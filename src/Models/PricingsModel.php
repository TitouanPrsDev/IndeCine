<?php

namespace App\Models;

class PricingsModel extends Model {
    protected $pricingName;
    protected $pricingDescription;
    protected $pricing;

    public function __construct () {
        $this -> table = 'pricings';
    }

    public function setName($name) {
        $this -> pricingName = $name;
        return $this;
    }

    public function setDescription($description) {
        $this -> pricingDescription = $description;
        return $this;
    }

    public function setPricing($pricing) {
        $this -> pricing = $pricing;
        return $this;
    }
}