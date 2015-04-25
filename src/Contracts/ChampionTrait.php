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
     * @var CLImate
     */
    private $cli;

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

        if ($this->cli->arguments->defined('verbose')) {
            $this->cli->whisper(sprintf(
                'Current point difference is %d, my champion has %d, %s has %d',
                $current['points'] - $championLine['points'], $championLine['points'],
                $current['team'],
                $current['points']
            ));
        }

        $current['v'] -= $victories;
        $current['matches'] -= $victories;
        $this->matches += $victories;
        $current['points'] -= $pointsTaken;
        if ($this->cli->arguments->defined('verbose')) {
            $this->cli->whisper(sprintf('Subtracted %s victories from %s', $victories, $current['team']));
        }

        return $current;
    }

    /**
     * @param $current
     */
    public function subtractEqualities($current, $countMatches = true)
    {
        $equalitiesNo = 1;

        if ($equalitiesNo <= $current['e']) {
            $pointsTaken = $equalitiesNo * 1;
        } else {
            $equalitiesNo = $current['e'];
            $pointsTaken = $current['e'] * 1;
        }

        if ($this->cli->arguments->defined('verbose')) {
            $this->cli->whisper(sprintf(
                'Current team %s has %d',
                $current['team'],
                $current['points']
            ));
        }

        $current['e'] -= $equalitiesNo;
        $current['matches'] -= $equalitiesNo;
        $current['points'] -= $pointsTaken;
        if ($countMatches) {
            $this->matches += $equalitiesNo;
        }

        if ($this->cli->arguments->defined('verbose')) {
            $this->cli->whisper(sprintf('Subtracted %s equalities from %s', $pointsTaken, $current['team']));
        }

        return $current;
    }

    public function restartCount()
    {
        $this->matches = 0;

        return $this;
    }
}