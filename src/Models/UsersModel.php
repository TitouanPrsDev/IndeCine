<?php

namespace App\Models;

class UsersModel extends Model {
    protected $userFirstName;
    protected $userLastName;
    protected $userEmail;
    protected $userPhoneNumber;
    protected $userBirthDate;
    protected $userPassword;
    protected $userProfilePicture;
    protected $userRole;

    public function __construct () {
        $this -> table = 'users';
    }

    public function setFirstName($firstName) {
        $this -> userFirstName = $firstName;
        return $this;
    }

    public function setLastName($lastName) {
        $this -> userLastName = $lastName;
        return $this;
    }

    public function setEmail($email) {
        $this -> userEmail = $email;
        return $this;
    }

    public function setPhoneNumber($phoneNumber) {
        $this -> userPhoneNumber = $phoneNumber;
        return $this;
    }

    public function setBirthDate($birthDate) {
        $this -> userBirthDate = $birthDate;
        return $this;
    }

    public function setPassword($password) {
        $this -> userPassword = $password;
        return $this;
    }

    public function setProfilePicture($profilePicture) {
        $this -> userProfilePicture = $profilePicture;
        return $this;
    }

    public function setRole($role) {
        $this -> userRole = $role;
        return $this;
    }
}