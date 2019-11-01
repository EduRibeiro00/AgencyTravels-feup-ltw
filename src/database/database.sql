PRAGMA foreign_keys = on;
BEGIN TRANSACTION;

-- Table: Location
DROP TABLE IF EXISTS Location;

CREATE TABLE Location (
    locationID     INTEGER PRIMARY KEY,
    country        TEXT    CONSTRAINT nn_location_country NOT NULL,
    city           TEXT    CONSTRAINT nn_location_city NOT NULL
);

-- Table: User
DROP TABLE IF EXISTS User;

CREATE TABLE User (
    userID         INTEGER PRIMARY KEY,
    name           TEXT    CONSTRAINT nn_user_name NOT NULL,
    username       TEXT    CONSTRAINT nn_user_username NOT NULL,
    password       TEXT    CONSTRAINT nn_user_password NOT NULL,
    email          TEXT    CONSTRAINT nn_user_email NOT NULL,
    description    TEXT,
    birthDate      DATE    CONSTRAINT nn_user_birthDate NOT NULL,
    gender         TEXT    CONSTRAINT check_user_gender CHECK (gender = "M" OR gender = "F"),
    locationID     INTEGER CONSTRAINT fk_user_locationid REFERENCES Location (locationID)  ON DELETE SET NULL
                                                                                           ON UPDATE CASCADE
);

-- Table: Place
DROP TABLE IF EXISTS Place;

CREATE TABLE Place (
    placeID        INTEGER PRIMARY KEY,
    title          TEXT    CONSTRAINT nn_place_title NOT NULL,
    rating         REAL    DEFAULT 0 CONSTRAINT check_place_rating CHECK (rating >= 0 AND rating <= 5),
    address        TEXT    CONSTRAINT nn_place_address NOT NULL,
    description    TEXT    CONSTRAINT nn_place_description NOT NULL,
    capacity       INTEGER CONSTRAINT nn_place_capacity NOT NULL CONSTRAINT check_place_capacity CHECK (capacity > 0),
    numRooms       INTEGER CONSTRAINT nn_place_numRooms NOT NULL CONSTRAINT check_place_numRooms CHECK (numRooms > 0),
    numBathrooms   INTEGER CONSTRAINT nn_place_numBathrooms NOT NULL CONSTRAINT check_place_numBathrooms CHECK (numBathrooms > 0),
    gpsCoords      TEXT    CONSTRAINT nn_place_gpscoords NOT NULL,
    locationID     INTEGER CONSTRAINT fk_place_locationid REFERENCES Location (locationID)  ON DELETE SET NULL
                                                                                            ON UPDATE CASCADE,
    ownerID        INTEGER CONSTRAINT nn_place_ownerid NOT NULL CONSTRAINT fk_place_ownerid REFERENCES User (userID)  ON DELETE CASCADE
                                                                                                                      ON UPDATE CASCADE                                                                                  
);


-- Table: Image
DROP TABLE IF EXISTS Image;

CREATE TABLE Image (
    imageID        INTEGER PRIMARY KEY,
    image          TEXT    CONSTRAINT nn_image_image NOT NULL,
    userID         INTEGER CONSTRAINT fk_image_userid REFERENCES User(userID) ON DELETE SET NULL
                                                                              ON UPDATE CASCADE
                           CONSTRAINT unique_image_userid UNIQUE,
    placeID        INTEGER CONSTRAINT fk_image_placeid REFERENCES Place(placeID) ON DELETE SET NULL
                                                                                 ON UPDATE CASCADE
);


-- Table: Availability
DROP TABLE IF EXISTS Availability;

CREATE TABLE Availability (
    availabilityID      INTEGER PRIMARY KEY,
    startDate           DATE CONSTRAINT nn_availability_startdate NOT NULL,              
    endDate             DATE CONSTRAINT nn_availability_enddate NOT NULL,
    pricePerDay         REAL CONSTRAINT nn_availability_priceperday NOT NULL CONSTRAINT check_availability_priceperday CHECK (pricePerDay > 0),
    placeID             INTEGER CONSTRAINT nn_availability_placeid NOT NULL CONSTRAINT fk_availability_placeid REFERENCES Place (placeID)  ON DELETE CASCADE
                                                                                                                                           ON UPDATE CASCADE,
    CONSTRAINT  check_availability_dates CHECK (endDate > startDate)
);


-- Table: Reservation
DROP TABLE IF EXISTS Reservation;

CREATE TABLE Reservation (
    reservationID      INTEGER PRIMARY KEY,
    startDate          DATE CONSTRAINT nn_reservation_startdate NOT NULL,              
    endDate            DATE CONSTRAINT nn_reservation_enddate NOT NULL,
    price              REAL CONSTRAINT check_reservation_price CHECK (price > 0),
    placeID            INTEGER CONSTRAINT nn_reservation_placeid NOT NULL CONSTRAINT fk_reservation_placeid REFERENCES Place (placeID)  ON DELETE CASCADE
                                                                                                                                        ON UPDATE CASCADE,
    touristID          INTEGER CONSTRAINT nn_reservation_touristid NOT NULL CONSTRAINT fk_reservation_touristid REFERENCES User (userID) ON DELETE CASCADE
                                                                                                                                         ON UPDATE CASCADE,
    CONSTRAINT  check_reservation_dates CHECK (endDate > startDate)
);


-- Table: Review
DROP TABLE IF EXISTS Review;

CREATE TABLE Review (
    reviewID        INTEGER PRIMARY KEY,
    comment         TEXT,
    date            DATE CONSTRAINT nn_review_date NOT NULL,
    stars           INTEGER CONSTRAINT nn_review_stars NOT NULL CONSTRAINT check_review_stars CHECK (stars >= 0 AND starts <= 5),
    reservationID   INTEGER CONSTRAINT fk_review_reservationid REFERENCES Reservation(reservationID) ON DELETE CASCADE
                                                                                                     ON UPDATE CASCADE
                            CONSTRAINT unique_review_reservationid UNIQUE CONSTRAINT nn_review_reservationid NOT NULL

);

-- Table: Reply
DROP TABLE IF EXISTS Reply;

CREATE TABLE Reply (
    replyID        INTEGER PRIMARY KEY,
    comment        TEXT CONSTRAINT nn_reply_comment NOT NULL,
    date           DATE CONSTRAINT nn_reply_date NOT NULL,
    reviewID       INTEGER CONSTRAINT fk_reply_reviewid REFERENCES Review(reviewID) ON DELETE CASCADE
                                                                                    ON UPDATE CASCADE
                           CONSTRAINT nn_reply_reviewid NOT NULL,
    userID         INTEGER CONSTRAINT fk_reply_userid REFERENCES User(userID) ON DELETE CASCADE
                                                                              ON UPDATE CASCADE
                           CONSTRAINT nn_reply_userid NOT NULL
);


-----------------------------------------------------------
-- Triggers for the correct operation of the database

-- Triggers that update the rating of a place based on the average of the stars in all reviews
CREATE TRIGGER IF NOT EXISTS AverageRatingInsert
AFTER INSERT ON Review
BEGIN
    UPDATE Place
    SET rating = (SELECT sum(stars)
                  FROM Review NATURAL JOIN Reservation
                  WHERE placeID = (SELECT placeID
                                   FROM Review NATURAL JOIN Reservation
                                   WHERE reviewID = new.reviewID))
                 /
                 (SELECT count(*)
                  FROM Review NATURAL JOIN Reservation
                  WHERE placeID = (SELECT placeID
                                   FROM Review NATURAL JOIN Reservation
                                   WHERE reviewID = new.reviewID))
    WHERE placeID = (SELECT placeID
                     FROM Review NATURAL JOIN Reservation
                     WHERE reviewID = new.reviewID);
END;


CREATE TRIGGER IF NOT EXISTS AverageRatingUpdate
AFTER UPDATE OF stars ON Review
BEGIN
    UPDATE Place
    SET rating = (SELECT sum(stars)
                  FROM Review NATURAL JOIN Reservation
                  WHERE placeID = (SELECT placeID
                                   FROM Review NATURAL JOIN Reservation
                                   WHERE reviewID = new.reviewID))
                 /
                 (SELECT count(*)
                  FROM Review NATURAL JOIN Reservation
                  WHERE placeID = (SELECT placeID
                                   FROM Review NATURAL JOIN Reservation
                                   WHERE reviewID = new.reviewID))
    WHERE placeID = (SELECT placeID
                     FROM Review NATURAL JOIN Reservation
                     WHERE reviewID = new.reviewID);
END;


CREATE TRIGGER IF NOT EXISTS AverageRatingDeleteNormal
AFTER DELETE ON Review
WHEN ((SELECT count(*)
       FROM Review NATURAL JOIN Reservation
       WHERE placeID = (SELECT placeID
                        FROM Review NATURAL JOIN Reservation
                        WHERE reviewID = new.reviewID)) > 0)
BEGIN
    UPDATE Place
    SET rating = (SELECT sum(stars)
                  FROM Review NATURAL JOIN Reservation
                  WHERE placeID = (SELECT placeID
                                   FROM Review NATURAL JOIN Reservation
                                   WHERE reviewID = new.reviewID))
                 /
                 (SELECT count(*)
                  FROM Review NATURAL JOIN Reservation
                  WHERE placeID = (SELECT placeID
                                   FROM Review NATURAL JOIN Reservation
                                   WHERE reviewID = new.reviewID))
    WHERE placeID = (SELECT placeID
                     FROM Review NATURAL JOIN Reservation
                     WHERE reviewID = new.reviewID);
END;


-- Additional delete trigger, in order to prevent division by 0
CREATE TRIGGER IF NOT EXISTS AverageRatingDeleteNoReviews
AFTER DELETE ON Review
WHEN ((SELECT count(*)
       FROM Review NATURAL JOIN Reservation
       WHERE placeID = (SELECT placeID
                        FROM Review NATURAL JOIN Reservation
                        WHERE reviewID = new.reviewID)) = 0)
BEGIN
    UPDATE Place
    SET rating = 0
    WHERE placeID = (SELECT placeID
                     FROM Review NATURAL JOIN Reservation
                     WHERE reviewID = new.reviewID);
END;

COMMIT TRANSACTION;
PRAGMA foreign_keys = on;