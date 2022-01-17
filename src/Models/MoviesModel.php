<?php

namespace App\Models;

class MoviesModel extends Model {
    protected $table;

    protected $movieTitle;
    protected $moviePoster;
    protected $movieReleaseDate;
    protected $director_id;
    protected $movieSynopsis;
    protected $movieDuration;
    protected $movieFirstScreeningDate;
    protected $movieLastScreeningDate;
    protected $movieClassification;

    public function __construct () {
        $this -> table = 'movies';
    }

    public function setTitle($title) {
        $this -> movieTitle = $title;
        return $this;
    }

    public function setPoster($poster) {
        $this -> moviePoster = $poster;
        return $this;
    }

    public function setReleaseDate($releaseDate) {
        $this -> movieReleaseDate = $releaseDate;
        return $this;
    }

    public function setDirector_id($director_id) {
        $this -> director_id = $director_id;
        return $this;
    }

    public function setSynopsis($synopsis) {
        $this -> movieSynopsis = $synopsis;
        return $this;
    }

    public function setDuration($duration) {
        $this -> movieDuration = $duration;
        return $this;
    }

    public function setFirstScreeningDate($firstScreeningDate) {
        $this -> movieFirstScreeningDate = $firstScreeningDate;
        return $this;
    }

    public function setLastScreeningDate($lastScreeningDate) {
        $this -> movieLastScreeningDate = $lastScreeningDate;
        return $this;
    }

    public function setClassification($classification) {
        $this -> movieClassification = $classification;
        return $this;
    }
}