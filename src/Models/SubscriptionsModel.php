<?php

namespace App\Models;

class SubscriptionsModel extends Model {
    protected $subscriptionName;
    protected $subscriptionDescription;
    protected $subscriptionPricing;

    public function __construct() {
        $this -> table = 'subscriptions';
    }

    public function setName($name) {
        $this -> subscriptionName = $name;
        return $this;
    }

    public function setDescription($description) {
        $this -> subscriptionDescription = $description;
        return $this;
    }

    public function setPricing($pricing) {
        $this -> subscriptionPricing = $pricing;
        return $this;
    }
}