# Deployment

## Backend

### Binary Dependencies

- PHP 5.4+

- Apache / Nginx

- MongoDB 2.4+

- Redis

- RabbitMQ

- php-openssl (native extension)

- php-curl (native extension)

- php-mbstring (native extension)

- php-gettext (native extension)

- php-mongo 1.4+ (extension)

- php-redis (extension)

- php-phalcon 1.2+ (extension)

### OpenVJ Services

The following services are components of OpenVJ and should be running in the background. Please follow their own deployment instructions.

- [openvj-bg-service](https://github.com/vijos/openvj-bg-service)

- [openvj-git-service](https://github.com/vijos/openvj-git-service)

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

- Session.session_id

## Frontend

Run [grunt](http://gruntjs.com/getting-started) tasks before production deployment:

```bash
npm install -g grunt-cli  # install grunt

cd src/public/static/lib/vijos-ext
npm install               # install grunt dependencies
grunt production          # run tasks

cd src/public/view/flat
npm install
grunt production
```

For development purpose:

```bash
npm install -g grunt-cli  # install grunt

cd src/public/static/lib/vijos-ext
npm install               # install grunt dependencies
grunt                     # run tasks

cd src/public/view/flat
npm install
grunt
```