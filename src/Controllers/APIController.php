<?php

namespace App\Controllers;

use App\Models\{MoviesModel, ReservationsModel, UsersModel};

class APIController extends Controller {
    public function index () {
        $pageData = [
            'title' => "API",
            'description' => "API"
        ];

        $this -> render('api', compact('pageData'), 'default');
    }

    public function movies(array $params = null) {
        header('Content-Type: application/json');
        $moviesModel = new MoviesModel();
        $json = null;

        if (!$params) $json = $moviesModel -> read(
            columns: [ 'movieId', 'movieTitle', 'movieReleaseDate', 'director_id', 'movieSynopsis', 'movieDuration', 'movieFirstScreeningDate', 'movieClassification' ],
            fetch: 'fetchAll'
        );
        else if (is_numeric($params[0])) $json = $moviesModel -> read(
            columns: [ 'movieId', 'movieTitle', 'movieReleaseDate', 'director_id', 'movieSynopsis', 'movieDuration', 'movieFirstScreeningDate', 'movieClassification' ],
            criteria: [ 'movieId =' => $params[0] ],
            fetch: 'fetch'
        );
        else if ($params[0] === 'count') $json = $moviesModel -> read(
            count: 'count',
            fetch: 'fetch'
        );

        echo json_encode([ 'data' => $json] );
    }

    public function users (array $params = null) {
        header('Content-Type: application/json');
        $usersModel = new UsersModel();
        $json = null;

        if (!$params) $json = $usersModel -> read(
            columns: [ 'userId', 'firstName', 'lastName', 'email', 'role' ],
            fetch: 'fetchAll'
        );
        else if (is_numeric($params[0])) $json = $usersModel -> read(
            columns: [ 'userId', 'firstName', 'lastName', 'email', 'role' ],
            criteria: [ 'id =' => $params[0] ],
            fetch: 'fetch'
        );
        else if ($params[0] === 'count') $json = $usersModel -> read(
            count: 'count',
            fetch: 'fetch'
        );

        echo json_encode([ 'data' => $json ]);
    }

    public function reservations (array $params = null) {
        header('Content-Type: application/json');
        $reservationsModel = new ReservationsModel();
        $json = null;

        if (!$params) $json = $reservationsModel -> read(
            join: [
                'table' => 'R',
                'tables' => [
                    [
                        'table1' => [ 'reservations', 'R', 'pricing_id'],
                        'table2' => [ 'pricings', 'P', 'pricingId' ]
                    ], [
                        'table1' => [ 'reservations', 'R', 'seat_id' ],
                        'table2' => [ 'seats', 'S', 'seatId' ]
                    ]
                ]
            ],
            fetch: 'fetchAll'
        );
        else {
            $dates = $reservationsModel -> read(
                fetch: 'fetchAll'
            );
            $param = substr($params[0], 1);

            if ($params[0][0] === 'y') {
                foreach ($dates as $date) {
                    if (substr($date -> date, 0, 4) === $param) $json[] = $date;
                }

            } else if ($params[0][0] === 'm') {
                foreach ($dates as $date) {
                    if (substr($date -> date, 5, 2) === $param) $json[] = $date;
                }

            } else if ($params[0][0] === 'd') {
                foreach ($dates as $date) {
                    if (substr($date -> date, 8, 2) === $param) $json[] = $date;
                }

            } else {
                $json = false;
            }
        }

        echo json_encode([ 'data' => $json ]);
    }
}