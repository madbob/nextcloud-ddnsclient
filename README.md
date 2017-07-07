# DDNS Client

Easy access to your home-hosted NextCloud instance: register to a dynamic DNS provider, and configure automatic updates of IP binding through this simple application.

## Install

```
cd nextcloud/apps/
git clone https://github.com/madbob/nextcloud-ddnsclient.git
composer install
```

Once the app is enabled, it adds a configuration panel under the `Admin -> Sharing` section.

Be sure to [enabled a proper cron option](https://docs.nextcloud.com/server/12/admin_manual/configuration_server/background_jobs_configuration.html) for your Nextcloud instance.

