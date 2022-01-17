<?php

namespace App\Controllers;

use App\Models\MoviesModel;
use App\Models\PricingsModel;
use App\Models\ReservationsModel;
use App\Models\ScreeningsModel;
use App\Models\SeatsModel;

class MoviesController extends Controller {
    public function index (array $params = null) {
        $moviesModel = new MoviesModel();

        date_default_timezone_set('Europe/Paris');
        $date = date('Y-m-d');

        $pageData = [
            'title' => "Films",
            'description' => "Films"
        ];

        $movies = $moviesModel -> read(
            criteria: [
                'movieFirstScreeningDate <=' => $date,
                'movieLastScreeningDate >=' => $date
            ],
            join: [
                'table' => 'M',
                'tables' => [
                    [
                        'table1' => [ 'movies', 'M', 'director_id' ],
                        'table2' => [ 'directors', 'D', 'directorId' ]
                    ]
                ]
            ],
            fetch: 'fetchAll'
        );

        if (isset($_POST['book'])) header('Location: /movies/' . $_POST['book']);

        $this -> render('movies/index', compact('pageData', 'movies'));
    }

    public function movie (array $params = null) {
        $screeningsModel = new ScreeningsModel();
        $seatsModel = new SeatsModel();
        $pricingsModel = new PricingsModel();
        $moviesModel = new MoviesModel();
        $reservationsModel = new ReservationsModel();

        if (is_numeric($params[0])) {
            $pageData = [
                'title' => "Film",
                'description' => "Film"
            ];

            $movie = $moviesModel -> read(
                criteria: [ 'movieId =' => $params[0] ],
                join: [
                    'table' => 'M',
                    'tables' => [
                        [
                            'table1' => [ 'movies', 'M', 'director_id' ],
                            'table2' => [ 'directors', 'D', 'directorId' ]
                        ]
                    ]
                ],
                fetch: 'fetch'
            );

            $screenings = $screeningsModel -> read(
                criteria: [ 'movie_id =' => $params[0] ],
                fetch: 'fetchAll'
            );

            $dates = array();
            $fullDates = array();
            foreach ($screenings as $screening) {
                $date = substr($screening -> screeningDate, 5, 6);
                $fullDate = $screening -> screeningDate;
                !in_array($date, $dates) && ($dates[] = $date);
                !in_array($fullDate, $fullDates) && ($fullDates[] = $fullDate);
            }

            $screeningsList = array();
            for ($i = 0; $i < count($fullDates); $i++) {
                $screeningsList[$dates[$i]] = $screeningsModel -> read(
                    criteria: [ 'screeningDate =' =>  $fullDates[$i] ],
                    fetch: 'fetchAll'
                );
            }

            if (isset($_POST['screening'])) {
                $_SESSION['screening'] = $_POST['screening'];
                header('Location: /movies/book/' . $_POST['screening']);
            }

            $this -> render('movies/movie', compact('pageData', 'movie', 'dates', 'screeningsList'));

        } else if ($params[0] === 'book') {
            $pageData = [
                'title' => "Réserver",
                'description' => "Réserver"
            ];

            if (!isset($_SESSION['user'])) header('Location: /signin');

            $screening = $screeningsModel -> read(
                criteria: [ 'screeningId =' => $params[1] ],
                join: [
                    'table' => 'S',
                    'tables' => [
                        [
                            'table1' => [ 'screenings', 'S', 'movie_id' ],
                            'table2' => [ 'movies', 'M', 'movieId' ]
                        ], [
                            'table1' => [ 'screenings', 'S', 'movieRoom_id' ],
                            'table2' => [ 'moviesRooms', 'MR', 'movieRoomId' ]
                        ]
                    ]
                ],
                fetch: 'fetch'
            );

            $seats = $seatsModel -> read(
                criteria: [ 'movieRoom_id =' => $screening -> movieRoomId ],
                join: [
                    'table' => 'S',
                    'tables' => [
                        [
                            'table1' => [ 'seats', 'S', 'movieRoom_id' ],
                            'table2' => [ 'moviesRooms', 'MR', 'movieRoomId' ]
                        ]
                    ]
                ],
                fetch: 'fetchAll'
            );

            $pricings = $pricingsModel -> read(
                fetch: 'fetchAll'
            );

            $fieldsErrors = array();
            $tempFields = array();

            if (isset($_POST['submit'])) {

                if (!empty($_POST['screening-date'])) $tempFields['screening-date'] = $_POST['screening-date'];

                if (!empty($_POST['screening-time'])) $tempFields['screening-time'] = $_POST['screening-time'];

                if (!empty($_POST['movie-room'])) $tempFields['movie-room'] = $_POST['movie-room'];

                if (empty($_POST['seat'])) $fieldsErrors['seat'] = "Veuillez sélectionner une salle";
                else $tempFields['seat'] = $_POST['seat'];

                if (empty($_POST['pricing'])) $fieldsErrors['pricing'] = "Veuillez sélectionner un tarif";
                else $tempFields['pricing'] = $_POST['pricing'];

                if (empty($fieldsErrors)) {
                    $seat = $_POST['seat'];
                    $pricing = $_POST['pricing'];

                    $reservationsModel -> setUser_id($_SESSION['user'] -> userId)
                        -> setScreening_id($_SESSION['screening'])
                        -> setSeat_id($seat)
                        -> setPricing_id($pricing);

                    $reservationsModel -> create();
                    $_SESSION['message']['success'] = "La réservation a bien été validée";
                    header('Location: /dashboard/reservations');
                }
            }

            $this -> render('movies/book', compact(
                'pageData', 'screening', 'seats', 'pricings'
            ), 'form');
        }
    }
}