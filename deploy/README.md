# Deployment

## Backend

### Binary Dependencies

- PHP 5.4+

- Apache / Nginx

- MongoDB 2.4+

- Redis

- php-apc (extension)

- php-mongo 1.4+ (extension)

- php-redis (extension)

- php-phalcon 1.2+ (extension)

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

**Nginx**

```
    location @rewrite {
        rewrite ^/(.*)$ /index.php?_url=/$1;
    }
```

#### Crossdomain

```
<FilesMatch "\.(ttf|otf|eot|woff|svg)$">
    <IfModule mod_headers.c>
        Header set Access-Control-Allow-Origin "*"
    </IfModule>
</FilesMatch>
```

## Frontend

When deploying to the production environment, the following scripts should be executed:

- `bundle-ext-lib.js`

  Bundle submodules of `www/static/lib/vijos-ext` into `vijos-ext.js`
