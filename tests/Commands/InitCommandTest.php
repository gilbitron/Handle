<?php

use Handle\Tests\HandleTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class InitCommandTest extends HandleTestCase
{
    private $command;

    public function setUp()
    {
        parent::setUp();

        $this->command = $this->app->find('init');
    }

    public function testExecute()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'command' => $this->command->getName(),
            'path'    => $this->sitePath,
        ]);

        $this->assertFileExists($this->sitePath . DIRECTORY_SEPARATOR . 'config.yml');
        $this->assertFileExists($this->sitePath . DIRECTORY_SEPARATOR . '_content' . DIRECTORY_SEPARATOR . 'index.md');
        $this->assertFileExists($this->sitePath . DIRECTORY_SEPARATOR . '_content' . DIRECTORY_SEPARATOR . 'about.md');
        $this->assertTrue(is_dir($this->sitePath . DIRECTORY_SEPARATOR . '_cache'));
        $this->assertTrue(is_dir($this->sitePath . DIRECTORY_SEPARATOR . '_content'));
        $this->assertTrue(is_dir($this->sitePath . DIRECTORY_SEPARATOR . '_themes'));
        $this->assertTrue(is_dir($this->sitePath . DIRECTORY_SEPARATOR . '_themes' . DIRECTORY_SEPARATOR . 'default'));
    }
}