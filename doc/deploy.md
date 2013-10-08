# Deployment

## Backend Depencencies

### Hints for developers

You can use [Vagrant](http://www.vagrantup.com/) to quickly initialize your development environment. 

1. Download & install [Vagrant](http://downloads.vagrantup.com/) and [VirtualBox](https://www.virtualbox.org/wiki/Downloads).

2. Download [OpenVJ Vagrant box image](http://pan.baidu.com/share/link?shareid=4281126144&uk=3255084544) (`openvj-package.box`).

3. In a same directory, `git clone --recursive` [openvj](https://github.com/vijos/openvj.git), [openvj-git-service](https://github.com/vijos/openvj-git-service.git) and [openvg-bg-service](https://github.com/vijos/openvj-bg-service.git). 
   
   Put the downloaded `openvj-package.box` into `openvj` directory.

4. mkdir: `openvj-data`, `openvj-data/git`.

5. Copy `Vagrantfile.default` to `Vagrantfile`.
   
   ( You can follow the [Vagrantfile documentation](http://docs.vagrantup.com/v2/vagrantfile/index.html) and make your own changes, for example, use port-forwarding instead of private-network. )

6. Your directory tree should be similar to this:
   
   ```
    + the-openvj-project/       (any name)
      + openvj/                 (git://openvj)
        - openvj-package.box
        - Vagrantfile
        - Vagrantfile.default
        + .git/
        + doc/
        + src/
        - …
      + openvj-git-service/     (git://openvj-git-service)
        + .git/
        - …
      + openvj-bg-service/      (git://openvj-bg-service)
        + .git/
        - …
      + openvj-data/
        + git/
   ```

7. Run the following commands:
 
   ```bash
   #cd the-openvj-project
   cd openvj
   vagrant up
   ```

8. If you get Vagrant startup errors like: 

   ```
   The following SSH command responded with a non-zero exit status.
   Vagrant assumes that this means the command failed!
   ARPCHECK=no /sbin/ifup eth1 2> /dev/null
   ```
   
   Run the following commands:
   
   ```bash
    #cd the-openvj-project
    cd openvj
    vagrant ssh    # log into the virtual machine
      sudo -i      # get sudo
        rm -f /etc/udev/rules.d/70-persistent-net.rules
        rm -f /etc/sysconfig/network-scripts/ifcfg-eth1
        /etc/init.d/network restart
      exit
    exit           # return OS terminal
    vagrant reload # restart Vagrant
   ```

9. Modify `/etc/hosts` on UNIX-like OS or `.../system32/drivers/etc/hosts` on Windows. Add the following 2 lines:

   ```
   10.22.22.22 vijos.org
   10.22.22.22 www.vijos.org
   ```

10. Copy `(the-openvj-project)/openvj/src/app/configs/*.ini.default` to `*.ini`

PS: You can also install dependencies below by yourself without using Vagrant. See [installing instructions references](env_links.md).

PPS: Due to [a bug of VirtualBox](http://stackoverflow.com/questions/9479117/vagrant-virtualbox-apache2-strange-cache-behaviour), you may need to turn off sendfile (`sendfile off`) in `/etc/nginx/nginx.conf` in the VM.

PPPS: Do not forget to update submodules when pulling openvj: `git submodule foreach git pull`

### Binaries

- PHP 5.4+

- Apache / Nginx

- MongoDB 2.4+

- Redis

- RabbitMQ

- php-openssl (native extension)

- php-curl (native extension)

- php-mbstring (native extension)

- php-gettext (native extension)

- php-bcmath (native extension)

- php-gmp (native extension)

- php-mongo 1.4+ (extension)

- php-redis (extension)

- php-phalcon 1.2+ (extension)

- php-opcache(ZendOptimizerPlus) (extension, recommended)

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

## Backend Configrations

### Rewrite rules

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
location / {
    # if file exists return it right away
    if (-f $request_filename) {
        break;
    }

    # otherwise rewrite it
    if (!-e $request_filename) {
        rewrite ^(.+)$ /index.php?_url=$1 last;
        break;
    }
}
```

See http://docs.phalconphp.com/en/latest/reference/nginx.html for more information

### Crossdomain rules

**Apache**

```
<FilesMatch "\.(ico|css|js|gif|jpe?g|png|ttf|otf|eot|woff|svg)(\?[0-9]+)?$">
    <IfModule mod_headers.c>
        Header set Access-Control-Allow-Origin "*"
    </IfModule>
</FilesMatch>
```

**Nginx**

```
location ~* \.(ico|css|js|gif|jpe?g|png|ttf|otf|eot|woff|svg)(\?[0-9]+)?$ {
    add_header Access-Control-Allow-Origin "*";
}
```

### MongoDB

Ensure index:

- (TTL) `Session.time`

## Frontend

Run [grunt](http://gruntjs.com/getting-started) tasks before a production deployment:

```bash
npm install -g grunt-cli  # install grunt

cd src/public/static/lib
npm install               # install grunt dependencies
grunt production          # run tasks

cd src/public/view/flat
npm install
grunt production
```

Development:

```bash
npm install -g grunt-cli  # install grunt

cd src/public/static/lib
npm install               # install grunt dependencies
grunt                     # run tasks

cd src/public/view/flat
npm install
grunt
```
