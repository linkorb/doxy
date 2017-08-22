<?php

namespace Doxy\Command;

use RuntimeException;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doxy\Doxy;

class WatchCommand extends Command
{
    public function configure()
    {
        $this->setName('watch')
            ->setDescription('Watch Docker for changes')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $doxy = new Doxy();
        $doxy->watch($output);
    }
}
