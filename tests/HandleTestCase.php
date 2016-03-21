<?php

namespace Handle\Tests;

use Handle\Commands\BuildCommand;
use Handle\Commands\InitCommand;
use Symfony\Component\Console\Application;

class HandleTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var string
     */
    protected $sitePath;

    public function setUp()
    {
        $this->app = new Application();
        $this->app->add(new InitCommand());
        $this->app->add(new BuildCommand());
        
        $this->sitePath = HANDLE_TESTS_ROOT . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . 'site';
    }
}