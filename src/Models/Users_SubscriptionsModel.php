<?php

namespace App\Models;

class Users_SubscriptionsModel extends Model {
    protected $user_id;
    protected $subscription_id;
    protected $remainingPlaces;
    protected $points;

    public function __construct() {
        $this -> table = 'users_subscriptions';
    }

    public function setUser_id($user_id) {
        $this -> user_id = $user_id;
        return $this;
    }

    public function setSubscription_id($subscription_id) {
        $this -> subscription_id = $subscription_id;
        return $this;
    }

    public function setRemainingPlaces($remainingPlaces) {
        $this -> remainingPlaces = $remainingPlaces;
        return $this;
    }

    public function setPoints($points) {
        $this -> points = $points;
        return $this;
    }
}