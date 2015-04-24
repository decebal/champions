<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 20.04.2015
 * Time: 00:33
 */

namespace App\Models;

use App\Contracts\ChampionTrait;
use App\Contracts\TableTrait;
use App\Contracts\TemporaryTableInterface;

class Team
{
    use ChampionTrait;
    use TableTrait;

    /**
     * @var TemporaryTableInterface
     */
    private $table;

    protected $fullTable = [];
    protected $positionTable = [];
    /**
     * @var array|\SplPriorityQueue
     */
    protected $temporaryTableQueue = [];

    /**
     * @param TemporaryTableInterface $table
     */
    function __construct(TemporaryTableInterface $table)
    {
        $this->table = $table;
        $this->setFullTable($table->getFullTable());
    }

    /**
     * @param array $fullTable
     */
    public function setFullTable($fullTable)
    {
        $this->fullTable = $fullTable;
    }

    /**
     * @param string $teamString
     * @return int
     * @throws \Exception
     */
    public function computeMinNoOfMatches($teamString = '')
    {
        $teamString = trim(ucfirst($teamString));
        if (!isset($this->fullTable[$teamString])) {
            throw new \Exception('Please ask for a team by the name in fullTable');
        }

        $this->fullTable = $this->computePositionTable($this->fullTable);

        //already champion
        if ($this->fullTable[$teamString]['position'] !== 1) {
            $this->makeChampion($teamString);
        }

        return $this->matches;
    }

    /**
     * @param string $teamString
     * @param array $table
     * @return $this
     */
    public function makeChampion($teamString = '', $table = [])
    {
        $this->setTemporaryQueue($teamString, ($table?:$this->fullTable));

        $championLine = [];
        $championLine['team'] = '';

        while (!($championLine['team'] === $teamString)) {
            $championLine = $this->temporaryTableQueue->extract();
            if ($this->temporaryTableQueue->isEmpty()) {
                return $this;
            }
        }

        if ($championLine['position'] == 1) {
            return $this;
        }

        if ($this->temporaryTableQueue->isEmpty()) {
            return $this;
        }

        $auxTable = [];
        while($this->temporaryTableQueue->valid()) {
            $current = $this->temporaryTableQueue->current();

            $pointDiff = $current['points'] - $championLine['points'];
            $buff = ($current['points'] - $championLine['points']) % 3;
            if ($pointDiff <= 2) {
                //get matches between teams in current queue

                //check if any equalities might be subtracted

                //update table if there are changes

                //continue
                $buff -= 3;
            }

            $auxTable[$current['team']] = $this->subtractVictories(
                $current,
                $championLine,
                $buff
            );

            $this->temporaryTableQueue->next();
        }

        //prepare the table for re-compute
        $auxTable = $this->rebuildTable($championLine, $auxTable);
        $this->makeChampion($teamString, $auxTable);

        return $this;
    }

    /**
     * @param $teamString
     * @param $table
     */
    protected function setTemporaryQueue($teamString, $table)
    {
        $this->temporaryTableQueue = new \SplPriorityQueue();

        foreach ($table as $team => $line) {
            $this->temporaryTableQueue->insert($line, $line['position']);
            if ($teamString == $team) {
                break;
            }
        }
    }

}