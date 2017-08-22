Doxy
=======================================================

Dynamic proxy configuration for docker containers

## Commands

### doxy run

This command will scan all active containers for the `VIRTUAL_HOST` environment variable.

For each running container, a section is generated in the config file.
The resulting `doxy.conf` will be generated in the path defined in `DOXY_NGINX_OUTPUT_PATH` (usually `/etc/nginx/sites-enabled`).

The Nginx configuration section will be configured to use the provided VIRTUAL_HOST as hostname.

It will forward incoming requests to the current container IP and the first port listed. Optionally you can specify
the container port using the `VIRTUAL_PORT` environment variable.

If an environment variable `LETSENCRYPT_EMAIL` is defined, the appropriate let's encrypt configuration is added to the configuration.

## Configuration

Doxy will load it's environment variable configuration from `~/.doxy` or `/etc/doxy.conf`.

You can use the included `doxy.conf.dist` as an example.

## Let's encrypt: Retrieve the initial certificate

Doxy does not load the initial cert for your new server. The simplest way to retrieve the intial certificate:

    service nginx stop
    certbot certonly --standalone -d my.example.com
    service nginx start

In order to renew existing certificates you don't need to restart nginx, simply run

    certbot renew

## Building doxy.phar

This project uses [box2](https://box-project.github.io/box2/s) to generate the .phar file.

Simply install the latest box.phar in your local directory and run it's `build` command:

    curl -LSs https://box-project.github.io/box2/installer.php | php
    php box.phar build

## License

MIT. Please refer to the [license file](LICENSE) for details.

## Brought to you by the LinkORB Engineering team

<img src="http://www.linkorb.com/d/meta/tier1/images/linkorbengineering-logo.png" width="200px" /><br />
Check out our other projects at [linkorb.com/engineering](http://www.linkorb.com/engineering).

Btw, we're hiring!
