<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 20.04.2015
 * Time: 00:41
 */

namespace App\Contracts;


interface TemporaryTableInterface
{
    /**
     * @return mixed
     */
    function buildFullTable();

    /**
     * @return mixed
     */
    function getFullTable();
}