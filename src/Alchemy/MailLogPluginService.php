<?php

/*
 * This file is part of Phraseanet Mail-Log plugin
 *
 * (c) 2005-2013 Alchemy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy;

use Alchemy\Phrasea\Application as PhraseaApplication;
use Alchemy\Phrasea\Plugin\PluginProviderInterface;
use Monolog\Logger;
use Monolog\Handler\SwiftMailerHandler;
use Silex\Application;

class MailLogPluginService implements PluginProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['mail-log-plugin.configuration'] = $app->share(function (Application $app) {
            $conf = array();

            if (isset($app['phraseanet.configuration']['plugins']['mail-log-plugin'])) {
                $conf = $app['phraseanet.configuration']['plugins']['mail-log-plugin'];
            }

            return array_replace(array(
                'subject'    => '',
                'recipients' => array(),
                'emitter'    => null,
                'level'      => Logger::DEBUG,
                'channels'   => $app['log.channels'],
            ), $conf);
        });

        $app['mail-log-plugin.recipients'] = $app->share(function (Application $app) {
            return $app['mail-log-plugin.configuration']['recipients'];
        });

        $app['mail-log-plugin.emitter'] = $app->share(function (Application $app) {
            return $app['mail-log-plugin.configuration']['emitter'];
        });

        $app['mail-log-plugin.message'] = $app->share(function (Application $app){
            $message = new \Swift_Message($app['mail-log-plugin.configuration']['subject']);
            if (null !== $app['mail-log-plugin.emitter']) {
                $message->setFrom($app['mail-log-plugin.emitter']);
            }
            $message->setTo($app['mail-log-plugin.recipients']);
            $message->setContentType('text/plain');

            return $message;
        });

        $app['mail-log-plugin.handler'] = $app->share(function (Application $app) {
            $level = $app['mail-log-plugin.configuration']['level'];

            if (defined($level)) {
                $level = constant($level);
            }

            return new SwiftMailerHandler($app['mailer'], $app['mail-log-plugin.message'], $level);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        foreach ((array) $app['mail-log-plugin.configuration']['channels'] as $channel) {
            $app[$channel] = $app->share(
                $app->extend($channel, function($logger, $app) {
                    $logger->pushHandler($app['mail-log-plugin.handler']);

                    return $logger;
                })
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function create(PhraseaApplication $app)
    {
        return new static();
    }
}
