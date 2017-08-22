<?php

namespace Doxy\Loader;

use Doxy\Model\Config;
use Doxy\Model\Upstream;
use Doxy\Model\Server;
use Docker\Docker;

class ConfigLoader
{
    public function load(Docker $docker)
    {
        $config = new Config();

        $containerInfos = $docker->getContainerManager()->findAll();
        foreach ($containerInfos as $containerInfo) {
            $container = $docker->getContainerManager()->find($containerInfo->getId());
            //print_r($container);
            $env = $this->parseEnv($container->getConfig()->getEnv());
            //print_r($env);
            if (isset($env['VIRTUAL_HOST'])) {
                $server = new Server($env['VIRTUAL_HOST']);
                $config->getServers()->add($server);
                foreach ($container->getNetworkSettings()->getNetworks() as $network) {
                    $server->setContainerIp($network->getIpAddress());
                }

                $port = null;
                if (isset($env['VIRTUAL_PORT'])) {
                    $port = $env['VIRTUAL_PORT'];
                }
                foreach ($container->getNetworkSettings()->getPorts() as $key => $value) {
                    if (!$port) {
                        $port = explode('/', $key)[0];
                    }
                }
                $server->setContainerPort($port);
                //print_r($container->getNetworkSettings());exit();

                if (isset($env['LETSENCRYPT_EMAIL'])) {
                    $server->setLetsencryptEmail($env['LETSENCRYPT_EMAIL']);
                    $server->setSsl(true);
                }
            }
            //print_r($container->getConfig()->getEnv());
        }
        return $config;
    }

    private function parseEnv($lines) {
        $env = [];
        foreach ($lines as $line) {
            $part = explode('=', $line);
            $env[$part[0]] = $part[1];
        }
        return $env;
    }
}
