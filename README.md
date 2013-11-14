# Phraseanet Mail-Log Plugin

[![Build Status](https://travis-ci.org/Phraseanet/mail-log-plugin.png?branch=master)](https://travis-ci.org/Phraseanet/mail-log-plugin)
A Mail logger plugin for [Phraseanet](https://github.com/alchemy-fr/Phraseanet);

## Installation

First, retrieve the latest version :

```
git clone https://github.com/Phraseanet/mail-log-plugin.git
```

Then, use Phraseanet Konsole to install the plugin (please be sure to run
the command with the right user - www-data for instance)

```
bin/console plugin:add /path/to/mail-log-plugin
```

## Configuration

Use the following options to configure the plugin in your `configuration.yml`

```yaml
plugins:
    mail-log-plugin:
        # values : [DEBUG | INFO | NOTICE | WARNING | ERROR | CRITICAL | ALERT | EMERGENCY]
        level: ERROR
        channels:
            task-manager.logger
            monolog
        subject: Log message
        recipients: john@sysadmin.com
        emitter: logger@system.com
```

 - level: optional, default to `DEBUG`
 - channels: optional, array, default to all channels.
 - subject: optional, string, default to 'Log message'
 - recipients: mandatory, array ; an array of recipients emails
 - emitter: mandatory, string ; the email of emitter

## Uninstall

Use Phraseanet Konsole to uninstall the plugin

```
bin/console plugin:remove mail-log-plugin
```

## License

Phraseanet Mail-Log plugin is released under the MIT license
