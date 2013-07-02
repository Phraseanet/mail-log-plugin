<?php

namespace Alchemy\Tests;

use Alchemy\Phrasea\Application;
use Alchemy\MailLogPluginService;
use Monolog\Logger;

class MailLogPluginServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testItShouldBeRegistered()
    {
        $app = new Application();
        $app->register(MailLogPluginService::create($app));

        $configuration = $this->getMockBuilder('Alchemy\Phrasea\Core\Configuration\Configuration')
            ->disableOriginalConstructor()
            ->getMock();
        $configuration->expects($this->any())
            ->method('offsetGet')
            ->with('plugins')
            ->will($this->returnValue(array(
                'mail-log-plugin' => array('level' => Logger::DEBUG),
            )));

        $app['phraseanet.configuration'] = $configuration;

        $logger = $this->getMockBuilder('Monolog\\Logger')
            ->disableOriginalConstructor()
            ->getMock();
        $logger->expects($this->once())
            ->method('pushHandler')
            ->with($this->isInstanceOf('Monolog\Handler\SwiftMailerHandler'));

        $app['task-manager.logger'] = $app->share(function () use ($logger) {
            return $logger;
        });

        $logger2 = $this->getMockBuilder('Monolog\\Logger')
            ->disableOriginalConstructor()
            ->getMock();
        $logger2->expects($this->once())
            ->method('pushHandler')
            ->with($this->isInstanceOf('Monolog\Handler\SwiftMailerHandler'));

        $app['monolog'] = $app->share(function () use ($logger2) {
            return $logger2;
        });

        $app->boot();
        $app['task-manager.logger'];
        $app['monolog'];
    }
}
