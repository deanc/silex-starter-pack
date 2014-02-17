Application/Project Name
=========

Overview of project goes here

Installation
--------------

* Clone the repository
* Copy `config.default.php` to a new file in the same directory called `config.php` and fill in the configuration values.
* Run `composer install` in the project directory root
* Set up your vhost:

####Apache instructions:

```sh
<VirtualHost *:80>
     ServerAdmin webmaster@dummy-host2.example.com
     DocumentRoot "/Users/deanclatworthy/Projects/PROJECTNAME/web"
     <Directory "/Users/deanclatworthy/Projects/PROJECTNAME/web">
        Options -Indexes FollowSymLinks
        AllowOverride All
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)$ /index.php [QSA,L]
        </IfModule>
    </Directory>
     ServerName PROJECTNAME.dev
     ErrorLog "/private/var/log/apache2/PROJECTNAME.dev-error_log"
     CustomLog "/private/var/log/apache2/PROJECTNAME.dev-access_log" common
</VirtualHost>
```

* Go to `http://PROJECTNAME.dev` or whatever you set up your vhost as and your basic application should be loaded.

Admin Control Panel
---------

The admin control panel is located at `http://PROJECTNAME.dev/a/` and the admin login details are set in `config.php`.

If you want to include admin authentication in a controller, make sure you add the following line before defining your routes in the controller provider:
```sh
$controllers->before($app['adminAuth']);
```
