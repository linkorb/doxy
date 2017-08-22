<?php

namespace Doxy;

use Doxy\Loader\ConfigLoader;
use Doxy\Generator\NginxGenerator;
use Docker\Docker;
use Docker\DockerClient;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use PidHelper\PidHelper;

class Doxy
{
    protected $docker;
    public function __construct()
    {
        $this->docker = new Docker();
    }

    public function watch($output)
    {
        $output->writeLn("Doxy: watching docker for changes in running containers");
        $lock = new PidHelper(sys_get_temp_dir(), 'doxy.pid');
        if (!$lock->lock()) {
            $output->writeln('<error>Other doxy process is already running, quiting.</error>');
            return;
        }

        $lastContainerIds = [];

        $running = true;
        $c = 0;
        $max = 10000;
        while ($c < $max) {
            $containerIds = [];
            $containers = $this->docker->getContainerManager()->findAll();
            foreach ($containers as $container) {
                //print_r($container->getId());
                //print_r($container);
                $containerIds[$container->getId()] = $container->getState();
            }

            // $output->writeLn('<comment>Checking...</comment>');
            // print_r($containerIds);
            // print_r($lastContainerIds);

            if ($lastContainerIds === $containerIds) {
                $output->writeLn(date('Y-m-d H:i:s') . " ($c): No changes</comment>");
                // state unchanged
            } else {
                $output->writeLn(date('Y-m-d H:i:s') . " ($c): Detected change in running container. Performing run.");
                $this->run($output);
            }
            $lastContainerIds = $containerIds;
            sleep(5);
            $c++ ;
        }


        $lock->unlock();

    }

    public function run($output)
    {
        $output->writeLn("Doxy: run");


        $output->writeLn(" - Loading config from Docker");
        $loader = new ConfigLoader();
        $config = $loader->load($this->docker);

        $output->writeLn(" - Generating nginx configuration");
        $generator = new NginxGenerator();
        $generator->generate($config);

        $output->writeLn(" - Restarting nginx");
        $process = new Process('service nginx restart');
        $process->run();

        if (!$process->isSuccessful()) {
            $output->writeLn(" - Failed: " . $process->getErrorOutput());
        } else {
            $output->writeLn(" - Success");
        }
    }
}
