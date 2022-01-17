<?php

namespace App\Controllers;

use App\Models\UsersModel;

class SigninController extends Controller {
    public function index () {
        $messages = json_decode(file_get_contents('src/Core/messages.json'), true);

        $pageData = array(
            'title' => "Connexion",
            'description' => "Connexion"
        );

        if (isset($_SESSION['user'])) header('Location: /dashboard');

        $fieldsErrors = array();
        $tempFields = array();

        if (isset($_POST['submit'])) {
            $usersModel = new UsersModel();

            if (empty($_POST['email'])) $fieldsErrors['email'] = "Adresse email invalide";
            else {
                $password = strip_tags($_POST['password']);

                $res = $usersModel -> read(
                    criteria: [ 'userEmail =' => $_POST['email'] ],
                    fetch: 'fetch'
                );

                if (password_verify($password, $res -> userPassword)) {
                    $_SESSION['user'] = $res;
                    $_SESSION['message']['success'] = $messages['messages']['success']['account']['signin'];
                    header('Location: /dashboard');

                } else {
                    $tempFields['email'] = $_POST['email'];
                    $_SESSION['message']['error'] = $messages['messages']['error']['signin']['incorrect-email-password'];
                }
            }
        }

        $this -> render('signin', compact('pageData', 'fieldsErrors', 'tempFields'), 'form');
    }
}