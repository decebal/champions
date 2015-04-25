<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 23.04.2015
 * Time: 04:13
 */

return [
    'tableModel' => [
        'class'     => 'App\Models\Table',
        'arguments' => ['mongo'],
    ],
    'teamService' => [
        'class'     => 'App\Services\Team',
        'arguments' => ['tableModel', 'cli'],
    ],
];