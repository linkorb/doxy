<?php

namespace Doxy\Command;

use RuntimeException;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Docker\Docker;
use Docker\DockerClient;
use Doxy\Loader\ConfigLoader;
use Doxy\Generator\NginxGenerator;

class NginxConfigureCommand extends Command
{
    public function configure()
    {
        $this->setName('nginx:configure')
            ->setDescription('Auto configure nginx sites and upstreams from docker')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeLn("<info>Doxy:</info>");

        $client = new DockerClient([
            'remote_socket' => getenv('DOCKER_HOST'),
            'ssl' => false,
        ]);
        $docker = new Docker($client);

        $loader = new ConfigLoader();
        $config = $loader->load($docker);

        //print_r($config);

        $generator = new NginxGenerator();
        $generator->generate($config);

        $process = new Process('service nginx restart');
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

    }
}
