<?php

namespace Hackathon\PlayerIA;

use Hackathon\Game\Result;

/**
 * Class MattiashellPlayer
 * @package Hackathon\PlayerIA
 * @author HellouinMattias
 */
class MattiashellPlayer extends Player
{
    protected $mySide;
    protected $opponentSide;
    protected $result;
    protected $state_of_mind = 0;
    protected $number_of_mad_turn = 0;
    protected $T9_gang = array("Vcollette", "Santost", "Paultato", "Neosia67");
    private $list_player = array(
        "Evekeys" => "evekeys",
        "Romainepita" => "Romain-Epita",
        "Paultato" => "Paultato",
        "Carlito5973" => "Carlito5973",
        "Benlie06" => "BenLi06",
        "Mobe94" => "mobe94",
        "Ghope" => "GHope",
        "Fransex" => "Fransex",
        "Kewynhe" => "Kewynhe",
        "Vcollette" => "vcollette",
        "Sky555v" => "sky555v",
        "Gdslmedl" => "GDSLmedl",
        "Felixdupriez" => "felixdupriez",
        "Danylgz" => "dany-lgz",
        "Roro59" => "roro59",
        "Pacothegreat" => "PacoTheGreat",
        "Akatsuki95" => "Akatsuki95",
        "Mattiashell" => "mattiashell",
        "Totokh" => "Totokh",
        "Shiinsekai" => "shiinsekai",
        "Basmakamel" => "basmakamel",
        "Aubss" => "AubSs",
        "Etienneelg" => "etienneelg",
        "Legrande" => "legrand_e",
        "Letellierbastien" => "LetellierBastien",
        "Lie92" => "lie92",
        "Christaupher" => "christaupher",
        "Meshufr" => "meshu-fr",
        "Galtar95" => "Galtar95",
        "Sicount" => "sicount",
        "Fullife" => "Fullife",
        "Clementrk" => "clementrk",
        "Aznote" => "aznote",
        "Santost" => "santos-t",
        "Azndarky" => "azndarky",
        "Lebestsigl" => "LebestSIGL",
        "Phloria" => "Phloria",
        "Yadavac" => "Yadavac",
        "Zimpow" => "zimpow"
    );


    public function isOpponentAFullBatard(string $name)
    {
        if (in_array($name, $this->list_player)) {
            $github_name = $this->list_player[$name];
            $batard = false;
            $url = "https://github.com/{$github_name}/fluffy-waddle/blob/master/src/Game/PlayerIA/{$github_name}Player.php";
            $response = file_get_contents($url);
            if (strpos($response, 'Mattiashell') !== false and !in_array($name, $this->T9_gang)) {
                $batard = true;
            }
            if (strpos($response, 'return parent::friendChoice') == false)
                $batard = true;
            return $batard;
        }
    }

    public function getChoice()
    {
        $max_turn = 10;
        $round_number = $this->result->getNbRound();
        $opponent_name = $this->result->getStatsFor($this->opponentSide)['name'];

        //Coup de traitre tour 9
        if ($round_number === 9) {
            if (in_array($opponent_name, $this->T9_gang))
                return parent::friendChoice();
            return parent::foeChoice();
        }

        //Pas de negociation avec les lâches
        if ($this->isOpponentAFullBatard($opponent_name))
            return parent::foeChoice();

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