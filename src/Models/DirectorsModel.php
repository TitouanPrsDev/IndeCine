<?php

namespace App\Models;

class DirectorsModel extends Model {
    protected $directorName;
    protected $directorPicture;

    public function __construct() {
        $this -> table = 'directors';
    }

    public function setName($name) {
        $this -> directorName = $name;
        return $this;
    }

    public function setPicture($picture) {
        $this -> directorPicture = $picture;
        return $this;
    }
}