<?php

namespace App\Core;

use PDO;
use PDOException;

class Database extends PDO {
    private static $instance;

    private const SERVER = 'webdev';
    private const USER = 'root';
    private const PASSWORD = '';
    private const DATABASE = 'indecine';

    private function __construct () {
        $_dsn = 'mysql:dbname=' . self::DATABASE . ';host=' . self::SERVER;

        try {
            parent::__construct($_dsn, self::USER, self::PASSWORD);

            $this -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this -> exec('SET NAMES utf8');

        } catch (PDOException $e) {
            echo 'Erreur : ' . $e -> getMessage() . '<br>';
            echo 'N° : ' . $e -> getCode();
        }
    }

    public static function getInstance () : self {
        if (self::$instance === null) self::$instance = new self();

        return self::$instance;
    }
}