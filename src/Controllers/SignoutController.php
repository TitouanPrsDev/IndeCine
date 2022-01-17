<?php

namespace App\Controllers;

class SignoutController extends Controller {
    public function index () {
        $pageData = array(
            'title' => "Déconnexion",
            'description' => "Déconnexion"
        );

        if (!isset($_SESSION['user'])) header('Location: /');

        else {
            unset($_SESSION['user']);
            header('Location: /');
        }
    }
}