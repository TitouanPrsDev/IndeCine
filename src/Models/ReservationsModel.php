<?php

namespace App\Models;

class ReservationsModel extends Model {
    protected $user_id;
    protected $screening_id;
    protected $seat_id;
    protected $pricing_id;

    public function __construct () {
        $this -> table = 'reservations';
    }

    public function setUser_id($user_id) {
        $this -> user_id = $user_id;
        return $this;
    }

    public function setScreening_id($screening_id) {
        $this -> screening_id = $screening_id;
        return $this;
    }

    public function setSeat_id($seat_id) {
        $this -> seat_id = $seat_id;
        return $this;
    }

    public function setPricing_id($pricing_id) {
        $this -> pricing_id = $pricing_id;
        return $this;
    }
}