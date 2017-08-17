<?php

namespace Doxy\Generator;

use Doxy\Model\Config;
use RuntimeException;

class NginxGenerator
{
    public function generate(Config $config)
    {
        $path = rtrim(getenv('DOXY_NGINX_OUTPUT_PATH'), '/');
        if (!$path) {
            $path = '/etc/nginx/sites-enabled';
        }
        if (!$path || !file_exists($path)) {
            throw new RuntimeException("DOXY_NGINX_OUTPUT_PATH not configured correctly: " . $path);
        }

        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../../templates');
        $twig = new \Twig_Environment($loader, []);

        foreach ($config->getServers() as $server) {
            $filename = $path . '/' . $server->getName() . '.conf';
            $data = [];
            $data['server'] = $server;
            $content = $twig->render('nginx-server.conf.twig', $data);
            file_put_contents($filename, $content);
        }
    }
}
