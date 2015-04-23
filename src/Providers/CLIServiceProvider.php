<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 23.04.2015
 * Time: 04:05
 */

namespace App\Providers;


use League\CLImate\CLImate;
use League\Container\ServiceProvider;

class CLIServiceProvider extends ServiceProvider
{
    protected $provides = [
        'cli'
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {
        $this->getContainer()['cli'] = new CLImate();
    }
}