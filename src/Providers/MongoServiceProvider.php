<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 23.04.2015
 * Time: 03:57
 */

namespace App\Providers;


use League\Container\ServiceProvider;
use League\Monga;

class MongoServiceProvider extends ServiceProvider
{
    protected $provides = [
        'mongo'
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
        // Get server connection
        $monga = Monga::connection('localhost', array());

        $this->getContainer()['mongo'] = $monga;
    }
}