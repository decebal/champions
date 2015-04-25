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
        $climate = $this->getContainer()->get('cli');
        try {
            $teamModel = $this->getContainer()->get('teamService');
            if ($climate->arguments->defined('verbose')) {
                $climate->br()->border('-*-', 50);
            }
            $minNoOfMatches = $teamModel->computeMinNoOfMatches($teamString);

            if ($climate->arguments->defined('verbose')) {
                $climate->br()->border('-*-', 50);
            }
            $teamModel->skipEqualities()->restartCount();
            $minNoOfMatchesWithoutEq = $teamModel->computeMinNoOfMatches($teamString);

            $min = min([$minNoOfMatchesWithoutEq, $minNoOfMatches]);

            $message = sprintf("The minimum number of matches for %s to be champion is: ", $teamString);
            $climate->green($message);
            $climate->yellow($min);
        } catch (\Exception $ex) {
            $climate->error($ex->getMessage());
        }

        return ;
    }
}
