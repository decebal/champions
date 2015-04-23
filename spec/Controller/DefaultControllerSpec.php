<?php

namespace spec\App\Controller;

use League\CLImate\CLImate;
use League\Monga\Connection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DefaultControllerSpec extends ObjectBehavior
{
    function let(CLImate $output, Connection $mongoConnection)
    {
        $this->beConstructedWith($output);
        $this->beConstructedWith($mongoConnection);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Controller\DefaultController');
    }
}
