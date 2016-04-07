<?php

namespace Handle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Humbug\SelfUpdate\Updater;

class UpdateCommand extends Command
{
    protected function configure()
    {
        $this->setName('update')->setDescription('Update the Handle CLI');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $updater = new Updater();
        $updater->getStrategy()->setPharUrl('https://gilbitron.github.io/Handle/handle.phar');
        $updater->getStrategy()->setVersionUrl('https://gilbitron.github.io/Handle/handle.phar.version');

        try {
            $result = $updater->update();
            if (!$result) {
                $output->writeln('<info>No update available</info>');
                return;
            }

            $new = $updater->getNewVersion();
            $old = $updater->getOldVersion();
            $output->writeln(sprintf('<info>Updated from %s to %s</info>', $old, $new));
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}