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
        $climate = new CLImate();

        $climate->arguments->add([
            'team' => [
                'prefix'       => 't',
                'longPrefix'   => 'team',
                'description'  => 'Name of the team you want to make champion',
                'defaultValue' => 'Man City',
            ],
            'fullTable' => [
                'prefix'       => 'f',
                'longPrefix'   => 'full-table',
                'description'  => 'Print the full table',
                'noValue'      => true,
            ],
            'verbose' => [
                'prefix'      => 'v',
                'longPrefix'  => 'verbose',
                'description' => 'Verbose output',
                'noValue'     => true,
            ],
            'help' => [
                'prefix'      => 'h',
                'longPrefix'  => 'help',
                'description' => 'Prints a usage statement',
                'noValue'     => true,
            ]
        ]);
        $this->getContainer()['cli'] = $climate;
    }
}