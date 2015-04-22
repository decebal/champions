<?php

require('vendor/autoload.php');

$climate = new League\CLImate\CLImate;

// Get a connection
$monga = League\Monga::connection('localhost', array());

// Get the database
$database = $monga->database('champions');