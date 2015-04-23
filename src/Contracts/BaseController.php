<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 20.04.2015
 * Time: 01:03
 */

namespace App\Contracts;

use App\Providers\CLIServiceProvider;
use App\Providers\MongoServiceProvider;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Container\ContainerInterface;
use League\Monga;

abstract class BaseController implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function __construct(ContainerInterface  $container)
    {
        $this->setContainer($container);

        $this->registerProviders();
    }

    /**
     * @return $this
     */
    public function registerProviders()
    {
        $this->getContainer()->addServiceProvider(new MongoServiceProvider());
        $this->getContainer()->addServiceProvider(new CLIServiceProvider());

        return $this;
    }
}