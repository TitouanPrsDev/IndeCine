<?php

namespace App\Controllers;

use App\Models\UsersModel;

class SignupController extends Controller {
    public function index () {
        $messages = json_decode(file_get_contents('src/Core/messages.json'), true);

        $pageData = array(
            'title' => "Inscription",
            'description' => "Inscription"
        );

        if (isset($_SESSION['user'])) header('Location: /dashboard');

        $fieldsErrors = array();
        $tempFields = array();

        if (isset($_POST['submit'])) {
            $usersModel = new UsersModel();

            if (empty($_POST['first-name']) || !preg_match('/^[a-zA-Z]{1,30}$/', $_POST['first-name'])) $fieldsErrors['first-name'] = "Prénom invalide";
            else $tempFields['first-name'] = $_POST['first-name'];

            if (empty($_POST['last-name']) || !preg_match('/^[a-zA-Z]{1,30}$/', $_POST['last-name'])) $fieldsErrors['last-name'] = "Nom invalide";
            else $tempFields['last-name'] = $_POST['last-name'];

            if (empty($_POST['email'])) $fieldsErrors['email'] = "Adresse email invalide";
            else {
                $emailRes = $usersModel -> read(
                    criteria: [ 'userEmail =' => $_POST['email'] ],
                    fetch: 'fetch'
                );

                if ($emailRes) $fieldsErrors['email'] = "Adresse email déjà utilisée";
                else $tempFields['email'] = $_POST['email'];
            }

            if (empty($_POST['password']) || !preg_match('/^[a-zA-Z0-9_\-&*\/]{8,}$/', $_POST['password'])) $fieldsErrors['password'] = "Mot de passe invalide";

            if (empty($fieldsErrors)) {
                $firstName = $_POST['first-name'];
                $lastName = $_POST['last-name'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

                $usersModel -> setFirstName($firstName)
                    -> setLastName($lastName)
                    -> setEmail($email)
                    -> setPassword($password);

                $usersModel -> create();
                $_SESSION['message']['success'] = $messages['messages']['success']['account']['signup'];
                header('Location: /signin');
            }
        }

        $this -> render('signup', compact('pageData', 'fieldsErrors', 'tempFields'), 'form');
    }
}