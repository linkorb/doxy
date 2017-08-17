Doxy
====

Docker management tools

## Commands

### doxy nginx:configure

This command will scan all active containers for the `VIRTUAL_HOST` environment variable.
For each container that has this variable defined, an nginx configuration file is generated in `/etc/nginx/sites-enabled`.

The nginx configuration file will be configured to use the provided VIRTUAL_HOST as hostname.

It will forward incoming requests to the current container IP and the first port listed. Optionally you can specify
the container port using the `VIRTUAL_PORT` environment variable.

If an environment variable `LETSENCRYPT_EMAIL` is defined, the appropriate let's encrypt configuration is added to the configuration.

## Retrieve the initial certificate

    certbot certonly --standalone -d my.example.com

## License

MIT. Please refer to the [license file](LICENSE) for details.

## Brought to you by the LinkORB Engineering team

<img src="http://www.linkorb.com/d/meta/tier1/images/linkorbengineering-logo.png" width="200px" /><br />
Check out our other projects at [linkorb.com/engineering](http://www.linkorb.com/engineering).

Btw, we're hiring!
