<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 20.04.2015
 * Time: 00:35
 */

namespace App\Models;


use App\Contracts\BaseModel;
use App\Contracts\TemporaryTableInterface;
use League\Monga;

class Table extends BaseModel implements TemporaryTableInterface
{
    static protected $collection = 'fullTable';

    /**
     * @return mixed
     * @throws Monga\MongoCursorException
     * @throws \Exception
     */
    function buildFullTable()
    {
        /**
         * @var Monga
         */
        $awayTable = $this->mongo->collection('awayTeams')->find()->toArray();
        $homeTable = $this->mongo->collection('homeTeams')->find()->toArray();

        if (!$homeTable || !$awayTable) {
            throw new \Exception('Mongo collections are needed, please update your mongo database');
        }

        $fullTable = [];
        foreach ($awayTable as $key => $teamLine) {
            $fullTable[$key]['_id'] = $teamLine['_id'];
            $fullTable[$key]['team'] = $teamLine['_id'];
            $fullTable[$key]['matches'] = $teamLine['matches'];
            $fullTable[$key]['points'] = $teamLine['points'];
            $fullTable[$key]['gf'] = $teamLine['gf'];
            $fullTable[$key]['ga'] = $teamLine['ga'];
        }
        unset($teamLine);

        foreach ($homeTable as $key => $teamLine) {
            $fullTable[$key]['matches'] += $teamLine['matches'];
            $fullTable[$key]['points'] += $teamLine['points'];
            $fullTable[$key]['gf'] += $teamLine['gf'];
            $fullTable[$key]['ga'] += $teamLine['ga'];
            $fullTable[$key]['gd'] = $fullTable[$key]['gf'] - $fullTable[$key]['ga'];
        }
        unset($teamLine);

        uasort($fullTable, function($lineA, $lineB) {
            if ($lineA['points'] == $lineB['points']) {
                return $lineA['gd'] > $lineB['gd'] ? -1 : 1;
            }

            return $lineA['points'] > $lineB['points'] ? -1 : 1;
        });

        $this->mongoCollection->truncate();
        foreach ($fullTable as $line) {
            $this->mongoCollection->insert($line);
        }
        unset($line);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    function getFullTable()
    {
        $fullTableCount = $this->mongoCollection->find()->count();
        if (!$fullTableCount) {
            $this->buildFullTable();
        }

        $fullTable = $this->mongoCollection->find()->toArray();

        if (!$fullTable) {
            throw new \Exception('The fullTable cannot be built or has been build unsuccessfully!');
        }

        return $fullTable;
    }
}