<?php

function format_date($date, $format) {
    if ($format === 'display-slash') return date_format(date_create($date), 'd/m/Y');
    else if ($format === 'display-dash') return date_format(date_create($date), 'd-m-Y');
    else if ($format === 'sql') return date_format(date_create($date), 'Y-m-d');
    else return null;
}

function format_duration($duration) {
    return floor($duration / 60) . "h " . $duration % 60 . "min";
    // TODO Ajouter 0 au minutes
}

function movie_classification($classification) {
    switch ($classification) {
        case 'non-classe':
            return "Non classé";
        case 'tous-publics':
            return "Tous publics";
        case 'moins-12':
            return "-12 ans";
        case 'moins-16':
            return "-16 ans";
        case 'moins-18':
            return "-18 ans";
        case 'moins-18-x':
            return "-18 ans (classé X)";
    }
}