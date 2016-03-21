<?php

use Handle\Tests\HandleTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class BuildCommandTest extends HandleTestCase
{
    private $command;

    public function setUp()
    {
        parent::setUp();

        $this->command = $this->app->find('build');

        $initCommand   = $this->app->find('init');
        $commandTester = new CommandTester($initCommand);
        $commandTester->execute([
            'command' => $initCommand->getName(),
            'path'    => $this->sitePath,
        ]);
    }

    public function testExecute()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'command' => $this->command->getName(),
            '--path'  => $this->sitePath,
        ]);

        $this->assertFileExists($this->sitePath . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'index.md');
        $this->assertFileExists($this->sitePath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'index.html');
    }
}