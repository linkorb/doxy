<?php

namespace Doxy\Command;

use RuntimeException;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doxy\Doxy;

class RunCommand extends Command
{
    public function configure()
    {
        $this->setName('run')
            ->setDescription('Run doxy once')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $doxy = new Doxy();
        $doxy->run($output);
    }
}
