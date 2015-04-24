<?php namespace App\Controller;

use App\Contracts\BaseController;
use League\Monga;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends BaseController
{

    /**
     * @return mixed
     */
    public function listTable()
    {
        $tableModel = $this->getContainer()->get('tableModel');
        try {
            $currentTable = $tableModel->getFullTable();
            $this->getContainer()->get('cli')->table($currentTable);
        } catch (\Exception $ex) {
            $this->getContainer()->get('cli')->error($ex->getMessage());
        }

        return ;
    }

    public function computeMinMatchesFor($teamString = '')
    {
        try {
            $teamModel = $this->getContainer()->get('teamModel');
            $minNoOfMatches = $teamModel->computeMinNoOfMatches($teamString);
            $this->getContainer()->get('cli')->blue($minNoOfMatches);
        } catch (\Exception $ex) {
            $this->getContainer()->get('cli')->error($ex->getMessage());
        }

        return ;
    }
}
