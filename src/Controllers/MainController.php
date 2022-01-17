<?php

namespace App\Controllers;

class MainController extends Controller {
    public function index () {
        $pageData = array (
            'title' => 'Accueil',
            'description' => 'Accueil'
        );

        $this -> render('index', compact('pageData'));
    }
}