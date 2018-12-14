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

    public function getChoice()
    {
        $round_number = $this->result->getNbRound();
        $opponent_name = $this->result->getStatsFor($this->opponentSide)['name'];
        if ($round_number === 9) {
            if ($opponent_name === "Vcollette")
                return parent::friendChoice();
            return parent::foeChoice();
        }

        $opponent_last = $this->result->getLastChoiceFor($this->opponentSide);
        if ($opponent_last === "foe")
            $this->state_of_mind = 1;
        if ($this->state_of_mind === 0)
            return parent::friendChoice();
        return parent::foeChoice();
    }
}