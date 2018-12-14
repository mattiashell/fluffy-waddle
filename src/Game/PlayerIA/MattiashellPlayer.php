<?php

namespace Hackathon\PlayerIA;

use Hackathon\Game\Result;

/**
 * Class MattiashellPlayer
 * @package Hackathon\PlayerIA
 * @author MattiasH
 */
class MattiashellPlayer extends Player
{
    protected $mySide;
    protected $opponentSide;
    protected $result;
    protected $state_of_mind = 0;
    protected $number_of_mad_turn = 0;

    public function getChoice()
    {
        $max_turn = 10;
        $round_number = $this->result->getNbRound();
        $opponent_name = $this->result->getStatsFor($this->opponentSide)['name'];
        $T9_gang = array("Vcollette", "Santost", "Paultato");
        if ($round_number === 9) {
            if (in_array($opponent_name, $T9_gang))
                return parent::friendChoice();
            return parent::foeChoice();
        }

        $opponent_last = $this->result->getLastChoiceFor($this->opponentSide);
        if ($opponent_last === "foe")
            $this->state_of_mind = 1;
        if ($this->state_of_mind === 0 or $this->number_of_mad_turn > $max_turn / 5) {
            $this->number_of_mad_turn = 0;
            return parent::friendChoice();
        }
        $this->number_of_mad_turn += 1;
        if ($this->number_of_mad_turn > $max_turn / 5) {
            $this->number_of_mad_turn = 0;
            return parent::friendChoice();
        }

        return parent::foeChoice();
    }
}