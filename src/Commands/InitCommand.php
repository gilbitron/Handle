<?php

namespace Handle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    protected function configure()
    {
        $this->setName('init')
            ->setDescription('Create the Handle site structure in the given directory')
            ->addArgument('path', InputArgument::OPTIONAL, 'Path to create your Handle site in');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');
        if (!$path || $path == '.') {
            $path = getcwd();
        }

        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true)) {
                $output->writeln('<error>Error creating root folder ' . $path . '</error>');
            }
        }

        $output->writeln($path);
    }
}