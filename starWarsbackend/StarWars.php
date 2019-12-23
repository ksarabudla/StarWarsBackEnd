<?php
/***
 * Class StarWars
 */

class StarWars {
    /**
     * @var \MongoDB\Driver\Manager
     */
    private $mng;
    /**
     * @var \MongoDB\Driver\Query
     */
    private $query;
    /**
     * @var array
     */
    public $filmChars = [];
    /**
     * @var array
     */
    public $filmSpecies = [];
    /**
     * @var array
     */
    public $starShips = [];
    /**
     * @var array
     */
    public $vehicles = [];

    /**
     * @var
     */
    public $firstAnswer;

    /**
     * @var
     */
    public $secondAnswer;

    /**
     * @var
     */
    public $thirdAnswer;

    /**
     * @var
     */
    public $fourthAnswer;

    /**
     * @var array
     */
    public $peoplePlanets = [];

    /**
     * @var array
     */
    public $people = [];

    /**
     * @var
     */
    public $error;

    /***
     * StarWars constructor.
     */
    public function __construct()
    {
        $this->mng = new MongoDB\Driver\Manager("mongodb://candidate:PrototypeRocks123654@ds345028.mlab.com:45028/star-wars");
        $this->query = new MongoDB\Driver\Query([]);
        $this->loadStarWarsObjects();
    }

    /***
     * @param $collection
     * @return \MongoDB\Driver\Cursor
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function getCollection($collection){
        try {
            $rows = $this->mng->executeQuery($collection, $this->query);
        } catch (MongoDB\Driver\Exception\Exception $e){
            $this->throwException($e);
        }
        return $rows;
    }

    /**
     * @param $e
     */
    public function throwException($e){
        $this->error = "The API has experienced an error. Seems MongoDB Server is not reachable.";
    }

    /**
     * This Function is for loading all the objects which are related to the films
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function loadStarWarsObjects(){
        $maxchars = 0;
        $rows = $this->getCollection("star-wars.films");
        //Form the Relation Ship Details
        foreach ($rows as $row) {

            $cCount = count($row->characters);
            if ($maxchars < $cCount) {
                $maxchars = $cCount;
                $this->firstAnswer = $row->title .' ('.$maxchars.') ';
            }

            foreach($row->characters as $index => $character) {
                if (!isset($this->filmChars[$character])) {
                    $this->filmChars[$character] = 1;
                } else {
                    $this->filmChars[$character] += 1 ;
                }
            }
            arsort($this->filmChars);

            foreach($row->species as $index => $specie) {
                if (!isset($this->filmSpecies[$specie])) {
                    $this->filmSpecies[$specie] = 1;
                } else {
                    $this->filmSpecies[$specie] += 1 ;
                }
            }
            arsort($this->filmSpecies);

            foreach($row->starships as $index => $starship) {
                if (!isset($this->starShips[$starship])) {
                    $this->starShips[$starship] = 1;
                } else {
                    $this->starShips[$starship] += 1 ;
                }
            }
            arsort($this->starShips);

            foreach($row->vehicles as $index => $vehicle) {
                if (!isset($vehicles[$vehicle])) {
                    $this->vehicles[$vehicle] = 1;
                } else {
                    $this->vehicles[$vehicle] += 1 ;
                }
            }
            arsort($this->vehicles);
        }
    }

    /**
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function FindAllAnswers(){
        $this->findMostAppearedCharacter();
    }

    /**
     * This Function is find out the most Appeared Characters in the Films
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function findMostAppearedCharacter(){
        $peoples = $this->getCollection("star-wars.people");
        foreach ($peoples as $ppl) {
            $this->people[$ppl->id] = $ppl->name;
            $this->peoplePlanets[$ppl->homeworld][] = $ppl->id;
        }
        $maxcharCount = 0;
        foreach ($this->filmChars as $charID => $char){
            if ($maxcharCount == 0) {
                $maxcharCount = $char;
            }
            if (($maxcharCount-3) < $char) {
                $this->secondAnswer .= $this->people[$charID] .' ('.$char.') <br/>';
            }
        }
    }


}
