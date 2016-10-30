<?php

namespace Start\Application;

use Perfumer\Component\Container\Container;
use Perfumer\Framework\Application\AbstractApplication;
use Perfumer\Package\Framework\Bundle\HttpBundle as PerfumerHttpBundle;
use Start\Bundle\HttpBundle as StartHttpBundle;

class DevHttpApplication extends AbstractApplication
{
    public function getBundles()
    {
        return [
            new PerfumerHttpBundle(),
            new StartHttpBundle()
        ];
    }

    protected function before()
    {
        date_default_timezone_set('Asia/Almaty');

        define('ENV', 'dev');

        define('TMP_DIR', __DIR__ . '/../../tmp/');
    }

    protected function after(Container $container)
    {
        $container->get('propel.service_container');
    }
}