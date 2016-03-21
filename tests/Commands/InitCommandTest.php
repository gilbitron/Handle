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

        $this->assertFileExists($this->sitePath . DIRECTORY_SEPARATOR . '.htaccess');
        $this->assertFileExists($this->sitePath . DIRECTORY_SEPARATOR . 'config.yml');
        $this->assertTrue(is_dir($this->sitePath . DIRECTORY_SEPARATOR . 'content'));
        $this->assertTrue(is_dir($this->sitePath . DIRECTORY_SEPARATOR . 'public'));
        $this->assertTrue(is_dir($this->sitePath . DIRECTORY_SEPARATOR . 'themes'));
        $this->assertTrue(is_dir($this->sitePath . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'default'));
    }
}