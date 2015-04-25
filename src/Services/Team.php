<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 20.04.2015
 * Time: 00:33
 */

namespace App\Services;

use App\Contracts\ChampionTrait;
use App\Contracts\TableTrait;
use App\Contracts\TemporaryTableInterface;
use League\CLImate\CLImate;

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
     * @var bool
     */
    private $skip = false;

    /**
     * @param TemporaryTableInterface $table
     * @param CLImate $cli
     */
    function __construct(TemporaryTableInterface $table, CLImate $cli)
    {
        $this->table = $table;
        $this->setFullTable($table->getFullTable());
        $this->cli = $cli;
    }

    /**
     * @param array $fullTable
     */
    public function setFullTable($fullTable)
    {
        $this->fullTable = $fullTable;
    }

    public function skipEqualities($flag = true)
    {
        $this->skip = $flag;

        return $this;
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
        $table = $table?:$this->fullTable;
        $this->setTemporaryQueue($teamString, $table);
        $championLine = [];
        $championLine['team'] = '';

        if ($this->temporaryTableQueue->isEmpty()) {
            return $this;
        }

        while (!($championLine['team'] === $teamString)) {
            $championLine = $this->temporaryTableQueue->extract();
        }

        if ($championLine['position'] == 1) {
            return $this;
        }

        //get matches between teams in current queue
        $directMatches = $this->table->getMatchesBetween($table, $teamString);
        $queueCopy = clone $this->temporaryTableQueue;

        $auxTable = [];
        while($this->temporaryTableQueue->valid()) {
            $current = $this->temporaryTableQueue->current();

            $pointDiff = $current['points'] - $championLine['points'];
            $buff = ($current['points'] - $championLine['points']) % 3;
            if ($pointDiff <= 2
                && !$this->skip
                && !$queueCopy->isEmpty()) {

                $equalityPointsSubtracted = false;
                //check if any equalities might be subtracted
                foreach ($directMatches as $match) {
                    if (
                        in_array(
                            $current['team'],
                            [$match['HomeTeam'], $match['AwayTeam']]
                        )
                        && $match['FTHG'] == $match['FTAG']
                    ) {
                        $opponentName = ($match['HomeTeam'] == $current['team'])
                            ? $match['AwayTeam']
                            : $match['HomeTeam'];

                        $opponentLine = $this->findTeamInQueue($queueCopy, $opponentName);

                        //if the opponent is already below our wannabe champion, just subtract a win
                        if (!$opponentLine) {
                            break;
                        }

                        $auxTable[$current['team']] = $this->subtractEqualities($current);
                        $current['e'] = $auxTable[$current['team']]['e'];
                        $current['matches'] = $auxTable[$current['team']]['matches'];
                        $current['points'] = $auxTable[$current['team']]['points'];

                        $auxTable[$opponentName] = $this->subtractEqualities($opponentLine, false);
                        $equalityPointsSubtracted = true;

                        //if already under my champion exit
                        if (($current['points'] - $championLine['points']) < 0) {
                            break;
                        }
                    }
                }

                //update table if there are changes
                if ($equalityPointsSubtracted) {
                    $auxTable = $this->completeAuxTable($auxTable);
                    $auxTable = $this->rebuildTable($championLine, $auxTable);
                    return $this->makeChampion($teamString, $auxTable);
                }

                //continue
                $buff -= 3;
            } elseif ($queueCopy->isEmpty() || $this->skip) {
                $buff -= 3;
            }

            if ($pointDiff >= 0) {
                $auxTable[$current['team']] = $this->subtractVictories(
                    $current,
                    $championLine,
                    $buff
                );
            }

            $this->temporaryTableQueue->next();
        }

        //prepare the table for re-compute
        $auxTable = $this->rebuildTable($championLine, $auxTable);

        return $this->makeChampion($teamString, $auxTable);
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

    /**
     * @param \SplPriorityQueue $queueCopy
     * @param $opponentName
     * @return array
     * @throws \Exception
     */
    protected function findTeamInQueue(\SplPriorityQueue $queueCopy, $opponentName)
    {
        if ($queueCopy->isEmpty()) {
            throw new \Exception('Empty queue!');
        }

        $foundTeam = [];
        $queueCopy->rewind();
        while ($queueCopy->valid()) {
            $opponentData = $queueCopy->current();
            if ($opponentData['team'] == $opponentName) {
                $foundTeam = $opponentData;
                break;
            }

            $queueCopy->next();
        }
        unset($opponentData);

        return $foundTeam;
    }

    /**
     * @param $auxTable
     * @return array
     */
    protected function completeAuxTable($auxTable)
    {
        $queueCopy = clone $this->temporaryTableQueue;
        $queueCopy->rewind();
        while ($queueCopy->valid()) {
            $currentNode = $queueCopy->current();
            if (!isset($auxTable[$currentNode['team']])) {
                $auxTable[$currentNode['team']] = $currentNode;
            }
            $queueCopy->next();
        }
        return $auxTable;
    }

}