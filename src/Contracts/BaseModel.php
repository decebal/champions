<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 23.04.2015
 * Time: 04:35
 */

namespace App\Contracts;

use League\Monga;

abstract class BaseModel
{
    /**
     * @var null|object
     */
    protected $mongo = null;
    /**
     * @var null|Monga\Collection
     */
    protected $mongoCollection = null;

    static protected $collection = '';

    public function __construct(Monga\Connection $MongoConnection)
    {
        // Get the database
        $this->mongo = $MongoConnection->database('champions');

        //set default collection object
        if (static::$collection) {
            $this->mongoCollection = $this->mongo->collection(static::$collection);
        }
    }

}