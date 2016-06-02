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

        $this->assertFileExists($this->sitePath . DIRECTORY_SEPARATOR . 'index.html');
        $this->assertFileExists($this->sitePath . DIRECTORY_SEPARATOR . 'about' . DIRECTORY_SEPARATOR . 'index.html');
        $this->assertContains('Welcome to your Handle site!', file_get_contents($this->sitePath . DIRECTORY_SEPARATOR . 'index.html'));
        $this->assertContains('This is a test page.', file_get_contents($this->sitePath . DIRECTORY_SEPARATOR . 'about' . DIRECTORY_SEPARATOR . 'index.html'));
    }
}