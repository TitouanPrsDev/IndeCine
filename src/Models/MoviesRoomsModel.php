<?php

namespace App\Models;

class MoviesRoomsModel extends Model {
    protected $movieRoomName;
    protected $movieRoomDescription;

    public function __construct() {
        $this -> table = 'moviesRooms';
    }

    public function setName($name) {
        $this -> movieRoomName = $name;
        return $this;
    }

    public function setDescription($description) {
        $this -> movieRoomDescription = $description;
        return $this;
    }
}