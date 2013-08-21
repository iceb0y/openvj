# Deployment

## Backend

### Binary Dependencies

- PHP 5.4+

- Apache / Nginx

- MongoDB 2.4+

- php-openssl (native extension)

- php-curl (native extension)

- php-mbstring (native extension)

- php-apc (extension)

- php-mongo 1.4+ (extension)

- php-phalcon 1.2+ (extension)

- php-sundown (extension)

### Libraries

OpenVJ uses [Composer](http://getcomposer.org/) to manage library dependencies.

After [installing Composer](http://getcomposer.org/doc/00-intro.md), you should install dependencies via the following commands.

```
cd src/app
php composer.phar install
```

### Config

#### Rewrite rules

**Apache**

```
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php?_url=/$1 [QSA,L]
</IfModule>
```

See http://docs.phalconphp.com/en/latest/reference/apache.html for more information

**Nginx**

```
    location @rewrite {
        rewrite ^/(.*)$ /index.php?_url=/$1;
    }
```

See http://docs.phalconphp.com/en/latest/reference/nginx.html for more information

#### Crossdomain

```
<FilesMatch "\.(ttf|otf|eot|woff|svg)$">
    <IfModule mod_headers.c>
        Header set Access-Control-Allow-Origin "*"
    </IfModule>
</FilesMatch>
```

### MongoDB Database

Please ensure these indexes:

- 'Session' -> 'session_id'

## Frontend

Grunt task:

```bash
cd task
grunt
```