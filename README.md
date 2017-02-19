Silex Starter Pack
=========

The Silex Starter Pack is a simple bootstrap to help you begin your project with Silex. It includes:

  - An admin login system and control panel (styled mostly using Bootstrap)
  - A very basic user authentication system allowing for sign up/log in/log out with users coming from a database.
  - A console application allowing quick and easy development of console tasks

Installation
--------------

* Create your project by typing the following command:

```sh
    composer create-project deanc/silex-starter-pack your-project-name dev-master
```

* Decide on your any namespaces you are going to want to autoload and create the relevent directory structure under the `src` directory.
* Open up composer.json and adjust the `autoload` section to load your new namespace. An example would be:

```sh
    ,"autoload": {
        "psr-0": {
            "DC\\SilexStarterPack": "src/"
            ,"YourName\\SomeProjectName" : "src/"
        }
    }
```

* Copy `app/config.default.php` to a new file in the same directory called `app/config.php` and fill in the configuration values.
* Run `composer install`
* Set up your vhost:

#### Build in PHP web-server quick start instructions:

Navigate into the web directory and type `php -S 127.0.0.1:8080`

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

The admin control panel is located at `http://ssp.dev/a/`. If you want to add any new admin controllers add them under your own namespace such as `YourName\Project\Controller\Admin\Project.php` for a `Project` admin controller. Then mount it in index.php like so:

```sh
$app->mount('/a', new YourName\Project\Controller\Admin\Project());
```

Bonus stuff
-----

#### Twilio

If you want to use Twilio require their library:

`composer require twilio/sdk`

Enable the utility in `app/config.php`:

`define('TWILIO_ENABLED', true);`

Use it as follows:

```php
$app['twilo']->send($from, $to, $text);
```


Reccommended Libraries
-----

* Pagination: "soup/paginator" https://packagist.org/packages/soup/paginator
* Mailing: "swiftmailer/swiftmailer" https://packagist.org/packages/swiftmailer/swiftmailer

Author
-----
[Dean Clatworthy](http://deanclatworthy.com)
