<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 23.04.2015
 * Time: 11:13
 */

namespace App\Contracts;


trait ChampionTrait
{
    public $matches = 0;

    /**
     * @param $current
     * @param $championLine
     * @param $buff
     * @return
     */
    protected function subtractVictories($current, $championLine, $buff)
    {
        $victories = ($current['points'] - ($championLine['points'] + $buff)) / 3;

        if ($victories <= $current['v']) {
            $pointsTaken = $victories * 3;
        } else {
            $victories = $current['v'];
            $pointsTaken = $current['v'] * 3;
        }

        $current['v'] -= $victories;
        $current['matches'] -= $victories;
        $this->matches += $victories;
        $current['points'] -= $pointsTaken;

        return $current;
    }
}