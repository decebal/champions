<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 23.04.2015
 * Time: 20:57
 */

namespace App\Contracts;

/**
 * Class TableTrait
 *
 * @package App\Contracts
 */
trait TableTrait
{

    /**
     * @param array $table
     * @return mixed
     */
    public function sortTable(array $table = [])
    {
        uasort($table, function ($lineA, $lineB) {
            if ($lineA['points'] == $lineB['points']) {
                return $lineA['gd'] > $lineB['gd'] ? -1 : 1;
            }

            return $lineA['points'] > $lineB['points'] ? -1 : 1;
        });

        return $table;
    }

    /**
     * @param array $table
     * @return array
     */
    public function computePositionTable(array $table = [])
    {
        $pos = 1;
        foreach($table as $key => $value) {
            $table[$key]['position'] = $pos++;
        }

        return $table;
    }

    /**
     * @param $championLine
     * @param $auxTable
     * @return array|mixed
     */
    protected function rebuildTable($championLine, $auxTable)
    {
        $auxTable[$championLine['team']] = $championLine;
        $auxTable = $this->sortTable($auxTable);
        $auxTable = $this->computePositionTable($auxTable);

        return $auxTable;
    }
}