# Phraseanet Mail-Log Plugin

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
        # values : task_abstract::[LOG_DEBUG | LOG_INFO | LOG_WARNING |
        # LOG_ERROR | LOG_CRITICAL | LOG_ALERT]
        level: task_abstract::LOG_ERROR
        channels:
            task-manager.logger
            monolog
        subject: Log message
        recipients: john@sysadmin.com
        emitter: logger@system.com
```

 - level: optional, default to `\task_abstract::LOG_DEBUG`
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
