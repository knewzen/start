<?php

namespace Perfumerlabs\Start\Application;

use Perfumer\Component\Container\Container;
use Perfumer\Framework\Application\AbstractApplication;
use Perfumer\Package\Framework\Bundle\ConsoleBundle as PerfumerConsoleBundle;
use Perfumerlabs\Start\Bundle\CommandBundle;
use Perfumerlabs\Start\Bundle\CommonBundle;
use Perfumerlabs\Start\Bundle\ProdBundle;

class ProdConsoleApplication extends AbstractApplication
{
    public function getBundles()
    {
        return [
            new PerfumerConsoleBundle(),
            new CommonBundle(),
            new CommandBundle(),
            new ProdBundle()
        ];
    }

    protected function before()
    {
        date_default_timezone_set('Asia/Almaty');

        define('ENV', 'prod');

        define('TMP_DIR', __DIR__ . '/../../tmp/');
    }

    protected function after(Container $container)
    {
        $container->get('propel.service_container');
    }
}
