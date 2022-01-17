SET default_storage_engine = InnoDB;

CREATE TABLE users (
    userId integer NOT NULL AUTO_INCREMENT,
    userFirstName varchar(30) NOT NULL,
    userLastName varchar(30) NOT NULL,
    userEmail varchar(255) NOT NULL,
    userPhoneNumber varchar(10),
    userBirthDate date,
    userPassword varchar(60) NOT NULL,
    userProfilePicture varchar(255),
    userRole enum('admin', 'client') NOT NULL DEFAULT 'client',
    PRIMARY KEY (userId)
);

CREATE TABLE subscriptions (
    subscriptionId integer NOT NULL AUTO_INCREMENT,
    subscriptionName varchar(255) NOT NULL,
    subscriptionDescription text,
    subscriptionPricing double NOT NULL DEFAULT 0,
    PRIMARY KEY (subscriptionId)
);

CREATE TABLE directors (
    directorId integer NOT NULL AUTO_INCREMENT,
    directorName varchar(255) NOT NULL,
    directorPicture varchar(255) NOT NULL,
    PRIMARY KEY (directorId)
);

CREATE TABLE movies (
    movieId integer NOT NULL AUTO_INCREMENT,
    movieTitle varchar(255) NOT NULL,
    moviePoster varchar(255) NOT NULL,
    movieReleaseDate date NOT NULL,
    director_id integer NOT NULL,
    movieSynopsis text NOT NULL,
    movieDuration smallint NOT NULL,
    movieFirstScreeningDate date NOT NULL,
    movieLastScreeningDate date NOT NULL,
    movieClassification enum('non-classe', 'tous-publics', 'moins-12', 'moins-16', 'moins-18', 'moins-18-x') NOT NULL DEFAULT 'non-classe',
    PRIMARY KEY (movieId),
    FOREIGN KEY(director_id) REFERENCES directors(directorId)
);

CREATE TABLE moviesRooms (
    movieRoomId integer NOT NULL AUTO_INCREMENT,
    movieRoomName varchar(255) NOT NULL,
    movieRoomDescription text,
    PRIMARY KEY (movieRoomId)
);

CREATE TABLE screenings (
    screeningId integer NOT NULL AUTO_INCREMENT,
    movie_id integer NOT NULL,
    movieRoom_id integer NOT NULL,
    screeningDate date NOT NULL,
    screeningTime time NOT NULL,
    PRIMARY KEY (screeningId),
    FOREIGN KEY (movie_id) REFERENCES movies(movieId),
    FOREIGN KEY (movieRoom_id) REFERENCES moviesRooms(movieRoomId)
);

CREATE TABLE seats (
    seatId integer NOT NULL AUTO_INCREMENT,
    movieRoom_id integer NOT NULL,
    seatX_position integer NOT NULL,
    seatY_position integer NOT NULL,
    PRIMARY KEY (seatId),
    FOREIGN KEY (movieRoom_id) REFERENCES moviesRooms(movieRoomId)
);

CREATE TABLE pricings (
    pricingId integer NOT NULL AUTO_INCREMENT,
    pricingName varchar(255) NOT NULL,
    pricingDescription varchar(255),
    pricing double NOT NULL,
    PRIMARY KEY (pricingId)
);

CREATE TABLE users_subscriptions (
    user_id integer NOT NULL,
    subscription_id integer NOT NULL,
    remainingPlaces integer NOT NULL DEFAULT 0,
    points integer NOT NULL DEFAULT 0,
    PRIMARY KEY (user_id, subscription_id),
    FOREIGN KEY (user_id) REFERENCES users(userId),
    FOREIGN KEY (subscription_id) REFERENCES subscriptions(subscriptionId)
);

CREATE TABLE reservations (
    reservationId integer NOT NULL AUTO_INCREMENT,
    user_id integer NOT NULL,
    screening_id integer NOT NULL,
    seat_id integer NOT NULL,
    pricing_id integer NOT NULL,
    reservationTimestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (reservationId),
    FOREIGN KEY (user_id) REFERENCES users(userId),
    FOREIGN KEY (screening_id) REFERENCES screenings(screeningId),
    FOREIGN KEY (seat_id) REFERENCES seats(seatId),
    FOREIGN KEY (pricing_id) REFERENCES pricings(pricingId)
);