<?php

namespace App\Models;

class ScreeningsModel extends Model {
    protected $movie_id;
    protected $movieRoom_id;
    protected $screeningDate;
    protected $screeningTime;

    public function __construct () {
        $this -> table = 'screenings';
    }

    public function setMovie_id($movie_id) {
        $this -> movie_id = $movie_id;
        return $this;
    }

    public function setMovieRoom_id($movieRoom_id) {
        $this -> movieRoom_id = $movieRoom_id;
        return $this;
    }

    public function setDate($date) {
        $this -> screeningDate = $date;
        return $this;
    }

    public function setTime($time) {
        $this -> screeningTime = $time;
        return $this;
    }
}