<?php

require('vendor/autoload.php');

$climate = new League\CLImate\CLImate;

// Get a connection
$monga = League\Monga::connection('localhost', array());