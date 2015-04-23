<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 20.04.2015
 * Time: 00:33
 */

namespace App\Models;

use App\Contracts\TemporaryTableInterface;

class Team {
    /**
     * @var TemporaryTableInterface
     */
    private $table;

    protected $fullTable = [];
    protected $positionTable = [];
    protected $temporaryTable = [];

    protected $matches = 0;

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

        $table = $this->computePositionTable($this->fullTable);

        if ($table[$teamString]['position'] !== 1) {
            $table[$teamString]['position'] = $this->makeChampion($teamString);
        }

        return $this->matches;
    }

    /**
     * @param $table
     * @return array
     */
    public function computePositionTable($table)
    {
        $positionTable = [];
        $pos = 1;
        foreach($table as $key => $value) {
            $positionTable[$key]['position'] = $pos++;
        }

        return $positionTable;
    }

    /**
     * @param $teamString
     */
    private function makeChampion($teamString)
    {
    }
}