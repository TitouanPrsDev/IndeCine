<?php

namespace App\Controllers;

use App\Models\{MoviesModel,
    ScreeningsModel,
    ReservationsModel,
    MoviesRoomsModel,
    PricingsModel,
    SubscriptionsModel,
    Users_SubscriptionsModel,
    UsersModel,
    DirectorsModel,
    SeatsModel};

class DashboardController extends Controller {
    /* If no route method defined */
    public function index () {
        $usersModel = new UsersModel();
        $reservationsModel = new ReservationsModel();
        $moviesModel = new MoviesModel();
        $users_SubscriptionsModel = new Users_SubscriptionsModel();

        $totalUsers = $usersModel -> read(
            count: 'count',
            fetch: 'fetch'
        ) -> count;

        $totalReservations = $reservationsModel -> read(
            count: 'count',
            fetch: 'fetch'
        ) -> count;

        $totalMovies = $moviesModel -> read(
            count: 'count',
            fetch: 'fetch'
        ) -> count;

        $totalUsers_Subscriptions = $users_SubscriptionsModel -> read(
            count: 'count',
            fetch: 'fetch'
        ) -> count;

        $pageData = [
            'title' => "Tableau de bord",
            'description' => "Tableau de bord",
        ];

        if (!isset($_SESSION['user'])) header('Location: /');

        $this -> render('dashboard/index', compact(
            'pageData', 'totalUsers', 'totalReservations', 'totalMovies', 'totalUsers_Subscriptions'
        ), 'dashboard');
    }

    /* If route method = 'movies' */
    public function movies (array $params = null) {
        $moviesModel = new MoviesModel();
        $screeningsModel = new ScreeningsModel();
        $directorsModel = new DirectorsModel();

        date_default_timezone_set('Europe/Paris');
        $date = date('Y-m-d');

        $messages = json_decode(file_get_contents('src/Core/messages.json'), true);

        if (!isset($_SESSION['user'])) header('Location: /');

        /* Movie's index page */
        if (!$params) {
            /* Define basic page's data */
            $pageData = [
                'title' => "Films",
                'description' => "Films",
                'breadcrumb' => [ "Films" ]
            ];

            /* Count total movies */
            $totalMovies = $moviesModel -> read(
                count: 'count',
                fetch: 'fetch'
            ) -> count;

            /* Count movies currently screening */
            $moviesScreening = $moviesModel -> read(
                criteria: [
                    'movieFirstScreeningDate <=' => $date,
                    'movieLastScreeningDate >=' => $date
                ],
                count: 'count',
                fetch: 'fetch'
            ) -> count;

            /* Count movies waiting for screening */
            $moviesWaitingForScreening = $moviesModel -> read(
                criteria: [
                    'movieFirstScreeningDate >' => $date,
                    'movieLastScreeningDate >' => $date
                ],
                count: 'count',
                fetch: 'fetch'
            ) -> count;

            /* Count movies already screened */
            $moviesAlreadyScreened = $moviesModel -> read(
                criteria: [
                    'movieFirstScreeningDate <' => $date,
                    'movieLastScreeningDate <=' => $date

                ],
                count: 'count',
                fetch: 'fetch'
            ) -> count;

            /* Pagination system */
            $page = 1;

            if (!isset($_SESSION['nbLines'])) $_SESSION['nbLines'] = 5;

            $movies = null;
            if (!isset($_POST['page']) && !isset($_POST['show'])) {
                $movies = $moviesModel -> read(
                    join: [
                        'table' => 'M',
                        'tables' => [
                            [
                                'table1' => ['movies', 'M', 'director_id'],
                                'table2' => ['directors', 'D', 'directorId']
                            ]
                        ]
                    ],
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );

            } else if (isset($_POST['page'])) {
                $page = $_POST['page'];

                $movies = $moviesModel -> read(
                    join: [
                        'table' => 'M',
                        'tables' => [
                            [
                                'table1' => ['movies', 'M', 'director_id'],
                                'table2' => ['directors', 'D', 'directorId']
                            ]
                        ]
                    ],
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );

            } else if (isset($_POST['show'])) {
                $_SESSION['nbLines'] = intval($_POST['show']);

                $movies = $moviesModel -> read(
                    join: [
                        'table' => 'M',
                        'tables' => [
                            [
                                'table1' => ['movies', 'M', 'director_id'],
                                'table2' => ['directors', 'D', 'directorId']
                            ]
                        ]
                    ],
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );
            }

            $nbPages = $totalMovies / $_SESSION['nbLines'];

            /* Page's button */
            if (isset($_POST['add'])) header('Location: /dashboard/movies/add');
            if (isset($_POST['modify'])) header('Location: /dashboard/movies/modify/' . $_POST['modify']);
            if (isset($_POST['delete'])) header('Location: /dashboard/movies/delete/' . $_POST['delete']) ;

            /* Render page */
            $this -> render('dashboard/movies/index', compact(
                'pageData',
                'movies',
                'totalMovies',
                'moviesScreening',
                'moviesWaitingForScreening',
                'moviesAlreadyScreened',
                'nbPages'
            ), 'dashboard');

        /* If param is numeric => Details of a movie */
        } else if (is_numeric($params[0])) {
            $movie = $moviesModel -> read(
                criteria: [ 'movieId =' => $params[0] ],
                join: [
                    'table' => 'M',
                    'tables' => [
                        [
                            'table1' => [ 'movies', 'M', 'director_id' ],
                            'table2' => [ 'directors', 'D', 'directorId']
                        ]
                    ]
                ],
                fetch: 'fetch'
            );

            /* Basic page's data */
            $pageData = [
                'title' => $movie -> movieTitle,
                'description' => "Film",
                'breadcrumb' => [
                    "Films" => '/dashboard/movies',
                    $movie -> movieTitle
                ]
            ];

            /* Render page */
            $this -> render('dashboard/movies/movie', compact(
                'pageData', 'movie'
            ), 'dashboard');

        /* Add movie page */
        } else if ($params[0] === 'add') {
            /* Basic page's data */
            $pageData = [
                'title' => "Ajouter un film",
                'description' => "Ajouter un film",
                'breadcrumb' => [
                    "Films" => '/dashboard/movies',
                    "Ajouter un film"
                ]
            ];

            /* Fetch all directors */
            $directors = $directorsModel -> read(
                columns: [ 'directorId', 'directorName' ],
                fetch: 'fetchAll'
            );

            /* Submit form */
            $fieldsErrors = array();
            $tempFields = array();

            if (isset($_POST['submit'])) {
                if (empty($_POST['title'])) $fieldsErrors['title'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['title'];
                else $tempFields['title'] = $_POST['title'];

                if (empty($_POST['duration']) || !is_numeric($_POST['duration']) || strlen($_POST['duration']) > 3) $fieldsErrors['duration'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['duration'];
                else $tempFields['duration'] = $_POST['duration'];

                if (empty($_POST['director'])) $fieldsErrors['director'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['director'];

                if (empty($_POST['release-date']) || strlen($_POST['release-date']) !== 10) $fieldsErrors['release-date'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['release-date'];
                else $tempFields['release-date'] = $_POST['release-date'];

                if (empty($_POST['synopsis'])) $fieldsErrors['synopsis'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['synopsis'];
                else $tempFields['synopsis'] = $_POST['synopsis'];

                if (empty($_POST['poster']) || strlen($_POST['poster']) > 255) $fieldsErrors['poster'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['poster'];
                else $tempFields['poster'] = $_POST['poster'];

                if (empty($_POST['classification'])) $fieldsErrors['classification'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['classification'];

                if (empty($_POST['first-screening-date']) || strlen($_POST['first-screening-date']) !== 10) $fieldsErrors['first-screening-date'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['first-screening-date'];
                else $tempFields['first-screening-date'] = $_POST['first-screening-date'];

                if (empty($_POST['last-screening-date']) || strlen($_POST['last-screening-date']) !== 10) $fieldsErrors['last-screening-date'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['last-screening-date'];
                else $tempFields['last-screening-date'] = $_POST['last-screening-date'];

                if (empty($fieldsErrors)) {
                    $title = strip_tags($_POST['title']);
                    $duration = strip_tags($_POST['duration']);
                    $director = $_POST['director'];
                    $releaseDate = strip_tags($_POST['release-date']);
                    $synopsis = strip_tags($_POST['synopsis']);
                    $poster = strip_tags($_POST['poster']);
                    $classification = $_POST['classification'];
                    $firstScreeningDate = strip_tags($_POST['first-screening-date']);
                    $lastScreeningDate = strip_tags($_POST['last-screening-date']);

                    $moviesModel -> setTitle($title)
                        -> setPoster($poster)
                        -> setReleaseDate(format_date($releaseDate, 'sql'))
                        -> setDirector_id($director)
                        -> setSynopsis($synopsis)
                        -> setDuration($duration)
                        -> setFirstScreeningDate(format_date($firstScreeningDate, 'sql'))
                        -> setLastScreeningDate(format_date($lastScreeningDate, 'sql'))
                        -> setClassification($classification);

                    $moviesModel -> create();
                    $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['add']['movies'];
                    header('Location: /dashboard/movies');
                }
            }

            /* Render page */
            $this -> render('dashboard/movies/add', compact(
                'pageData', 'directors', 'fieldsErrors', 'tempFields'
            ), 'dashboard');

        /* Modify movie page */
        } else if ($params[0] === 'modify') {
            if (!$params[1]) header('Location: /dashboard/movies');
            else {

                /* Basic page's data */
                $pageData = [
                    'title' => "Modifier un film",
                    'description' => "Modifier un film",
                    'breadcrumb' => [
                        "Films" => '/dashboard/movies',
                        "Modifier un film"
                    ]
                ];

                /* Get current movie */
                $movie = $moviesModel -> read(
                    criteria: [ 'movieId =' => $params[1] ],
                    fetch: 'fetch'
                );

                /* Fetch all directors */
                $directors = $directorsModel -> read(
                    columns: [ 'directorId', 'directorName' ],
                    fetch: 'fetchAll'
                );

                /* Submit form */
                $fieldsErrors = array();

                if (isset($_POST['submit'])) {
                    if (empty($_POST['title'])) $fieldsErrors['title'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['title'];
                    else $moviesModel -> setTitle(strip_tags($_POST['title']));

                    if (empty($_POST['duration']) || !is_numeric($_POST['duration']) || strlen($_POST['duration']) > 3) $fieldsErrors['duration'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['duration'];
                    else $moviesModel -> setDuration(strip_tags($_POST['duration']));

                    if (empty($_POST['director'])) $fieldsErrors['director'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['director'];
                    else $moviesModel -> setDirector_id($_POST['director']);

                    if (empty($_POST['release-date']) || strlen($_POST['release-date']) !== 10) $fieldsErrors['release-date'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['release-date'];
                    else $moviesModel -> setReleaseDate(format_date(strip_tags($_POST['release-date']), 'sql'));

                    if (empty($_POST['synopsis'])) $fieldsErrors['synopsis'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['synopsis'];
                    else $moviesModel -> setSynopsis(strip_tags($_POST['synopsis']));

                    if (empty($_POST['poster']) || strlen($_POST['poster']) > 255) $fieldsErrors['poster'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['poster'];
                    else $moviesModel -> setPoster(strip_tags($_POST['poster']));

                    if (empty($_POST['classification'])) $fieldsErrors['classification'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['classification'];
                    else $moviesModel -> setClassification($_POST['classification']);

                    if (empty($_POST['first-screening-date']) || strlen($_POST['first-screening-date']) !== 10) $fieldsErrors['first-screening-date'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['first-screening-date'];
                    else $moviesModel -> setFirstScreeningDate(format_date(strip_tags($_POST['first-screening-date']), 'sql'));

                    if (empty($_POST['last-screening-date']) || strlen($_POST['last-screening-date']) !== 10) $fieldsErrors['last-screening-date'] = $messages['messages']['error']['dashboard']['movies']['add-modify']['last-screening-date'];
                    else $moviesModel -> setLastScreeningDate(format_date(strip_tags($_POST['last-screening-date']), 'sql'));

                    if (empty($fieldsErrors)) {
                        $moviesModel -> update([ 'movieId' => $params[1] ]);
                        $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['modify']['movies'];
                        header('Location: /dashboard/movies');
                    }
                }

                /* Render page */
                $this -> render('dashboard/movies/modify', compact(
                    'pageData',
                    'movie',
                    'directors',
                    'fieldsErrors'
                ), 'dashboard');
            }

        /* Delete movie page */
        } else if ($params[0] === 'delete') {
            $pageData = [
                'title' => "Supprimer un film",
                'description' => "Supprimer un film",
                'breadcrumb' => [
                    "Films" => '/dashboard/movies',
                    "Supprimer un film"
                ]
            ];

            /* Get current movie */
            $movie = $moviesModel -> read(
                criteria: [ 'movieId =' => $params[1] ],
                join: [
                    'table' => 'M',
                    'tables' => [
                        [
                            'table1' => [ 'movies', 'M', 'director_id' ],
                            'table2' => [ 'directors', 'D', 'directorId']
                        ]
                    ]
                ],
                fetch: 'fetch'
            );

            /* Delete movie logic */
            $idScreeningsExist = $screeningsModel -> read(
                criteria: [ 'movie_id = ' => $params[1] ],
                fetch: 'fetch'
            );

            if (isset($_POST['submit'])) {
                if ($idScreeningsExist) $_SESSION['message']['error'] = $messages['messages']['error']['dashboard']['movies']['delete'];
                else {
                    $moviesModel -> delete($params[1], 'movieId');
                    header('Location: /dashboard/movies');
                }
            }

            /* Render page */
            $this -> render('dashboard/movies/delete', compact(
                'pageData', 'movie'
            ), 'dashboard');

        } else header('Location: /dashboard/movies');
    }

    /* If route method = 'screenings' */
    public function screenings (array $params = null) {
        $screeningsModel = new ScreeningsModel();
        $reservationsModel = new ReservationsModel();
        $moviesModel = new MoviesModel();
        $moviesRoomsModel = new MoviesRoomsModel();

        date_default_timezone_set('Europe/Paris');
        $date = date('Y-m-d');

        $messages = json_decode(file_get_contents('src/Core/messages.json'), true);

        if (!isset($_SESSION['user'])) header('Location: /');

        /* Screening's index page */
        if (!$params) {
            $pageData = [
                'title' => "Séances",
                'description' => "Séances",
                'breadcrumb' => [ "Séances" ]
            ];

            /* Count total screenings */
            $totalScreenings = $screeningsModel -> read(
                count: 'count',
                fetch: 'fetch'
            ) -> count;

            /* Pagination system */
            $page = 1;
            if (!isset($_SESSION['nbLines'])) $_SESSION['nbLines'] = 5;

            $screenings = null;
            if (!isset($_POST['page']) && !isset($_POST['show'])) {

                $screenings = $screeningsModel -> read(
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
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );

            } else if (isset($_POST['page'])) {
                $page = intval($_POST['page']);

                $screenings = $screeningsModel -> read(
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
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );

            } else if (isset($_POST['show'])) {
                $_SESSION['nbLines'] = intval($_POST['show']);

                $screenings = $screeningsModel -> read(
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
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );
            }

            $nbPages = $totalScreenings / $_SESSION['nbLines'];

            /* ACTIONS */
            if (isset($_POST['add'])) header('Location: /dashboard/screenings/add');
            if (isset($_POST['modify'])) header('Location: /dashboard/screenings/modify/'.$_POST['modify']);
            if (isset($_POST['delete'])) header('Location: /dashboard/screenings/delete/'.$_POST['delete']);

            /* Render page */
            $this -> render('dashboard/screenings/index', compact(
                'pageData',
                'screenings',
                'totalScreenings',
                'nbPages'
            ), 'dashboard');

        /* Screening's details */
        } else if (is_numeric($params[0])) {
            /* Get current screening */
            $screening = $screeningsModel -> read(
                criteria: [ 'screeningId =' => $params[0] ],
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

            /* Basic's page data */
            $pageData = [
                'title' => $screening -> movieTitle . " - " . format_date($screening -> screeningDate, 'display-slash') . " en " . $screening -> movieRoomName,
                'description' => "Séance",
                'breadcrumb' => [
                    "Séances" => '/dashboard/screenings',
                    $screening -> movieTitle . " - " . format_date($screening -> screeningDate, 'display-slash') . " en " . $screening -> movieRoomName
                ]
            ];

            /* Render page */
            $this -> render('dashboard/screenings/screening', compact(
                'pageData', 'screening'
            ), 'dashboard');

        /* Add screening page */
        } else if ($params[0] === 'add') {
            $pageData = [
                'title' => "Ajouter une séance",
                'description' => "Ajouter une séance",
                'breadcrumb' => [
                    "Séances" => '/dashboard/screenings',
                    "Ajouter une séance"
                ]
            ];

            /* Fetch all movies */
            $movies = $moviesModel -> read(
                columns: [ 'movieId', 'movieTitle' ],
                criteria: [
                    'movieFirstScreeningDate <=' => $date,
                    'movieLastScreeningDate >=' => $date
                ],
                fetch: 'fetchAll'
            );

            /* Fetch all movies rooms */
            $moviesRooms = $moviesRoomsModel -> read(
                columns: [ 'movieRoomId', 'movieRoomName' ],
                fetch: 'fetchAll'
            );

            /* Submit logic */
            $fieldsErrors = array();
            $tempFields = array();

            if (isset($_POST['submit'])) {
                if (empty($_POST['movie'])) $fieldsErrors['movie'] = $messages['messages']['error']['dashboard']['screenings']['add-modify']['movie'];

                if (empty($_POST['movie-room'])) $fieldsErrors['movie-room'] = $messages['messages']['error']['dashboard']['screenings']['add-modify']['movie-room'];

                if (empty($_POST['date']) || strlen($_POST['date']) !== 10) $fieldsErrors['date'] = $messages['messages']['error']['dashboard']['screenings']['add-modify']['date'];
                else $tempFields['date'] = $_POST['date'];

                if (empty($_POST['time'])) $fieldsErrors['time'] = $messages['messages']['error']['dashboard']['screenings']['add-modify']['time'];
                else $tempFields['time'] = $_POST['time'];

                if (empty($fieldsErrors)) {
                    $movie = $_POST['movie'];
                    $movieRoom = $_POST['movie-room'];
                    $date = strip_tags($_POST['date']);
                    $time = strip_tags($_POST['time']);

                    $screeningsModel -> setMovie_id($movie)
                        -> setMovieRoom_id($movieRoom)
                        -> setDate(format_date($date, 'sql'))
                        -> setTime($time);

                    $screeningsModel -> create();
                    $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['add']['screenings'];
                    header('Location: /dashboard/screenings');
                }
            }

            /* Render page */
            $this -> render('dashboard/screenings/add', compact(
                'pageData',
                'movies',
                'moviesRooms',
                'fieldsErrors',
                'tempFields'
            ), 'dashboard');

        /* Modify screening page */
        } else if ($params[0] === 'modify') {
            $pageData = [
                'title' => "Modifier une séance",
                'description' => "Modifier une séance",
                'breadcrumb' => [
                    "Séances" => '/dashboard/screenings',
                    "Modifier une séance"
                ]
            ];

            /* Get current screening */
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

            /* Fetch all movies */
            $movies = $moviesModel -> read(
                fetch: 'fetchAll'
            );

            /* Fetch all movies rooms */
            $moviesRooms = $moviesRoomsModel -> read(
                fetch: 'fetchAll'
            );

            /* Submit logic */
            $fieldsErrors = array();

            if (isset($_POST['submit'])) {
                if (empty($_POST['movie'])) $fieldsErrors['movie'] = $messages['messages']['error']['dashboard']['screenings']['add-modify']['movie'];
                else $screeningsModel -> setMovie_id($_POST['movie']);

                if (empty($_POST['movie-room'])) $fieldsErrors['movie-room'] = $messages['messages']['error']['dashboard']['screenings']['add-modify']['movie-room'];
                else $screeningsModel -> setMovieRoom_id($_POST['movie-room']);

                if (empty($_POST['date'])) $fieldsErrors['date'] = $messages['messages']['error']['dashboard']['screenings']['add-modify']['date'];
                else $screeningsModel -> setDate(format_date(strip_tags($_POST['date']), 'sql'));

                if (empty($_POST['time'])) $fieldsErrors['time'] = $messages['messages']['error']['dashboard']['screenings']['add-modify']['time'];
                else $screeningsModel -> setTime(strip_tags($_POST['time']));

                if (empty($fieldsErrors)) {
                    $screeningsModel -> update([ 'screeningId' => $params[1] ]);
                    $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['modify']['screenings'];
                    header('Location: /dashboard/screenings');
                }
            }

            /* Render page */
            $this -> render('dashboard/screenings/modify', compact(
                'pageData', 'screening', 'movies', 'moviesRooms', 'fieldsErrors'
            ), 'dashboard');

        /* Delete screening page */
        } else if ($params[0] === 'delete') {
            $pageData = [
                'title' => "Supprimer une séance",
                'description' => "Supprimer une séance",
                'breadcrumb' => [
                    "Séances" => '/dashboard/screenings',
                    "Supprimer une séance"
                ]
            ];

            /* Get current screening */
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

            /* Delete screening logic */
            $idReservationsExist = $reservationsModel -> read(
                criteria: [ 'screening_id = ' => $params[1] ],
                fetch: 'fetch'
            );

            if (isset($_POST['submit'])) {
                if ($idReservationsExist) $_SESSION['message']['error'] = $messages['messages']['error']['dashboard']['screenings']['delete'];
                else {
                    $screeningsModel -> delete($params[1], 'screeningId');
                    header('Location: /dashboard/screenings');
                }
            }

            /* Render page */
            $this -> render('dashboard/screenings/delete', compact(
                'pageData', 'screening'
            ), 'dashboard');

        } else header('Location: /dashboard/screenings');
    }

    /* If route method = 'reservations' */
    public function reservations (array $params = null) {
        $reservationsModel = new ReservationsModel();
        $usersModel = new UsersModel();
        $screeningsModel = new ScreeningsModel();
        $seatsModel = new SeatsModel();
        $pricingsModel = new PricingsModel();

        date_default_timezone_set('Europe/Paris');
        $date = date('Y-m-d');

        $messages = json_decode(file_get_contents('src/Core/messages.json'), true);

        if (!isset($_SESSION['user'])) header('Location: /');

        /* Reservation's index page */
        if (!$params) {
            $pageData = [
                'title' => "Réservations",
                'description' => "Réservations",
                'breadcrumb' => [ "Réservations" ]
            ];

            /* Count total reservations */
            $totalReservations = $reservationsModel -> read(
                criteria: [ 'user_id =' => $_SESSION['user'] -> userId ],
                count: 'count',
                fetch: 'fetch'
            ) -> count;

            /* Execute if user is admin */
            if ($_SESSION['user'] -> userRole === 'admin') {

                /* Pagination system */
                $page = 1;
                if (!isset($_SESSION['nbLines'])) $_SESSION['nbLines'] = 5;

                $reservations = null;
                if (!isset($_POST['page']) && !isset($_POST['show'])) {
                    $reservations = $reservationsModel -> read(
                        join: [
                            'table' => 'R',
                            'tables' => [
                                [
                                    'table1' => [ 'reservations', 'R', 'user_id' ],
                                    'table2' => [ 'users', 'U', 'userId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'screening_id'],
                                    'table2' => [ 'screenings', 'Sc', 'screeningId' ]
                                ], [
                                    'table1' => [ 'screenings', 'Sc', 'movieRoom_id' ],
                                    'table2' => [ 'moviesRooms', 'MR', 'movieRoomId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'seat_id' ],
                                    'table2' => [ 'seats', 'Se', 'seatId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'pricing_id' ],
                                    'table2' => [ 'pricings', 'P', 'pricingId' ]
                                ]
                            ]
                        ],
                        limit: $_SESSION['nbLines'],
                        offset: ($page - 1) * $_SESSION['nbLines'],
                        fetch: 'fetchAll'
                    );

                } else if (isset($_POST['page'])) {
                    $page = intval($_POST['page']);

                    $reservations = $reservationsModel -> read(
                        join: [
                            'table' => 'R',
                            'tables' => [
                                [
                                    'table1' => [ 'reservations', 'R', 'user_id' ],
                                    'table2' => [ 'users', 'U', 'userId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'screening_id'],
                                    'table2' => [ 'screenings', 'Sc', 'screeningId' ]
                                ], [
                                    'table1' => [ 'screenings', 'Sc', 'movieRoom_id' ],
                                    'table2' => [ 'moviesRooms', 'MR', 'movieRoomId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'seat_id' ],
                                    'table2' => [ 'seats', 'Se', 'seatId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'pricing_id' ],
                                    'table2' => [ 'pricings', 'P', 'pricingId' ]
                                ]
                            ]
                        ],
                        limit: $_SESSION['nbLines'],
                        offset: ($page - 1) * $_SESSION['nbLines'],
                        fetch: 'fetchAll'
                    );

                } else if (isset($_POST['show'])) {
                    $_SESSION['nbLines'] = intval($_POST['show']);

                    $reservations = $reservationsModel -> read(
                        join: [
                            'table' => 'R',
                            'tables' => [
                                [
                                    'table1' => [ 'reservations', 'R', 'user_id' ],
                                    'table2' => [ 'users', 'U', 'userId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'screening_id'],
                                    'table2' => [ 'screenings', 'Sc', 'screeningId' ]
                                ], [
                                    'table1' => [ 'screenings', 'Sc', 'movieRoom_id' ],
                                    'table2' => [ 'moviesRooms', 'MR', 'movieRoomId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'seat_id' ],
                                    'table2' => [ 'seats', 'Se', 'seatId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'pricing_id' ],
                                    'table2' => [ 'pricings', 'P', 'pricingId' ]
                                ]
                            ]
                        ],
                        limit: $_SESSION['nbLines'],
                        offset: ($page - 1) * $_SESSION['nbLines'],
                        fetch: 'fetchAll'
                    );
                }

                $nbPages = $totalReservations / $_SESSION['nbLines'];

            /* Execute if user is client */
            } else {
                /* Pagination system */
                $page = 1;
                if (!isset($_SESSION['nbLines'])) $_SESSION['nbLines'] = 5;

                $reservations = null;
                if (!isset($_POST['page']) && !isset($_POST['show'])) {
                    $reservations = $reservationsModel -> read(
                        criteria: [ 'userId =' => $_SESSION['user'] -> userId ],
                        join: [
                            'table' => 'R',
                            'tables' => [
                                [
                                    'table1' => [ 'reservations', 'R', 'user_id' ],
                                    'table2' => [ 'users', 'U', 'userId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'screening_id'],
                                    'table2' => [ 'screenings', 'Sc', 'screeningId' ]
                                ], [
                                    'table1' => [ 'screenings', 'Sc', 'movieRoom_id' ],
                                    'table2' => [ 'moviesRooms', 'MR', 'movieRoomId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'seat_id' ],
                                    'table2' => [ 'seats', 'Se', 'seatId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'pricing_id' ],
                                    'table2' => [ 'pricings', 'P', 'pricingId' ]
                                ]
                            ]
                        ],
                        limit: $_SESSION['nbLines'],
                        offset: ($page - 1) * $_SESSION['nbLines'],
                        fetch: 'fetchAll'
                    );

                } else if (isset($_POST['page'])) {
                    $page = intval($_POST['page']);

                    $reservations = $reservationsModel -> read(
                        criteria: [ 'userId =' => $_SESSION['user'] -> userId ],
                        join: [
                            'table' => 'R',
                            'tables' => [
                                [
                                    'table1' => [ 'reservations', 'R', 'user_id' ],
                                    'table2' => [ 'users', 'U', 'userId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'screening_id'],
                                    'table2' => [ 'screenings', 'Sc', 'screeningId' ]
                                ], [
                                    'table1' => [ 'screenings', 'Sc', 'movieRoom_id' ],
                                    'table2' => [ 'moviesRooms', 'MR', 'movieRoomId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'seat_id' ],
                                    'table2' => [ 'seats', 'Se', 'seatId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'pricing_id' ],
                                    'table2' => [ 'pricings', 'P', 'pricingId' ]
                                ]
                            ]
                        ],
                        limit: $_SESSION['nbLines'],
                        offset: ($page - 1) * $_SESSION['nbLines'],
                        fetch: 'fetchAll'
                    );

                } else if (isset($_POST['show'])) {
                    $_SESSION['nbLines'] = intval($_POST['show']);

                    $reservations = $reservationsModel -> read(
                        criteria: [ 'userId =' => $_SESSION['user'] -> userId ],
                        join: [
                            'table' => 'R',
                            'tables' => [
                                [
                                    'table1' => [ 'reservations', 'R', 'user_id' ],
                                    'table2' => [ 'users', 'U', 'userId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'screening_id'],
                                    'table2' => [ 'screenings', 'Sc', 'screeningId' ]
                                ], [
                                    'table1' => [ 'screenings', 'Sc', 'movieRoom_id' ],
                                    'table2' => [ 'moviesRooms', 'MR', 'movieRoomId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'seat_id' ],
                                    'table2' => [ 'seats', 'Se', 'seatId' ]
                                ], [
                                    'table1' => [ 'reservations', 'R', 'pricing_id' ],
                                    'table2' => [ 'pricings', 'P', 'pricingId' ]
                                ]
                            ]
                        ],
                        limit: $_SESSION['nbLines'],
                        offset: ($page - 1) * $_SESSION['nbLines'],
                        fetch: 'fetchAll'
                    );
                }

                $nbPages = $totalReservations / $_SESSION['nbLines'];
            }

            /* ACTIONS */
            if (isset($_POST['add'])) header('Location: /dashboard/reservations/add');
            if (isset($_POST['modify'])) header('Location: /dashboard/reservations/modify/'.$_POST['modify']);
            if (isset($_POST['delete'])) header('Location: /dashboard/reservations/delete/'.$_POST['delete']);

            /* Execute if user is admin */
            if ($_SESSION['user'] -> userRole === 'admin') {
                /* Count waiting reservations */
                $waitingReservations = $reservationsModel -> read(
                    criteria: [ 'screeningDate >=' => $date ],
                    join: [
                        'table' => 'R',
                        'tables' => [
                            [
                                'table1' => [ 'reservations', 'R', 'screening_id'],
                                'table2' => [ 'screenings', 'S', 'screeningId' ]
                            ]
                        ]
                    ],
                    count: 'count',
                    fetch: 'fetch'
                ) -> count;

                /* Count completed reservations */
                $completedReservations = $reservationsModel -> read(
                    criteria: [ 'screeningDate <' => $date ],
                    join: [
                        'table' => 'R',
                        'tables' => [
                            [
                                'table1' => [ 'reservations', 'R', 'screening_id'],
                                'table2' => [ 'screenings', 'S', 'screeningId' ]
                            ]
                        ]
                    ],
                    count: 'count',
                    fetch: 'fetch'
                ) -> count;

                /* Render page */
                $this -> render('dashboard/reservations/index', compact(
                    'pageData',
                    'reservations',
                    'totalReservations',
                    'waitingReservations',
                    'completedReservations',
                    'nbPages'
                ), 'dashboard');

            /* Execute if user is client */
            } else {
                /* Render page */
                $this -> render('dashboard/reservations/index', compact(
                    'pageData',
                    'reservations',
                    'totalReservations',
                    'nbPages'
                ), 'dashboard');
            }

        /* Reservation's details page */
        } else if (is_numeric($params[0])) {
            /* Get current reservation */
            $reservation = $reservationsModel -> read(
                criteria: [ 'reservationId =' => $params[0] ],
                join: [
                    'table' => 'R',
                    'tables' => [
                        [
                            'table1' => [ 'reservations', 'R', 'user_id' ],
                            'table2' => [ 'users', 'U', 'userId' ]
                        ], [
                            'table1' => [ 'reservations', 'R', 'screening_id' ],
                            'table2' => [ 'screenings', 'Sc', 'screeningId' ]
                        ], [
                            'table1' => [ 'screenings', 'Sc', 'movie_id' ],
                            'table2' => [ 'movies', 'M', 'movieId' ]
                        ], [
                            'table1' => [ 'screenings', 'Sc', 'movieRoom_id'],
                            'table2' => [ 'moviesRooms', 'MR', 'movieRoomId' ]
                        ], [
                            'table1' => [ 'reservations', 'R', 'seat_id' ],
                            'table2' => [ 'seats', 'Se', 'seatId' ]
                        ]
                    ]
                ],
                fetch: 'fetch'
            );

            /* Basic's data page */
            $pageData = [
                'title' => "Réservation n°" . $reservation -> reservationId,
                'description' => "Réservations",
                'breadcrumb' => [
                    "Réservations" => '/dashboard/reservations',
                    "Réservation n°" . $reservation -> reservationId
                ]
            ];

            /* Render page */
            $this -> render('dashboard/reservations/reservation', compact(
                'pageData', 'reservation'
            ), 'dashboard');

        /* Add reservation page */
        } else if ($params[0] === 'add') {
            $pageData = [
                'title' => "Ajouter une réservation",
                'description' => "Ajouter une réservation",
                'breadcrumb' => [
                    "Réservations" => '/dashboard/reservations',
                    "Ajouter une réservation"
                ]
            ];

            /* Fetch all users */
            $users = $usersModel -> read(
                columns: [ 'userId', 'userFirstName', 'userLastName' ],
                fetch: 'fetchAll'
            );

            /* Fetch all screenings */
            $screenings = $screeningsModel -> read(
                columns: [ 'screeningId', 'screeningDate', 'screeningTime', 'movieTitle' ],
                join: [
                    'table' => 'S',
                    'tables' => [
                        [
                            'table1' => [ 'screenings', 'S', 'movie_id' ],
                            'table2' => [ 'movies', 'M', 'movieId' ]
                        ]
                    ]
                ],
                fetch: 'fetchAll'
            );

            /* Fetch all seats */
            $seats = $seatsModel -> read(
                columns: [ 'seatId', 'seatXPosition', 'seatYPosition', 'movieRoomName' ],
                join: [
                    'table' => 'S',
                    'tables' => [
                        [
                            'table1' => [ 'seats', 'S', 'movieRoom_id' ],
                            'table2' => [ 'moviesRooms', 'M', 'movieRoomId' ]
                        ]
                    ]
                ],
                fetch: 'fetchAll'
            );

            /* Fetch all pricings */
            $pricings = $pricingsModel -> read(
                columns: [ 'pricingId', 'pricingName', 'pricing' ],
                fetch: 'fetchAll'
            );

            /* Submit logic */
            $fieldsErrors = array();
            $tempFields = array();

            if (isset($_POST['submit'])) {
                if (empty($_POST['user'])) $fieldsErrors['user'] = $messages['messages']['error']['dashboard']['reservations']['add-modify']['user'];

                if (empty($_POST['screening'])) $fieldsErrors['screening'] = $messages['messages']['error']['dashboard']['reservations']['add-modify']['screening'];

                if (empty($_POST['seat'])) $fieldsErrors['seat'] = $messages['messages']['error']['dashboard']['reservations']['add-modify']['seat'];

                if (empty($_POST['pricing'])) $fieldsErrors['pricing'] = $messages['messages']['error']['dashboard']['reservations']['add-modify']['pricing'];

                if (empty($_POST['date']) || strlen($_POST['date']) !== 10) $fieldsErrors['date'] = $messages['messages']['error']['dashboard']['screenings']['add-modify']['date'];
                else $tempFields['date'] = $_POST['date'];

                if (empty($_POST['time'])) $fieldsErrors['time'] = $messages['messages']['error']['dashboard']['screenings']['add-modify']['time'];
                else $tempFields['time'] = $_POST['time'];

                if (empty($fieldsErrors)) {
                    $user = $_POST['user'];
                    $screening = $_POST['screening'];
                    $seat = $_POST['seat'];
                    $pricing = $_POST['pricing'];
                    $date = strip_tags($_POST['date']);
                    $time = strip_tags($_POST['time']);

                    $reservationsModel -> setUser_id($user)
                        -> setScreening_id($screening)
                        -> setSeat_id($seat)
                        -> setPricing_id($pricing)
                        -> setDate(format_date($date, 'sql'))
                        -> setTime($time);

                    $reservationsModel -> create();
                    $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['add']['reservations'];
                    header('Location: /dashboard/reservations');
                }
            }

            /* Render page */
            $this -> render('dashboard/reservations/add', compact(
                'pageData', 'users', 'screenings', 'seats', 'pricings', 'fieldsErrors', 'tempFields'
            ), 'dashboard');

        /* Modify reservation page */
        } else if ($params[0] === 'modify') {

            if ($_SESSION['user'] -> userRole === 'client') header('Location: /dashboard/reservations');

            /* Basic's page data */
            $pageData = [
                'title' => "Modifier une réservation",
                'description' => "Modifier une réservation",
                'breadcrumb' => [
                    "Réservations" => '/dashboard/reservations',
                    "Modifier une réservation"
                ]
            ];

            /* Get current reservation */
            $reservation = $reservationsModel -> read(
                criteria: [ 'reservationId =' => $params[1] ],
                join: [
                    'table' => 'R',
                    'tables' => [
                        [
                            'table1' => [ 'reservations', 'R', 'user_id' ],
                            'table2' => [ 'users', 'U', 'userId' ]
                        ]
                    ]
                ],
                fetch: 'fetch'
            );

            /* Fetch all users */
            $users = $usersModel -> read(
                fetch: 'fetchAll'
            );

            /* Fetch all screenings */
            $screenings = $screeningsModel -> read(
                join: [
                    'table' => 'S',
                    'tables' => [
                        [
                            'table1' => [ 'screenings', 'S', 'movie_id' ],
                            'table2' => [ 'movies', 'M', 'movieId' ]
                        ]
                    ]
                ],
                fetch:'fetchAll'
            );

            /* Fetch all seats */
            $seats = $seatsModel -> read(
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

            /* Submit logic */
            $fieldsErrors = array();

            if (isset($_POST['submit'])) {
                if (empty($_POST['user'])) $fieldsErrors['user'] = $messages['messages']['error']['dashboard']['reservations']['add-modify']['user'];
                else $reservationsModel -> setUser_id($_POST['user']);

                if (empty($_POST['screening'])) $fieldsErrors['screening'] = $messages['messages']['error']['dashboard']['reservations']['add-modify']['screening'];
                else $reservationsModel -> setScreening_id($_POST['screening']);

                if (empty($_POST['seat'])) $fieldsErrors['seat'] = $messages['messages']['error']['dashboard']['reservations']['add-modify']['seat'];
                else $reservationsModel -> setSeat_id($_POST['seat']);

                if (empty($_POST['pricing'])) $fieldsErrors['pricing'] = $messages['messages']['error']['dashboard']['reservations']['add-modify']['pricing'];
                else $reservationsModel -> setPricing_id($_POST['pricing']);

                if (empty($_POST['date']) || strlen($_POST['date']) !== 10) $fieldsErrors['date'] = $messages['messages']['error']['dashboard']['screenings']['add-modify']['date'];
                else $reservationsModel -> setDate(format_date(strip_tags($_POST['date']), 'sql'));

                if (empty($_POST['time'])) $fieldsErrors['time'] = $messages['messages']['error']['dashboard']['screenings']['add-modify']['time'];
                else $reservationsModel -> setTime(strip_tags($_POST['time']));

                if (empty($fieldsErrors)) {
                    $reservationsModel -> update([ 'reservationId' => $params[1] ]);
                    $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['modify']['reservations'];
                    header('Location: /dashboard/reservations');
                }
            }

            /* Render page */
            $this -> render('dashboard/reservations/modify', compact(
                'pageData', 'reservation', 'users', 'screenings', 'seats', 'fieldsErrors'
            ), 'dashboard');

        /* Delete reservation page */
        } else if ($params[0] === 'delete') {
            $pageData = [
                'title' => "Supprimer une réservation",
                'description' => "Supprimer une réservation",
                'breadcrumb' => [
                    "Réservations" => '/dashboard/reservations',
                    "Supprimer une réservation"
                ]
            ];

            /* Get current reservation */
            $reservation = $reservationsModel -> read(
                criteria: [ 'reservationId =' => $params[1] ],
                join: [
                    'table' => 'R',
                    'tables' => [
                        [
                            'table1' => [ 'reservations', 'R', 'user_id' ],
                            'table2' => [ 'users', 'U', 'userId' ]
                        ], [
                            'table1' => [ 'reservations', 'R', 'screening_id' ],
                            'table2' => [ 'screenings', 'Sc', 'screeningId' ]
                        ], [
                            'table1' => [ 'screenings', 'Sc', 'movie_id' ],
                            'table2' => [ 'movies', 'M', 'movieId' ]
                        ], [
                            'table1' => [ 'screenings', 'Sc', 'movieRoom_id'],
                            'table2' => [ 'moviesRooms', 'MR', 'movieRoomId' ]
                        ], [
                            'table1' => [ 'reservations', 'R', 'seat_id' ],
                            'table2' => [ 'seats', 'Se', 'seatId' ]
                        ]
                    ]
                ],
                fetch: 'fetch'
            );

            /* Delete reservation logic */
            if (isset($_POST['submit'])) {
                $reservationsModel -> delete($params[1], 'reservationId');
                header('Location: /dashboard/reservations');
            }

            /* Render page */
            $this -> render('dashboard/reservations/delete', compact(
                'pageData', 'reservation'
            ), 'dashboard');

        } else header('Location: /dashboard/reservations');
    }

    /* If route method = 'movies_rooms' */
    public function movies_rooms (array $params = null) {
        $moviesRoomsModel = new MoviesRoomsModel();
        $screeningsModel = new ScreeningsModel();
        $seatsModel = new SeatsModel();

        $messages = json_decode(file_get_contents('src/Core/messages.json'), true);

        if (!isset($_SESSION['user'])) header('Location: /');

        /* Movies rooms index page */
        if (!$params) {
            $pageData = [
                'title' => "Salles",
                'description' => "Salles",
                'breadcrumb' => [ "Salles" ]
            ];

            /* Count total movies rooms */
            $totalMoviesRooms = $moviesRoomsModel -> read(
                count: 'count',
                fetch: 'fetch'
            ) -> count;

            /* Pagination system */
            $page = 1;
            if (!isset($_SESSION['nbLines'])) $_SESSION['nbLines'] = 5;

            $moviesRooms = null;
            if (!isset($_POST['page']) && !isset($_POST['show'])) {
                $moviesRooms = $moviesRoomsModel -> read(
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );
            } else if (isset($_POST['page'])) {
                $page = intval($_POST['page']);

                $moviesRooms = $moviesRoomsModel -> read(
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );

            } else if (isset($_POST['show'])) {
                $_SESSION['nbLines'] = intval($_POST['show']);

                $moviesRooms = $moviesRoomsModel -> read(
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'nbLines'
                );
            }

            $nbPages = $totalMoviesRooms / $_SESSION['nbLines'];

            /* ACTIONS */
            if (isset($_POST['add'])) header('Location: /dashboard/movies_rooms/add');
            if (isset($_POST['modify'])) header('Location: /dashboard/movies_rooms/modify/'.$_POST['modify']);
            if (isset($_POST['delete'])) header('Location: /dashboard/movies_rooms/delete/'.$_POST['delete']);

            /* Render page */
            $this -> render('dashboard/movies_rooms/index', compact(
                'pageData',
                'moviesRooms',
                'totalMoviesRooms',
                'nbPages'
            ), 'dashboard');


        /* Movie room's details page */
        } else if (is_numeric($params[0])) {
            /* Get current movie room */
            $movieRoom = $moviesRoomsModel -> read(
                criteria: [ 'movieRoomId =' => $params[0] ],
                fetch: 'fetch'
            );

            /* Basic's page data */
            $pageData = [
                'title' => $movieRoom -> movieRoomName,
                'description' => "Salles",
                'breadcrumb' => [
                    "Salles" => '/dashboard/movies_rooms',
                    $movieRoom -> movieRoomName
                ]
            ];

            /* Render page */
            $this -> render('dashboard/movies_rooms/movie_room', compact(
                'pageData', 'movieRoom'
            ), 'dashboard');

        /* Add reservation page */
        } else if ($params[0] === 'add') {
            $pageData = [
                'title' => "Ajouter une salle",
                'description' => "Ajouter une salle",
                'breadcrumb' => [
                    "Films" => '/dashboard/movies_rooms',
                    "Ajouter une salle"
                ]
            ];

            /* Submit logic */
            $fieldsErrors = array();
            $tempFields = array();

            if (isset($_POST['submit'])) {

                if (empty($_POST['name'])) $fieldsErrors['name'] = $messages['messages']['error']['dashboard']['movies-rooms']['add-modify']['name'];
                else $tempFields['name'] = $_POST['name'];

                if (!empty($_POST['description'])) $tempFields['description'] = $_POST['description'];

                if (empty($fieldsErrors)) {
                    $name = strip_tags($_POST['name']);
                    $description = (!empty($_POST['description'])) ? strip_tags($_POST['description']) : null;

                    $moviesRoomsModel -> setName($name)
                        -> setDescription($description);

                    $moviesRoomsModel -> create();
                    $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['add']['movies_rooms'];
                    header('Location: /dashboard/movies_rooms');
                }
            }

            /* Render page */
            $this -> render('dashboard/movies_rooms/add',
                compact(
                    'pageData', 'fieldsErrors', 'tempFields'
                ), 'dashboard');

        /* Modify movie room page */
        } else if ($params[0] === 'modify') {
            if (!$params[1]) header('Location: /dashboard/movies_rooms');
            else {

                /* Basic's page data */
                $pageData = [
                    'title' => "Modifier une salle",
                    'description' => "Modifier une salle",
                    'breadcrumb' => [
                        "Salles" => '/dashboard/movies_rooms',
                        "Modifier une salle"
                    ]
                ];

                /* Get current movie room */
                $movieRoom = $moviesRoomsModel -> read(
                    criteria: [ 'movieRoomId =' => $params[1] ],
                    fetch: 'fetch'
                );

                /* Modify logic */
                $fieldsErrors = array();

                if (isset($_POST['submit'])) {

                    if (empty($_POST['name'])) $fieldsErrors['name'] = $messages['messages']['error']['dashboard']['movies-rooms']['add-modify']['name'];
                    else $moviesRoomsModel -> setName(strip_tags($_POST['name']));

                    if (!empty($_POST['description'])) $moviesRoomsModel -> setDescription(strip_tags($_POST['description']));

                    if (empty($fieldsErrors)) {
                        $moviesRoomsModel -> update([ 'movieRoomId' => $params[1] ]);
                        $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['modify']['movies_rooms'];
                        header('Location: /dashboard/movies_rooms');
                    }
                }

                /* Render page */
                $this -> render('dashboard/movies_rooms/modify', compact(
                    'pageData', 'movieRoom', 'fieldsErrors'
                ), 'dashboard');
            }

        /* Delete movie room page */
        } else if ($params[0] === 'delete') {
            $pageData = [
                'title' => "Supprimer une salle",
                'description' => "Supprimer une salle",
                'breadcrumb' => [
                    "Salles" => '/dashboard/movies_rooms',
                    "Supprimer une salle"
                ]
            ];

            /* Get current movie room */
            $movieRoom = $moviesRoomsModel -> read(
                criteria: [ 'movieRoomId =' => $params[1] ],
                fetch: 'fetch'
            );

            /* Delete movie room logic */
            $idScreeningsExist = $screeningsModel -> read(
                criteria: [ 'movieRoom_id = ' => $params[1] ],
                fetch: 'fetch'
            );

            $idSeatsExist = $seatsModel -> read(
                criteria: [ 'movieRoom_id = ' => $params[1] ],
                fetch: 'fetch'
            );

            if (isset($_POST['submit'])) {
                if ($idScreeningsExist || $idSeatsExist) $_SESSION['message']['error'] = $messages['messages']['error']['dashboard']['movies-rooms']['delete'];
                else {
                    $moviesRoomsModel -> delete($params[1], 'movieRoomId');
                    header('Location: /dashboard/movies_rooms');
                }
            }

            /* Render page */
            $this -> render('dashboard/movies_rooms/delete', compact(
                'pageData', 'movieRoom'
            ), 'dashboard');

        } else header('Location: /dashboard/movies_rooms');
    }

    /* If route method = 'pricings' */
    public function pricings (array $params = null) {
        $pricingsModel = new PricingsModel();
        $reservationsModel = new ReservationsModel();

        $messages = json_decode(file_get_contents('src/Core/messages.json'), true);

        if (!isset($_SESSION['user'])) header('Location: /');

        /* Pricings' index page */
        if (!$params) {
            /* Basic's page data */
            $pageData = [
                'title' => "Tarifs",
                'description' => "Tarifs",
                'breadcrumb' => [ "Tarifs" ]
            ];

            /* Count total pricings */
            $totalPricings = $pricingsModel -> read(
                count: 'count',
                fetch: 'fetch'
            ) -> count;

            /* Pagination system */
            $page = 1;
            if (!isset($_SESSION['nbLines'])) $_SESSION['nbLines'] = 5;

            $pricings = null;
            if (!isset($_POST['page']) && !isset($_POST['show'])) {

                $pricings = $pricingsModel -> read(
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );

            } else if (isset($_POST['page'])) {
                $page = intval($_POST['page']);

                $pricings = $pricingsModel -> read(
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );

            } else if (isset($_POST['show'])) {
                $_SESSION['nbLines'] = intval($_POST['show']);

                $pricings = $pricingsModel -> read(
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );
            }

            $nbPages = $totalPricings / $_SESSION['nbLines'];

            /* ACTIONS */
            if (isset($_POST['add'])) header('Location: /dashboard/pricings/add');
            if (isset($_POST['modify'])) header('Location: /dashboard/pricings/modify/'.$_POST['modify']);
            if (isset($_POST['delete'])) header('Location: /dashboard/pricings/delete/'.$_POST['delete']);

            /* Render page */
            $this -> render('dashboard/pricings/index', compact(
                'pageData', 'pricings', 'totalPricings', 'nbPages'
            ), 'dashboard');

        /* Pricing's details page */
        } else if (is_numeric($params[0])) {
            $pricing = $pricingsModel -> read(
                criteria: [ 'pricingId =' => $params[0] ],
                fetch: 'fetch'
            );

            /* Basic's page data */
            $pageData = [
                'title' => $pricing -> pricingName,
                'description' => "Tarifs",
                'breadcrumb' => [
                    "Tarifs" => '/dashboard/pricings',
                    $pricing -> pricingName
                ]
            ];

            /* Render page */
            $this -> render('dashboard/pricings/pricing', compact(
                'pageData', 'pricing'
            ), 'dashboard');

        /* Add pricing page */
        } else if ($params[0] === 'add') {
            /* Basic's page data */
            $pageData = [
                'title' => "Ajouter un tarif",
                'description' => "Ajouter un tarif",
                'breadcrumb' => [
                    "Tarifs" => '/dashboard/pricings',
                    "Ajouter un tarif"
                ]
            ];

            /* Submit logic */
            $fieldsErrors = array();
            $tempFields = array();

            if (isset($_POST['submit'])) {

                if (empty($_POST['name'])) $fieldsErrors['name'] = $messages['messages']['error']['dashboard']['pricings']['add-modify']['name'];
                else $tempFields['name'] = $_POST['name'];

                if (empty($_POST['pricing'])) $fieldsErrors['pricing'] = $messages['messages']['error']['dashboard']['pricings']['add-modify']['pricing'];
                else $tempFields['pricing'] = $_POST['pricing'];

                if (!empty($_POST['description'])) $tempFields['description'] = $_POST['description'];

                if (empty($fieldsErrors)) {
                    $name = strip_tags($_POST['name']);
                    $pricing = strip_tags($_POST['pricing']);
                    $description = (!empty($_POST['description'])) ? strip_tags($_POST['description']) : null;

                    $pricingsModel -> setName($name)
                        -> setDescription($description)
                        -> setPricing($pricing);

                    $pricingsModel -> create();
                    $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['add']['pricings'];
                    header('Location: /dashboard/pricings');
                }
            }

            /* Render page */
            $this -> render('dashboard/pricings/add', compact(
                'pageData', 'fieldsErrors', 'tempFields'
            ), 'dashboard');

        /* Modify pricing page */
        } else if ($params[0] === 'modify') {
            /* Basic's page data */
            $pageData = [
                'title' => "Modifier un tarif",
                'description' => "Modifier un tarif",
                'breadcrumb' => [
                    "Tarifs" => '/dashboard/pricings',
                    "Modifier un tarif"
                ]
            ];

            /* Get current pricing */
            $pricing = $pricingsModel -> read(
                criteria: [ 'pricingId =' => $params[1] ],
                fetch: 'fetch'
            );

            /* Submit logic */
            $fieldsErrors = array();

            if (isset($_POST['submit'])) {

                if (empty($_POST['name'])) $fieldsErrors['name'] = $messages['messages']['error']['dashboard']['pricings']['add-modify']['name'];
                else $pricingsModel -> setName(strip_tags($_POST['name']));

                if (empty($_POST['pricing'])) $fieldsErrors['pricing'] = $messages['messages']['error']['dashboard']['pricings']['add-modify']['pricing'];
                else $pricingsModel -> setPricing(strip_tags($_POST['pricing']));

                if (!empty($_POST['description'])) $pricingsModel -> setDescription(strip_tags($_POST['description']));

                if (empty($fieldsErrors)) {
                    $pricingsModel -> update([ 'pricingId' => $params[1] ]);
                    $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['modify']['pricings'];
                    header('Location: /dashboard/pricings');
                }
            }

            /* Render page */
            $this -> render('dashboard/pricings/modify', compact(
                'pageData', 'pricing', 'fieldsErrors'
            ), 'dashboard');

        /* Delete screening page */
        } else if ($params[0] === 'delete') {
            /* Basic's page data */
            $pageData = [
                'title' => "Supprimer un tarif",
                'description' => "Supprimer un tarif",
                'breadcrumb' => [
                    "Tarifs" => '/dashboard/pricings',
                    "Supprimer un tarif"
                ]
            ];

            /* Get current pricing */
            $pricing = $pricingsModel -> read(
                criteria: [ 'pricingId =' => $params[1] ],
                fetch: 'fetch'
            );

            /* Delete pricing logic */
            $idReservationsExist = $reservationsModel -> read(
                criteria: [ 'pricing_id = ' => $params[1] ],
                fetch: 'fetch'
            );

            if (isset($_POST['submit'])) {
                if ($idReservationsExist) $_SESSION['message']['error'] = $messages['messages']['error']['dashboard']['pricings']['delete'];
                else {
                    $pricingsModel -> delete($params[1], 'pricingId');
                    header('Location: /dashboard/pricings');
                }
            }

            /* Render page */
            $this -> render('dashboard/pricings/delete', compact(
                'pageData', 'pricing'
            ), 'dashboard');

        } else header('Location: /dashboard/pricings');
    }

    /* If route method = 'subscriptions' */
    public function subscriptions (array $params = null) {
        $subscriptionsModel = new SubscriptionsModel();
        $users_SubscriptionsModel = new Users_SubscriptionsModel();

        $messages = json_decode(file_get_contents('src/Core/messages.json'), true);

        if (!isset($_SESSION['user'])) header('Location: /');

        /* Subscriptions's index page */
        if (!$params) {
            $pageData = [
                'title' => "Abonnements",
                'description' => "Abonnements",
                'breadcrumb' => [ "Abonnements" ]
            ];

            /* Execute if user is admin */
            if ($_SESSION['user'] -> userRole === 'admin') {
                /* Count total subscriptions */
                $totalSubscriptions = $subscriptionsModel -> read(
                    count: 'count',
                    fetch: 'fetch'
                ) -> count;


                /* Pagination system */
                $page = 1;

                if (!isset($_SESSION['nbLines'])) {
                    $_SESSION['nbLines'] = 5;
                }

                $subscriptions = null;
                if (!isset($_POST['page']) && !isset($_POST['show'])) {
                    $subscriptions = $subscriptionsModel -> read(
                        limit: $_SESSION['nbLines'],
                        offset: ($page - 1) * $_SESSION['nbLines'],
                        fetch: 'fetchAll'
                    );

                } else if (isset($_POST['page'])) {
                    $page = $_POST['page'];

                    $subscriptions = $subscriptionsModel -> read(
                        limit: $_SESSION['nbLines'],
                        offset: ($page - 1) * $_SESSION['nbLines'],
                        fetch: 'fetchAll'
                    );

                } else if (isset($_POST['show'])) {
                    $_SESSION['nbLines'] = intval($_POST['show']);

                    $subscriptions = $subscriptionsModel -> read(
                        limit: $_SESSION['nbLines'],
                        offset: ($page - 1) * $_SESSION['nbLines'],
                        fetch: 'fetchAll'
                    );
                }

                $nbPages = $totalSubscriptions / $_SESSION['nbLines'];

            /* Execute if user is client */
            } else {
                /* Count total user's subscriptions */
                $totalSubscriptions = $users_SubscriptionsModel -> read(
                    criteria: [ 'user_id =' => $_SESSION['user'] -> userId ],
                    count: 'count',
                    fetch: 'fetch'
                ) -> count;

                /* Pagination system */
                $page = 1;

                if (!isset($_SESSION['nbLines'])) {
                    $_SESSION['nbLines'] = 5;
                }

                $subscriptions = null;

                if (!isset($_POST['page']) && !isset($_POST['show'])) {
                    $subscriptions = $users_SubscriptionsModel -> read(
                        criteria: [ 'user_id =' => $_SESSION['user'] -> userId ],
                        join: [
                            'table' => 'US',
                            'tables' => [
                                [
                                    'table1' => [ 'users_subscriptions', 'US', 'subscription_id' ],
                                    'table2' => [ 'subscriptions', 'S', 'subscriptionId' ]
                                ]
                            ]
                        ],
                        limit: $_SESSION['nbLines'],
                        offset: ($page - 1) * $_SESSION['nbLines'],
                        fetch: 'fetchAll'
                    );

                } else if (isset($_POST['page'])) {
                    $page = $_POST['page'];

                    $subscriptions = $users_SubscriptionsModel -> read(
                        criteria: [ 'user_id =' => $_SESSION['user'] -> userId ],
                        join: [
                            'table' => 'US',
                            'tables' => [
                                [
                                    'table1' => [ 'users_subscriptions', 'US', 'subscription_id' ],
                                    'table2' => [ 'subscriptions', 'S', 'subscriptionId' ]
                                ]
                            ]
                        ],
                        limit: $_SESSION['nbLines'],
                        offset: ($page - 1) * $_SESSION['nbLines'],
                        fetch: 'fetchAll'
                    );

                } else if (isset($_POST['show'])) {
                    $_SESSION['nbLines'] = intval($_POST['show']);

                    $subscriptions = $users_SubscriptionsModel -> read(
                        criteria: [ 'userId =' => $_SESSION['user'] -> userId ],
                        join: [
                            'table' => 'US',
                            'tables' => [
                                [
                                    'table1' => [ 'users_subscriptions', 'US', 'subscription_id' ],
                                    'table2' => [ 'subscriptions', 'S', 'subscriptionId' ]
                                ]
                            ]
                        ],
                        limit: $_SESSION['nbLines'],
                        offset: ($page - 1) * $_SESSION['nbLines'],
                        fetch: 'fetchAll'
                    );
                }

                $nbPages = $totalSubscriptions / $_SESSION['nbLines'];
            }

            /* ACTIONS */
            if (isset($_POST['add'])) header('Location: /dashboard/subscriptions/add');
            if (isset($_POST['modify'])) header('Location: /dashboard/subscriptions/modify/' . $_POST['modify']);
            if (isset($_POST['delete'])) header('Location: /dashboard/subscriptions/delete/' . $_POST['delete']);

            /* Render page */
            $this -> render('dashboard/subscriptions/index', compact(
                'pageData', 'totalSubscriptions', 'subscriptions', 'nbPages'
            ), 'dashboard');

        /* Subscription's details page */
        } else if (is_numeric($params[0])) {
            $subscription = $subscriptionsModel -> read(
                criteria: [ 'subscriptionId =' => $params[0] ],
                fetch: 'fetch'
            );

            /* Basic's page data */
            $pageData = [
                'title' => $subscription -> subscriptionName,
                'description' => "Abonnements",
                'breadcrumb' => [
                    "Abonnements" => '/dashboard/subscriptions',
                    $subscription -> subscriptionName
                ]
            ];

            /* Render page */
            $this -> render('dashboard/subscriptions/subscription', compact(
                'pageData', 'subscription'
            ), 'dashboard');

        /* Add subscription page */
        } else if ($params[0] === 'add') {
            /* Basic's page data */
            $pageData = [
                'title' => "Ajouter un abonnement",
                'description' => "Ajouter un abonnement",
                'breadcrumb' => [
                    "Abonnements" => '/dashboard/subscriptions',
                    "Ajouter un abonnement"
                ]
            ];

            /* Submit logic */
            $fieldsErrors = array();
            $tempFields = array();

            if (isset($_POST['submit'])) {

                if (empty($_POST['name'])) $fieldsErrors['name'] = $messages['messages']['error']['dashboard']['subscriptions']['add-modify']['name'];
                else $tempFields['name'] = $_POST['name'];

                if (empty($_POST['pricing'])) $fieldsErrors['pricing'] = $messages['messages']['error']['dashboard']['subscriptions']['add-modify']['pricing'];
                else $tempFields['pricing'] = $_POST['pricing'];

                if (!empty($_POST['description'])) $tempFields['description'] = $_POST['description'];

                if (empty($fieldsErrors)) {
                    $name = strip_tags($_POST['name']);
                    $description = (!empty($_POST['description'])) ? strip_tags($_POST['description']) : null;
                    $pricing = strip_tags($_POST['pricing']);

                    $subscriptionsModel -> setName($name)
                        -> setDescription($description)
                        -> setPricing($pricing);

                    $subscriptionsModel -> create();
                    $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['add']['subscriptions'];
                    header('Location: /dashboard/subscriptions');
                }
            }

            /* Render page */
            $this -> render('dashboard/subscriptions/add', compact(
                'pageData', 'fieldsErrors', 'tempFields'
            ), 'dashboard');

        /* Modify subscription page */
        } else if ($params[0] === 'modify') {
            if ($_SESSION['user'] -> userRole === 'client') header('Location: /dashboard/subscriptions');

            /* Basic's page data */
            $pageData = [
                'title' => "Modifier un abonnement",
                'description' => "Modifier un abonnement",
                'breadcrumb' => [
                    "Abonnements" => '/dashboard/subscriptions',
                    "Modifier un abonnement"
                ]
            ];

            /* Get current subscription */
            $subscription = $subscriptionsModel -> read(
                criteria: [ 'subscriptionId =' => $params[1] ],
                fetch: 'fetch'
            );

            /* Submit logic */
            $fieldsErrors = array();
            $tempFields = array();

            if (isset($_POST['submit'])) {

                if (empty($_POST['name'])) $fieldsErrors['name'] = $messages['messages']['error']['dashboard']['subscriptions']['add-modify']['name'];
                else $subscriptionsModel -> setName(strip_tags($_POST['name']));

                if (empty($_POST['pricing'])) $fieldsErrors['pricing'] = $messages['messages']['error']['dashboard']['subscriptions']['add-modify']['pricing'];
                else $subscriptionsModel -> setPricing(strip_tags($_POST['pricing']));

                if (!empty($_POST['description'])) $subscriptionsModel -> setDescription(strip_tags($_POST['description']));

                if (empty($fieldsErrors)) {
                    $subscriptionsModel -> update([ 'subscriptionId' => $params[1] ]);
                    $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['modify']['subscriptions'];
                    header('Location: /dashboard/subscriptions');
                }
            }

            /* Render page */
            $this -> render('dashboard/subscriptions/modify', compact(
                'pageData', 'subscription', 'fieldsErrors'
            ), 'dashboard');

        /* Delete subscription page */
        } else if ($params[0] === 'delete') {
            $pageData = [
                'title' => "Supprimer un abonnement",
                'description' => "Supprimer un abonnement",
                'breadcrumb' => [
                    "Abonnements" => '/dashboard/subscriptions',
                    "Supprimer un abonnement"
                ]
            ];

            /* Get current subscription */
            $subscription = $subscriptionsModel -> read(
                criteria: ['subscriptionId =' => $params[1]],
                fetch: 'fetch'
            );

            /* Delete subscription logic */
            $idUsers_SubscriptionsModel = $users_SubscriptionsModel -> read(
                criteria: [ 'subscription_id = ' => $params[1] ],
                fetch: 'fetch'
            );

            if (isset($_POST['submit'])) {
                if ($idUsers_SubscriptionsModel) $_SESSION['message']['error'] = $messages['messages']['error']['dashboard']['subscriptions']['delete'];
                else {
                    $subscriptionsModel -> delete($params[1], 'subscriptionId');
                    header('Location: /dashboard/subscriptions');
                }
            }

            /* Render page */
            $this -> render('dashboard/subscriptions/delete', compact(
                'pageData', 'subscription'
            ), 'dashboard');

        } else header('Location: /dashboard/subscriptions');
    }

    /* If route method = 'users' */
    public function users (array $params = null) {
        $usersModel = new UsersModel();
        $reservationsModel = new ReservationsModel();
        $subscriptionsModel = new SubscriptionsModel();
        $users_SubscriptionsModel = new Users_SubscriptionsModel();

        $messages = json_decode(file_get_contents('src/Core/messages.json'), true);

        if (!isset($_SESSION['user'])) header('Location: /');

        /* Users' index page */
        if (!$params) {
            $pageData = [
                'title' => "Utilisateurs",
                'description' => "Utilisateurs",
                'breadcrumb' => [ "Utilisateurs" ]
            ];

            /* Count total users */
            $totalUsers = $usersModel -> read(
                count: 'count',
                fetch: 'fetch'
            ) -> count;

            /* Pagination system */
            $page = 1;
            if (!isset($_SESSION['nbLines'])) $_SESSION['nbLines'] = 5;

            $users = null;
            if (!isset($_POST['page']) && !isset($_POST['show'])) {
                $users = $usersModel -> read(
                    columns: [
                        'userId',
                        'userFirstName',
                        'userLastName',
                        'userEmail',
                        'userPhoneNumber',
                        'userRole'
                    ],
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );

            } else if (isset($_POST['page'])) {
                $page = intval($_POST['page']);

                $users = $usersModel -> read(
                    columns: [
                        'userId',
                        'userFirstName',
                        'userLastName',
                        'userEmail',
                        'userPhoneNumber',
                        'userRole'
                    ],
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );

            } else if (isset($_POST['show'])) {
                $_SESSION['nbLines'] = intval($_POST['show']);

                $users = $usersModel -> read(
                    columns: [
                        'userId',
                        'userFirstName',
                        'userLastName',
                        'userEmail',
                        'userPhoneNumber',
                        'userRole'
                    ],
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );
            }

            $nbPages = $totalUsers / $_SESSION['nbLines'];

            /* ACTIONS */
            if (isset($_POST['add'])) header('Location: /dashboard/users/add');
            if (isset($_POST['modify'])) header('Location: /dashboard/users/modify/'.$_POST['modify']);
            if (isset($_POST['delete'])) header('Location: /dashboard/users/delete/'.$_POST['delete']);

            /* Render page */
            $this -> render('dashboard/users/index', compact(
                'pageData', 'users', 'totalUsers', 'nbPages', 'page'
            ), 'dashboard');

        /* User's details page */
        } else if (is_numeric($params[0])) {
            /* Get current user */
            $user = $usersModel -> read(
                criteria: [ 'userId =' => $params[0] ],
                fetch: 'fetch'
            );

            /* Basic's page data */
            $pageData = [
                'title' => $user -> userLastName . " " . $user -> userFirstName,
                'description' => "Utilisateurs",
                'breadcrumb' => [
                    "Utilisateurs" => '/dashboard/users',
                    $user -> userLastName . " " . $user -> userFirstName
                ]
            ];

            /* Get user's subscriptions */
            $users_subscriptions = $users_SubscriptionsModel -> read(
                criteria: [ 'user_id =' => $params[0] ],
                join: [
                    'table' => 'US',
                    'tables' => [
                        [
                            'table1' => [ 'users_subscriptions', 'US', 'subscription_id' ],
                            'table2' => [ 'subscriptions', 'S', 'subscriptionId' ]
                        ]
                    ]
                ],
                fetch: 'fetchAll'
            );

            /* Render page */
            $this -> render('dashboard/users/user', compact(
                'pageData', 'user', 'users_subscriptions'
            ), 'dashboard');

        /* Add user page */
        } else if ($params[0] === 'add') {
            /* Basic's page data */
            $pageData = [
                'title' => "Ajouter un utilisateur",
                'description' => "Ajouter un utilisateur",
                'breadcrumb' => [
                    "Utilisateurs" => '/dashboard/users',
                    "Ajouter un utilisateur"
                ]
            ];

            /* Submit logic */
            $fieldsErrors = array();
            $tempFields = array();

            if (isset($_POST['submit'])) {

                if (empty($_POST['last-name']) || !preg_match('/^[a-zA-Z]{1,30}$/', $_POST['last-name'])) $fieldsErrors['last-name'] = $messages['messages']['error']['dashboard']['users']['add-modify']['last-name'];
                else $tempFields['last-name'] = $_POST['last-name'];

                if (empty($_POST['first-name']) || !preg_match('/^[a-zA-Z]{1,30}$/', $_POST['first-name'])) $fieldsErrors['first-name'] = $messages['messages']['error']['dashboard']['users']['add-modify']['first-name'];
                else $tempFields['first-name'] = $_POST['first-name'];

                if (empty($_POST['email'])) $fieldsErrors['email'] = $messages['messages']['error']['dashboard']['users']['add-modify']['email'];
                else $tempFields['email'] = $_POST['email'];

                if (!empty($_POST['phone-number'])) $tempFields['phone-number'] = $_POST['phone-number'];

                if (empty($_POST['password']) || !preg_match('/^[a-zA-Z0-9_\-&*\/]{8,}$/', $_POST['password'])) $fieldsErrors['password'] = $messages['messages']['error']['dashboard']['users']['add-modify']['password'];
                else $tempFields['password'] = $_POST['password'];

                if (!empty($_POST['profile-picture'])) $tempFields['profile-picture'] = $_POST['profile-picture'];

                if (!empty($_POST['birth-date'])) $tempFields['birth-date'] = $_POST['birth-date'];

                if (empty($_POST['role'])) $fieldsErrors['role'] = $messages['messages']['error']['dashboard']['users']['add-modify']['role'];

                if (empty($fieldsErrors)) {
                    $lastName = strip_tags($_POST['last-name']);
                    $firstName = strip_tags($_POST['first-name']);
                    $email = strip_tags($_POST['email']);
                    $phoneNumber = (!empty($_POST['phone-number'])) ? strip_tags($_POST['phone-number']) : null;
                    $birthDate = (!empty($_POST['birth-date'])) ? strip_tags($_POST['birth-date']) : null;
                    $password = strip_tags($_POST['pricing']);
                    $profilePicture = (!empty($_POST['profile-picture'])) ? strip_tags($_POST['profile-picture']) : null;
                    $role = $_POST['role'];

                    $usersModel -> setFirstName($firstName)
                        -> setLastName($lastName)
                        -> setEmail($email)
                        -> setPhoneNumber($phoneNumber)
                        -> setBirthDate($birthDate)
                        -> setPassword($password)
                        -> setProfilePicture($profilePicture)
                        -> setRole($role);

                    $usersModel -> create();
                    $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['add']['users'];
                    header('Location: /dashboard/users');
                }
            }

            /* Render page */
            $this -> render('dashboard/users/add', compact(
                'pageData', 'fieldsErrors', 'tempFields'
            ), 'dashboard');

        /* Modify user page */
        } else if ($params[0] === 'modify') {
            if (!$params[1]) header('Location: /dashboard/users');
            else {
                /* Modify user */
                if (!isset($params[2])) {
                    /* Basic's page data */
                    $pageData = [
                        'title' => "Modifier un utilisateur",
                        'description' => "Modifier un utilisateur",
                        'breadcrumb' => [
                            "Utilisateurs" => '/dashboard/users',
                            "Modifier un utilisateur"
                        ]
                    ];

                    /* Get current user */
                    $user = $usersModel -> read(
                        criteria: [ 'userId =' => $params[1] ],
                        fetch: 'fetch'
                    );

                    /* Submit logic */
                    $fieldsErrors = array();

                    if (isset($_POST['submit'])) {

                        if (empty($_POST['last-name']) || !preg_match('/^[a-zA-Z]{1,30}$/', $_POST['last-name'])) $fieldsErrors['last-name'] = $messages['messages']['error']['dashboard']['users']['add-modify']['last-name'];
                        else $usersModel -> setLastName(strip_tags($_POST['last-name']));

                        if (empty($_POST['first-name']) || !preg_match('/^[a-zA-Z]{1,30}$/', $_POST['first-name'])) $fieldsErrors['first-name'] = $messages['messages']['error']['dashboard']['users']['add-modify']['first-name'];
                        else $usersModel -> setFirstName(strip_tags($_POST['first-name']));

                        if (empty($_POST['email'])) $fieldsErrors['email'] = $messages['messages']['error']['dashboard']['users']['add-modify']['email'];
                        else $usersModel -> setEmail(strip_tags($_POST['email']));

                        if (!empty($_POST['phone-number'])) $usersModel -> setPhoneNumber(strip_tags($_POST['phone-number']));

                        if (!empty($_POST['profile-picture'])) $usersModel -> setProfilePicture(strip_tags($_POST['profile-picture']));

                        if (!empty($_POST['birth-date'])) $usersModel -> setBirthDate(strip_tags($_POST['birth-date']));

                        if (empty($_POST['role'])) $fieldsErrors['role'] = $messages['messages']['error']['dashboard']['users']['add-modify']['role'];
                        else $usersModel -> setRole($_POST['role']);

                        if (empty($fieldsErrors)) {
                            $usersModel -> update([ 'userId' => $params[1] ]);
                            $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['modify']['users'];
                            header('Location: /dashboard/users');
                        }
                    }

                    /* Fetch all user's subscriptions */
                    $users_subscriptions = $users_SubscriptionsModel -> read(
                        criteria: [ 'user_id =' => $params[1] ],
                        join: [
                            'table' => 'US',
                            'tables' => [
                                [
                                    'table1' => [ 'users_subscriptions', 'US', 'subscription_id' ],
                                    'table2' => [ 'subscriptions', 'S', 'subscriptionId' ]
                                ]
                            ]
                        ],
                        fetch: 'fetchAll'
                    );

                    if (isset($_POST['add'])) {
                        header('Location: /dashboard/users/modify/' . $params[1] . '/add_subscription');
                    }

                    /* Render page */
                    $this -> render('dashboard/users/modify', compact(
                        'pageData', 'user', 'users_subscriptions', 'fieldsErrors'
                    ), 'dashboard');

                /* Add subscription to user page */
                } else if ($params[2] === 'add_subscription') {
                    /* Get current user */
                    $user = $usersModel -> read(
                        criteria: [ 'userId =' => $params[1] ],
                        fetch: 'fetch'
                    );

                    /* Basic's page data */
                    $pageData = [
                        'title' => "Ajouter un abonnement",
                        'description' => "Utilisateurs",
                        'breadcrumb' => [
                            "Utilisateurs" => '/dashboard/users',
                            "Modifier un utilisateur" => '/dashboard/users/modify/' . $user -> userId,
                            "Ajouter un abonnement"
                        ]
                    ];

                    /* Fetch all subscriptions */
                    $subscriptions = $subscriptionsModel -> read(
                        fetch: 'fetchAll'
                    );

                    /* Add subscription logic */
                    if (isset($_POST['submit'])) {
                        $subscriptionExist = $users_SubscriptionsModel -> read(
                            criteria: [ 'user_id =' => $params[1], 'subscription_id =' => $_POST['subscription'] ],
                            fetch: 'fetchAll'
                        );

                        if (empty($subscriptionExist)) {
                            $users_SubscriptionsModel -> setUser_id($params[1])
                                -> setSubscription_id($_POST['subscription']);

                            $users_SubscriptionsModel -> create();

                            $_SESSION['message']['success'] = "L'abonnement a bien été ajouté";
                            header('Location: /dashboard/users/modify/' . $user -> userId);

                        } else $_SESSION['message']['error'] = "Cet utilisateur est déja abonné";
                    }

                    /* Render page */
                    $this -> render('dashboard/users/add_subscription', compact(
                        'pageData', 'subscriptions'
                    ), 'dashboard');
                }
            }

        /* Delete user page */
        } else if ($params[0] === 'delete') {
            $pageData = [
                'title' => "Supprimer un utilisateur",
                'description' => "Supprimer un utilisateur",
                'breadcrumb' => [
                    "Utilisateurs" => '/dashboard/users',
                    "Supprimer un utilisateur"
                ]
            ];

            /* Get current user */
            $user = $usersModel -> read(
                criteria: [ 'userId =' => $params[1] ],
                fetch: 'fetch'
            );

            /* Delete user logic */
            $idReservationsExist = $reservationsModel -> read(
                criteria: [ 'user_id = ' => $params[1] ],
                fetch: 'fetch'
            );

            $idUsers_SubscriptionsModel = $users_SubscriptionsModel -> read(
                criteria: [ 'user_id = ' => $params[1] ],
                fetch: 'fetch'
            );

            /* Fetch all users' subscriptions */
            $users_subscriptions = $users_SubscriptionsModel -> read(
                criteria: [ 'user_id =' => $params[1] ],
                join: [
                    'table' => 'US',
                    'tables' => [
                        [
                            'table1' => [ 'users_subscriptions', 'US', 'subscription_id' ],
                            'table2' => [ 'subscriptions', 'S', 'subscriptionId' ]
                        ]
                    ]
                ],
                fetch: 'fetchAll'
            );

            if (isset($_POST['submit'])) {
                if ($idReservationsExist || $idUsers_SubscriptionsModel) $_SESSION['message']['error'] = $messages['messages']['error']['dashboard']['users']['delete'];
                else {
                    $usersModel -> delete($params[1], 'userId');
                    header('Location: /dashboard/users');
                }
            }

            /* Render page */
            $this -> render('dashboard/users/delete', compact(
                'pageData', 'user', 'users_subscriptions'
            ), 'dashboard');

        } else header('Location: /dashboard/users');
    }

    /* If route method = 'directors' */
    public function directors (array $params = null) {
        $directorsModel = new DirectorsModel();
        $moviesModel = new MoviesModel();

        $messages = json_decode(file_get_contents('src/Core/messages.json'), true);

        if (!isset($_SESSION['user'])) header('Location: /');

        /* Directors's index page */
        if (!$params) {
            $pageData = [
                'title' => "Réalisateurs",
                'description' => "Réalisateurs",
                'breadcrumb' => [ "Réalisateurs" ]
            ];

            /* Count total directors */
            $totalDirectors = $directorsModel -> read(
                count: 'count',
                fetch: 'fetch'
            ) -> count;

            /* Pagination system */
            $page = 1;
            if (!isset($_SESSION['nbLines'])) $_SESSION['nbLines'] = 5;

            $directors = null;
            if (!isset($_POST['page']) && !isset($_POST['show'])) {
                $directors = $directorsModel -> read(
                    columns: [
                        'directorId',
                        'directorName',
                    ],
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );

            } else if (isset($_POST['page'])) {
                $page = intval($_POST['page']);

                $directors = $directorsModel -> read(
                    columns: [
                        'directorId',
                        'directorName',
                    ],
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );

            } else if (isset($_POST['show'])) {
                $_SESSION['nbLines'] = intval($_POST['show']);

                $directors = $directorsModel -> read(
                    columns: [
                        'directorId',
                        'directorName',
                    ],
                    limit: $_SESSION['nbLines'],
                    offset: ($page - 1) * $_SESSION['nbLines'],
                    fetch: 'fetchAll'
                );
            }

            $nbPages = $totalDirectors / $_SESSION['nbLines'];

            /* ACTIONS */
            if (isset($_POST['add'])) header('Location: /dashboard/directors/add');
            if (isset($_POST['modify'])) header('Location: /dashboard/directors/modify/'.$_POST['modify']);
            if (isset($_POST['delete'])) header('Location: /dashboard/directors/delete/'.$_POST['delete']);

            /* Render page */
            $this -> render('dashboard/directors/index', compact(
                'pageData', 'directors', 'totalDirectors', 'nbPages'
            ), 'dashboard');

        /* Director's details page */
        } else if (is_numeric($params[0])) {
            $director = $directorsModel -> read(
                criteria: [ 'directorId =' => $params[0] ],
                fetch: 'fetch'
            );

            /* Basic's page data */
            $pageData = [
                'title' => $director -> directorName,
                'description' => "Réalisateurs",
                'breadcrumb' => [
                    "Réalisateurs" => '/dashboard/directors',
                    $director -> directorName
                ]
            ];

            /* Render page */
            $this -> render('dashboard/directors/director', compact(
                'pageData', 'director'
            ), 'dashboard');

        /* Add director page */
        } else if ($params[0] === 'add') {
            /* Basic's page data */
            $pageData = [
                'title' => "Ajouter un réalisateur",
                'description' => "Ajouter un réalisateur",
                'breadcrumb' => [
                    "Réalisateurs" => '/dashboard/directors',
                    "Ajouter un réalisateur"
                ]
            ];

            /* Submit logic */
            $fieldsErrors = array();
            $tempFields = array();

            if (isset($_POST['submit'])) {

                if (empty($_POST['name'])) $fieldsErrors['name'] = $messages['messages']['error']['dashboard']['directors']['add-modify']['name'];
                else $tempFields['name'] = $_POST['name'];

                if (empty($_POST['picture'])) $fieldsErrors['picture'] = $messages['messages']['error']['dashboard']['directors']['add-modify']['picture'];
                else $tempFields['picture'] = $_POST['picture'];

                if (empty($fieldsErrors)) {
                    $name = strip_tags($_POST['name']);
                    $picture = strip_tags($_POST['picture']);

                    $directorsModel -> setName($name)
                        -> setPicture($picture);

                    $directorsModel -> create();
                    $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['add']['directors'];
                    header('Location: /dashboard/directors');
                }
            }

            /* Render page */
            $this -> render('dashboard/directors/add', compact(
                'pageData', 'fieldsErrors', 'tempFields'
            ), 'dashboard');

        /* Director's modify page */
        } else if ($params[0] === 'modify') {
            if (!$params[1]) header('Location: /dashboard/directors');
            else {
                /* Basic's page data */
                $pageData = [
                    'title' => "Modifier un réalisateur",
                    'description' => "Modifier un réalisateur",
                    'breadcrumb' => [
                        "Réalisateurs" => '/dashboard/directors',
                        "Modifier un réalisateur"
                    ]
                ];

                /* Get current director */
                $director = $directorsModel -> read(
                    criteria: [ 'directorId =' => $params[1] ],
                    fetch: 'fetch'
                );

                /* Modify logic */
                $fieldsErrors = array();

                if (isset($_POST['submit'])) {

                    if (empty($_POST['name'])) $fieldsErrors['name'] = $messages['messages']['error']['dashboard']['directors']['add-modify']['name'];
                    else $directorsModel -> setName(strip_tags($_POST['name']));

                    if (empty($_POST['picture'])) $fieldsErrors['picture'] = $messages['messages']['error']['dashboard']['directors']['add-modify']['picture'];
                    else $directorsModel -> setPicture(strip_tags($_POST['picture']));

                    if (empty($fieldsErrors)) {
                        $directorsModel -> update([ 'directorId' => $params[1] ]);
                        $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['modify']['directors'];
                        header('Location: /dashboard/directors');
                    }
                }

                /* Render page */
                $this -> render('dashboard/directors/modify', compact(
                    'pageData', 'director', 'fieldsErrors'
                ), 'dashboard');
            }

        /* Delete page */
        } else if ($params[0] === 'delete') {
            /* Basic's page data */
            $pageData = [
                'title' => "Supprimer un réalisateur",
                'description' => "Supprimer un réalisateur",
                'breadcrumb' => [
                    "Réalisateur" => '/dashboard/directors',
                    "Supprimer un réalisateur"
                ]
            ];

            /* Get current director */
            $director = $directorsModel -> read(
                criteria: [ 'directorId =' => $params[1] ],
                fetch: 'fetch'
            );

            /* Delete director logic */
            $idMoviesExist = $moviesModel -> read(
                criteria: [ 'director_id = ' => $params[1] ],
                fetch: 'fetch'
            );

            if (isset($_POST['submit'])) {
                if ($idMoviesExist) $_SESSION['message']['error'] = $messages['messages']['error']['dashboard']['directors']['delete'];
                else {
                    $directorsModel -> delete($params[1], 'directorId');
                    header('Location: /dashboard/directors');
                }
            }

            /* Render page */
            $this -> render('dashboard/directors/delete', compact(
                'pageData', 'director'
            ), 'dashboard');

        } else header('Location: /dashboard/directors');
    }

    /* If route method = 'profile' */
    public function profile ($params = null) {
        $usersModel = new UsersModel();

        if (!isset($_SESSION['user'])) header('Location: /');

        /* Profile's index page */
        if (!$params) {
            $messages = json_decode(file_get_contents('src/Core/messages.json'), true);

            /* Basic's page data */
            $pageData = [
                'title' => "Profil",
                'description' => "Profil",
                'breadcrumb' => [ "Profil" ]
            ];

            /* Get connected user */
            $user = $usersModel -> read(
                criteria: [ 'userId =' => $_SESSION['user'] -> userId ],
                fetch: 'fetch'
            );

            /* Modify logic */
            $fieldsErrors = array();

            if (isset($_POST['submit'])) {
                if (empty($_POST['first-name']) || !preg_match('/^[a-zA-Z]{1,30}$/', $_POST['first-name'])) $fieldsErrors['first-name'] = $messages['messages']['error']['dashboard']['users']['add-modify']['first-name'];
                else $usersModel -> setFirstName(strip_tags($_POST['first-name']));

                if (empty($_POST['last-name']) || !preg_match('/^[a-zA-Z]{1,30}$/', $_POST['last-name'])) $fieldsErrors['last-name'] = $messages['messages']['error']['dashboard']['users']['add-modify']['last-name'];
                else $usersModel -> setLastName(strip_tags($_POST['last-name']));

                if (empty($_POST['email'])) $fieldsErrors['email'] = $messages['messages']['error']['dashboard']['users']['add-modify']['email'];
                else $usersModel -> setEmail(strip_tags($_POST['email']));

                if (!empty($_POST['phone-number'])) $usersModel -> setPhoneNumber(strip_tags($_POST['phone-number']));

                if (empty($_POST['password'])) $usersModel -> setPassword($user -> userPassword);
                else if (!empty($_POST['password'])) $usersModel -> setPassword(password_hash(strip_tags($_POST['password']), PASSWORD_BCRYPT));
                else $fieldsErrors['password'] = $messages['messages']['error']['dashboard']['users']['add-modify']['password'];

                if (!empty($_POST['profile-picture'])) $usersModel -> setProfilePicture(strip_tags($_POST['profile-picture']));

                if (!empty($_POST['birth-date'])) $usersModel -> setBirthDate(strip_tags($_POST['birth-date']));

                if (empty($fieldsErrors)) {
                    $_SESSION['message']['success'] = $messages['messages']['success']['dashboard']['modify']['profile'];
                    $usersModel -> update([ 'userId' => $_SESSION['user'] -> userId ]);
                    header('Location: /dashboard/profile');
                }
            }

            /* Render page */
            $this -> render('dashboard/profile/index', compact(
                'pageData', 'user', 'fieldsErrors'
            ), 'dashboard');
        }
    }
}