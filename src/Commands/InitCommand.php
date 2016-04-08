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

        try {
            $this->copyStructure($path, $output);
            $output->writeln('<info>Site created</info>');
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    /**
     * Copy the site structure to the given path
     *
     * @param string $sitePath
     * @param OutputInterface $output
     */
    protected function copyStructure($sitePath, OutputInterface $output)
    {
        $source = HANDLE_ROOT . DIRECTORY_SEPARATOR . 'init-structure';
        $rdi    = new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS);
        $rii    = new \RecursiveIteratorIterator($rdi, \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($rii as $fileInfo) {
            if ($fileInfo->isDir()) {
                if (!is_dir($sitePath . DIRECTORY_SEPARATOR . $rii->getSubPathName())) {
                    mkdir($sitePath . DIRECTORY_SEPARATOR . $rii->getSubPathName());
                    $output->writeln('Creating directory: ' . $rii->getSubPathName() . '...');
                }
            } else {
                if (!file_exists($sitePath . DIRECTORY_SEPARATOR . $rii->getSubPathName())) {
                    copy($fileInfo, $sitePath . DIRECTORY_SEPARATOR . $rii->getSubPathName());
                    $output->writeln('Creating file: ' . $rii->getSubPathName() . '...');
                }
            }
        }
    }
}