<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 20.04.2015
 * Time: 01:03
 */

namespace App\Contracts;


use League\CLImate\CLImate;
use League\Monga;

abstract class BaseController
{
    /**
     * @var CLImate
     */
    protected $output;

    /**
     * @var Monga
     */
    protected $mongoConnection;

    public function __construct(CLImate $output, Monga\Connection $mongoConnection)
    {
        $this->output = $output;
        $this->mongoConnection = $mongoConnection;
    }

    /**
     * @return CLImate
     */
    public function getOutput()
    {
        return $this->output;
    }
}