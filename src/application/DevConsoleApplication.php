<?php

namespace Start\Application;

use Perfumer\Component\Container\Container;
use Perfumer\Framework\Application\AbstractApplication;
use Perfumer\Package\Framework\Bundle\ConsoleBundle as PerfumerConsoleBundle;
use Start\Bundle\ConsoleBundle as StartConsoleBundle;

class DevConsoleApplication extends AbstractApplication
{
    public function getBundles()
    {
        return [
            new PerfumerConsoleBundle(),
            new StartConsoleBundle()
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