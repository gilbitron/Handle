<?php

namespace Handle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Humbug\SelfUpdate\Updater;

class RollbackCommand extends Command
{
    protected function configure()
    {
        $this->setName('rollback')->setDescription('Rollback an update to the Handle CLI');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $updater = new Updater();
        $updater->getStrategy()->setPharUrl('https://gilbitron.github.io/Handle/handle.phar');
        $updater->getStrategy()->setVersionUrl('https://gilbitron.github.io/Handle/handle.phar.version');

        try {
            $result = $updater->rollback();
            if (!$result) {
                $output->writeln('<error>There was an error rolling back the update</error>');
                return;
            }

            $output->writeln('<info>Rollback successful</info>');
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}