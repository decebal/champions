<?php namespace App\Controller;

use App\Contracts\BaseController;

class DefaultController extends BaseController
{
    public function listTable()
    {
        $currentTable = [
            [
                'position' => '1',
                'team' =>'Liverpool'
            ]
        ];
        return $this->output->table($currentTable);
    }

    public function computeMinMatchesFor($teamString = '')
    {
        return 0;
    }
}
