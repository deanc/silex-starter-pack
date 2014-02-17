Silex Starter Pack
=========

The Silex Starter Pack is a simple bootstrap to help you begin your project with Silex. It includeS:

  - An admin login system and control panel (styled using Bootstrap)
  - A very basic user authentication system allowing for sign up/log in/log out with users coming from a database.
  - A console application allowing quick and easy development of console tasks

Installation
--------------

* Clone the repository
* Decide on your any namespaces you are going to want to autoload and create the relevent directory structure under the `src` directory.
* Open up composer.json and adjust the `autoload` section to load your new namespace. If you want to keep the admin control panel you'll need to keep loading the `SilexStarterPack` namespace. An example would be:

```sh
    ,"autoload": {
        "psr-0": {
            "DC\\SilexStarterPack": "src/"
            ,"YourName\\SomeProjectName" : "src/"
        }
    }
```

* Copy `config.default.php` to a new file in the same directory called `config.php` and fill in the configuration values.
* Run `composer install`
* Set up your vhost:

####Apache instructions:

```sh
<VirtualHost *:80>
     ServerAdmin webmaster@dummy-host2.example.com
     DocumentRoot "/Users/deanclatworthy/Projects/silex-starter-pack/web"
     <Directory "/Users/deanclatworthy/Projects/silex-starter-pack/web">
        Options -Indexes FollowSymLinks
        AllowOverride All
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)$ /index.php [QSA,L]
        </IfModule>
    </Directory>
     ServerName ssp.dev
     ErrorLog "/private/var/log/apache2/silex-starter-pack.dev-error_log"
     CustomLog "/private/var/log/apache2/silex-starter-pack.dev-access_log" common
</VirtualHost>
```

(Nginx instructions coming soon...)

* Go to `http://ssp.dev` or whatever you set up your vhost as and your basic application should be loaded.

Admin Control Panel
---------

The admin control panel is located at `http://ssp.dev/a/` and the admin login details can be set in `config.php`. If you want to add any new admin controllers add them under your own namespace such as `YourName\Project\Controller\Admin\Project.php` for a `Project` admin controller. Then mount it in index.php like so:

```sh
$app->mount('/a', new YourName\Project\Controller\Admin\Project());
```

Take a look at how the `DC\SilexStarterPack\Controller\Admin\Index\` controller is built and make sure you call the admin authentication in every controller like so:

```sh
$controllers->before($app['adminAuth']);
```

User Login
---------

Information coming soon....

Console Applications
------

Info coming soon

Reccommended Libraries
-----

* Pagination: "soup/paginator" https://packagist.org/packages/soup/paginator
* Mailing: "swiftmailer/swiftmailer" https://packagist.org/packages/swiftmailer/swiftmailer

Author
-----
[Dean Clatworthy](http://deanclatworthy.com)
