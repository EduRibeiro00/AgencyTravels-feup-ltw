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
    userID         INTEGER	PRIMARY KEY,
    name           TEXT		CONSTRAINT nn_user_name NOT NULL,
    username       TEXT		CONSTRAINT nn_user_username NOT NULL
							CONSTRAINT unique_user_username UNIQUE
                            CONSTRAINT check_username_no_spaces CHECK(username NOT LIKE '% %'),
    password       TEXT		CONSTRAINT nn_user_password NOT NULL 
                            CONSTRAINT check_password_length CHECK(length(password) > 6),
    email          TEXT		CONSTRAINT nn_user_email NOT NULL
							CONSTRAINT unique_user_email UNIQUE,
    description    TEXT,
    birthDate      DATE		CONSTRAINT nn_user_birthDate NOT NULL,
    gender         TEXT		CONSTRAINT check_user_gender CHECK (gender = "M" OR gender = "F" or gender = "O"),
    locationID     INTEGER	CONSTRAINT fk_user_locationid REFERENCES Location (locationID)  ON DELETE SET NULL
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
    locationID     INTEGER CONSTRAINT nn_place_locationid NOT NULL CONSTRAINT fk_place_locationid REFERENCES Location (locationID)  ON DELETE SET NULL
                                                                                                                                    ON UPDATE CASCADE,
    ownerID        INTEGER CONSTRAINT nn_place_ownerid NOT NULL CONSTRAINT fk_place_ownerid REFERENCES User (userID)  ON DELETE CASCADE
                                                                                                                      ON UPDATE CASCADE                                                                                  
);


-- Table: Image
DROP TABLE IF EXISTS Image;

CREATE TABLE Image (
    imageID        INTEGER PRIMARY KEY AUTOINCREMENT,
    image          TEXT CONSTRAINT nn_image NOT NULL,
    userID         INTEGER CONSTRAINT fk_image_userid REFERENCES User(userID) ON DELETE CASCADE
                                                                              ON UPDATE CASCADE
                           CONSTRAINT unique_image_userid UNIQUE,
    placeID        INTEGER CONSTRAINT fk_image_placeid REFERENCES Place(placeID) ON DELETE CASCADE
                                                                                 ON UPDATE CASCADE,
    CONSTRAINT check_image_idsxor CHECK ((userID IS NULL AND placeID IS NOT NULL) OR (userID IS NOT NULL AND placeID IS NULL))                                                                   
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
    stars           INTEGER CONSTRAINT nn_review_stars NOT NULL CONSTRAINT check_review_stars CHECK (stars >= 0 AND stars <= 5),
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
    SET rating = (SELECT avg(stars)
                  FROM Review NATURAL JOIN Reservation
                  WHERE placeID = (SELECT placeID
                                   FROM Reservation
                                   WHERE reservationID = new.reservationID))
    WHERE placeID = (SELECT placeID
                     FROM Reservation
                     WHERE reservationID = new.reservationID);
END;


CREATE TRIGGER IF NOT EXISTS AverageRatingUpdate
AFTER UPDATE OF stars ON Review
BEGIN
    UPDATE Place
    SET rating = (SELECT avg(stars)
                  FROM Review NATURAL JOIN Reservation
                  WHERE placeID = (SELECT placeID
                                   FROM Reservation
                                   WHERE reservationID = new.reservationID))
    WHERE placeID = (SELECT placeID
                     FROM Reservation
                     WHERE reservationID = new.reservationID);
END;


CREATE TRIGGER IF NOT EXISTS AverageRatingDeleteNormal
AFTER DELETE ON Review
WHEN ((SELECT count(*)
       FROM Review NATURAL JOIN Reservation
       WHERE placeID = (SELECT placeID
                        FROM Reservation
                        WHERE reservationID = old.reservationID)) > 0)
BEGIN
    UPDATE Place
    SET rating = (SELECT avg(stars)
                  FROM Review NATURAL JOIN Reservation
                  WHERE placeID = (SELECT placeID
                                   FROM Reservation
                                   WHERE reservationID = old.reservationID))
    WHERE placeID = (SELECT placeID
                     FROM Reservation
                     WHERE reservationID = old.reservationID);
END;


-- Additional delete trigger, in order to prevent division by 0
CREATE TRIGGER IF NOT EXISTS AverageRatingDeleteNoReviews
AFTER DELETE ON Review
WHEN ((SELECT count(*)
       FROM Review NATURAL JOIN Reservation
       WHERE placeID = (SELECT placeID
                        FROM Reservation
                        WHERE reservationID = old.reservationID)) = 0)
BEGIN
    UPDATE Place
    SET rating = 0
    WHERE placeID = (SELECT placeID
                     FROM Reservation
                     WHERE reservationID = old.reservationID);
END;

-----------------------------------------------------------

-- POVOATE --
-- Location
INSERT INTO Location (country, city) VALUES ('Portugal', 'Aveiro');
INSERT INTO Location (country, city) VALUES ('Portugal', 'Lisbon');
INSERT INTO Location (country, city) VALUES ('Portugal', 'Porto');
INSERT INTO Location (country, city) VALUES ('Italy', 'Rome');
INSERT INTO Location (country, city) VALUES ('Italy', 'Venice');
INSERT INTO Location (country, city) VALUES ('England', 'London');
INSERT INTO Location (country, city) VALUES ('Russia', 'Moscow');
INSERT INTO Location (country, city) VALUES ('Cambodia', 'Phnom Penh');
INSERT INTO Location (country, city) VALUES ('USA', 'NY');

-- Users
INSERT INTO User (name, username, password, email, description, birthDate, gender, locationID ) VALUES ("Ruben Almeida", "ruben", "1234567", "up201704618@fe.up.pt", "Um jovem chavale com calidades", "1999-08-25", "M", 3);
INSERT INTO User (name, username, password, email, description, birthDate, gender, locationID ) VALUES ("Eduardo Ribeiro", "eduribas", "1234567", "up201705421@fe.up.pt", "Um bom moço que oferece casa a garinas e avisa que tequilha faz mal", "1999-04-04", "M", 3);
INSERT INTO User (name, username, password, email, birthDate, gender, locationID ) VALUES ("Manuel Coutinho", "mcgano", "1234567", "isso.agora@fe.up.pt", "1969-04-20", "O", 7);
INSERT INTO User (name, username, password, email, birthDate, gender, locationID ) VALUES ("Cristiano Reinaldo", "cr72", "1234567", "melhor.do.mundo@gmail.com", "1985-02-05", "O", 2);
INSERT INTO User (name, username, password, email, description, birthDate, gender, locationID ) VALUES ("José Figueiras", "ny911", "1234567", "inocente@gmail.com", "Pessoal, eu não tive NADA a ver com o 11 de setembro. NADA!!", "2001-09-11", "M", 9);
INSERT INTO User (name, username, password, email, description, birthDate, gender, locationID ) VALUES ("Sócrates", "expm", "1234567", "inocente2@gmail.com", "Ex Primeiro Ministro, com grandes amigos", "2001-09-11", "M", 2);
INSERT INTO User (name, username, password, email, description, birthDate, gender ) VALUES ("Quota de Mulheres", "metoo", "1234567", "women.missing@gmail.com", "Entrei porque o site tinha quotas de sexo. Beijos a todos", "1987-03-08", "F");

INSERT INTO Place (title, address, description, capacity, numRooms, numBathrooms, gpsCoords, locationID, ownerID) VALUES(
	"Apartamento em Santa Maria da Feira",
	"Rua Outeiro do Moinho 355",
	"Apartamento de tipologia T3, com áreas generosas em bom estado.
	Situado a 800m do Continente.
	Bons acessos e próximo de zonas comerciais.",
	5, 3, 2,
	"40.998944, -8.556472",
	1, 3
);

INSERT INTO Place (title, address, description, capacity, numRooms, numBathrooms, gpsCoords, locationID, ownerID) VALUES(
	"Apartamento em Gondomar",
	"R. do Sestelo 571, 4435-357 Rio Tinto",
	"Apartamento incrível nesta grande terra que é Gondomar.",
	2, 1, 1,
	"41.184855, -8.565989",
	3, 1
);

INSERT INTO Place (title, address, description, capacity, numRooms, numBathrooms, gpsCoords, locationID, ownerID) VALUES(
	"Apartamento T3 c/ varanda perto da Praia da Madalena",
	"Rua Benjamim Jorge Moreira - Madalena, Arcozelo, Vila Nova de Gaia",
	"Apartamento T3 perto da Praia da Madalena
	Venha visitar esse belíssimo apartamento, com três quartos e varanda em todos eles.",
	4, 3, 2,
	"41.111655, -8.632493",
	3, 2
);

INSERT INTO Place (title, address, description, capacity, numRooms, numBathrooms, gpsCoords, locationID, ownerID) VALUES(
	"T1 perto do metro Aliados",
	"Rua do Almada, Cedofeita, Santo Ildefonso, Sé, Miragaia, São Nicolau e Vitória, Porto",
	"Apartamento T1, totalmente remodelado",
	2, 1, 1,
	"41.149643, -8.612128",
	3, 2
);

INSERT INTO Place (title, address, description, capacity, numRooms, numBathrooms, gpsCoords, locationID, ownerID) VALUES(
	"Explore the Heart of the City from a Sunny Loft",
	"Largo das Olarias 65A, 1100-376 Lisboa",
	"Get a restful night's sleep in the mezzanine bedroom and curl up next to the open shutters and let in the morning sun before heading out to explore Lisbon. 
	The space features hardwood floors and crisp, white walls creating a chic, contemporary look.",
	2, 1, 1,
	"38.716444, -9.133317",
	2, 3
);

-- Image
INSERT INTO Image(image, userID, placeID) VALUES ("14b3a43790f206aff9d6aefe11c1b05c1c55ddacfd7f1df321cf22f3da1055c8.png", 1, null);
INSERT INTO Image(image, userID, placeID) VALUES ("97c5f50dc74ef7b62f986cb977b3ee216cc60d96fb2ebba565557401e0cf47ab.png", 2, null);
INSERT INTO Image(image, userID, placeID) VALUES ("0f5ca98631a06547629225d4c77030408728b90fa29de1e592ec0f7d7c29ce82.png", 4, null);
INSERT INTO Image(image, userID, placeID) VALUES ("3f10e3e9b4e52f8422728abeb3b37c8a3d5b9e01a8426a90d95510a93bc869dc.png", 5, null);
INSERT INTO Image(image, userID, placeID) VALUES ("f4cced5a7ecd93fca8bf68c8f0e3ca72572fa0f7afaffc0c1dfb02c69f291d7e.png", 6, null);
INSERT INTO Image(image, userID, placeID) VALUES ("d41408d4c70638efd7543c63c0d087f5c8632b725bce3ff25ed76928422d0005.png", 7, null);

INSERT INTO Image(image, userID, placeID) VALUES ("cf42271a971be1ca90dbc5bf1fb0f46a5987283a8cd514a4d1e36179289dc82b.png", null, 1);
INSERT INTO Image(image, userID, placeID) VALUES ("7b9e2143552f0b3e51be3435fb15420c8505b38b1539024bd3decb40b8f3fead.png", null, 1);
INSERT INTO Image(image, userID, placeID) VALUES ("7bee48dbd6df36c8fa25e6cbb27fac8a74c197f8ea4e20bb556baa5cf8481ad7.png", null, 1);
INSERT INTO Image(image, userID, placeID) VALUES ("daf3a178871fb1b8d1886cfe566a716e9bc7a52bcf7a2c317099567335939f27.png", null, 1);
INSERT INTO Image(image, userID, placeID) VALUES ("ccdb5b1e7452aca67ec8b9a8cf337803840c34e3d4eadb83ac4afaf8c5a60636.png", null, 1);
INSERT INTO Image(image, userID, placeID) VALUES ("20ea0515aa400322540386d769c8a7721bc9c43d842492640bca4e54ac51d155.png", null, 2);
INSERT INTO Image(image, userID, placeID) VALUES ("85c2b761a3efaaa714e8e0292eb380cc338803963c0818844b736048ba20030d.png", null, 2);
INSERT INTO Image(image, userID, placeID) VALUES ("bc094ca13be37be26c7e998e54f426f2df6d73415952a11cc6a6aeaecb041ba4.png", null, 2);
INSERT INTO Image(image, userID, placeID) VALUES ("c68b017ab451b6192aa31d9a63a3be696cdde7ee7b90d33e5debf0f5f6480b06.png", null, 3);
INSERT INTO Image(image, userID, placeID) VALUES ("0da2a28287ad80ccdf45bf7956e87c2d0c6a4985e8411114cfcd6ad381a8e344.png", null, 3);
INSERT INTO Image(image, userID, placeID) VALUES ("7f58bfa4b6b071519d3dec20037845a82413c433b6562777147121220f1cd8c9.png", null, 3);
INSERT INTO Image(image, userID, placeID) VALUES ("7e934a8e1e154499db218f725be0ccfcf227fb774576d55617ffa44376b2fb47.png", null, 3);
INSERT INTO Image(image, userID, placeID) VALUES ("eabaa130a02cc242ea6357b6b0e01b75316eb19dff7fe82fb8bd179b056ffb97.png", null, 4);
INSERT INTO Image(image, userID, placeID) VALUES ("c7cbe7f93e188d568f60c3f5a788f379660ddf76dbbee51084d0dea9b5a21adc.png", null, 4);
INSERT INTO Image(image, userID, placeID) VALUES ("351b41452c16c13b63b4bea16f8cd5b3cd308f25db4e378db3d7fed3fe413b96.png", null, 5);
INSERT INTO Image(image, userID, placeID) VALUES ("c2e47b282a11900fce2b39a768787acf2a0d36e22b680efdb198d762f23567a4.png", null, 5);
INSERT INTO Image(image, userID, placeID) VALUES ("e57b4c9d03b63c54d1e51da9e19b0e0072d06b80fbcf133de27eba682057f06a.png", null, 5);
INSERT INTO Image(image, userID, placeID) VALUES ("cfa0e45a74eddc535ebb42eb70e9dadd2106c56a0b3d9150d333f66828fcb111.png", null, 5);
INSERT INTO Image(image, userID, placeID) VALUES ("8468848342de437cd15e767b424e6737ef256f7ba6e98ac2d0a6c78895ec74f0.png", null, 5);
INSERT INTO Image(image, userID, placeID) VALUES ("e98d65d945eabf82ceb2e24a7472dbcab19f9f3678790211095b28060d050875.png", null, 5);

-- Availability
INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-11-10", "2019-11-20", 50.50, 1);
INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-11-23", "2019-11-30", 45.00, 1);
INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-12-20", "2019-12-27", 75.25, 1);
INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-12-30", "2020-01-03", 99.99, 1);

INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-11-12", "2019-11-15", 20.33, 2);
INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-11-26", "2019-11-28", 23.47, 2);
INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-12-29", "2020-01-02", 34.99, 2);

INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-11-03", "2019-11-19", 43.34, 3);
INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-11-24", "2019-11-26", 42.11, 3);
INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-12-02", "2019-12-07", 57.45, 3);
INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-12-17", "2020-01-04", 67.99, 3);
INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2020-01-10", "2020-01-15", 52.89, 3);

INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-11-12", "2019-12-15", 42.00, 4);
INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-12-16", "2020-01-10", 56.00, 4);

INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-11-01", "2019-11-29", 37.73, 5);
INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-12-01", "2019-12-06", 38.83, 5);
INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-12-08", "2019-12-13", 46.64, 5);
INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-12-15", "2019-12-22", 48.84, 5);
INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-12-23", "2019-12-28", 52.25, 5);
INSERT INTO Availability (startDate, endDate, pricePerDay, placeID) VALUES ("2019-12-29", "2020-01-03", 59.99, 5);

-- Reservation
INSERT INTO Reservation (startDate, endDate, price, placeID, touristID) VALUES ("2019-11-25", "2019-11-28", 102, 1, 2);
INSERT INTO Reservation (startDate, endDate, price, placeID, touristID) VALUES ("2019-12-01", "2019-12-07", 475, 1, 5);
INSERT INTO Reservation (startDate, endDate, price, placeID, touristID) VALUES ("2019-12-10", "2019-12-17", 475, 1, 6);
INSERT INTO Reservation (startDate, endDate, price, placeID, touristID) VALUES ("2019-12-28", "2019-12-29", 190, 1, 7);

INSERT INTO Reservation (startDate, endDate, price, placeID, touristID) VALUES ("2019-11-06", "2019-11-10", 81.20, 2, 3);
INSERT INTO Reservation (startDate, endDate, price, placeID, touristID) VALUES ("2019-11-16", "2019-11-23", 163.29, 2, 5);
INSERT INTO Reservation (startDate, endDate, price, placeID, touristID) VALUES ("2019-12-01", "2019-12-21", 500.50 , 2, 2);
INSERT INTO Reservation (startDate, endDate, price, placeID, touristID) VALUES ("2019-12-23", "2019-12-27", 174.95 , 2, 4);

INSERT INTO Reservation (startDate, endDate, price, placeID, touristID) VALUES ("2019-11-21", "2019-11-22", 43.34, 3, 1);
INSERT INTO Reservation (startDate, endDate, price, placeID, touristID) VALUES ("2019-11-28", "2019-12-01", 154.03, 3, 7);
INSERT INTO Reservation (startDate, endDate, price, placeID, touristID) VALUES ("2019-12-09", "2019-12-16", 429.59, 3, 5);
INSERT INTO Reservation (startDate, endDate, price, placeID, touristID) VALUES ("2019-01-05", "2019-01-08", 158.67, 3, 6);

INSERT INTO Reservation (startDate, endDate, price, placeID, touristID) VALUES ("2019-11-04", "2019-11-10", 252, 4, 4);
INSERT INTO Reservation (startDate, endDate, price, placeID, touristID) VALUES ("2018-12-04", "2018-12-10", 1234, 4, 3);
INSERT INTO Reservation (startDate, endDate, price, placeID, touristID) VALUES ("2018-10-03", "2018-11-11", 81231, 4, 1);
INSERT INTO Reservation (startDate, endDate, price, placeID, touristID) VALUES ("2018-06-01", "2018-06-12", 123.11, 4, 2);

-- Review
INSERT INTO Review (date, stars, reservationID) VALUES ("2019-11-25", 4, 1);
INSERT INTO Review (comment, date, stars, reservationID) VALUES ("Lindo", "2019-12-21", 5, 2);
INSERT INTO Review (comment, date, stars, reservationID) VALUES ("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.", "2019-12-19", 3, 3);
INSERT INTO Review (date, stars, reservationID) VALUES ("2020-01-04", 4, 4);

INSERT INTO Review (comment, date, stars, reservationID) VALUES ("Corresponde às expetativas", "2019-11-13", 5, 5);
INSERT INTO Review (comment, date, stars, reservationID) VALUES ("Se desse dava 6 estrelas", "2019-12-02", 5, 6);
INSERT INTO Review (date, stars, reservationID) VALUES ("2018-01-10", 5, 8);

INSERT INTO Review (date, stars, reservationID) VALUES ("2019-11-23", 3, 9);
INSERT INTO Review (comment, date, stars, reservationID) VALUES ("O pior sitio em que já fiquei. Não recomendo", "2019-12-18", 1, 11);
INSERT INTO Review (comment, date, stars, reservationID) VALUES ("O pior sitio em que já fiquei. Não recomendo", "2019-12-18", 1, 12);
INSERT INTO Review (comment, date, stars, reservationID) VALUES ("O pior sitio em que já fiquei. Não recomendo", "2019-12-18", 1, 13);
INSERT INTO Review (comment, date, stars, reservationID) VALUES ("O pior sitio em que já fiquei. Não recomendo", "2019-12-18", 1, 14);
INSERT INTO Review (comment, date, stars, reservationID) VALUES ("O pior sitio em que já fiquei. Não recomendo", "2019-12-18", 1, 10);

-- Reply
INSERT INTO Reply (comment, date, reviewID, userID) VALUES ("Ainda bem que gostou", "2019-12-21", 2, 3);
INSERT INTO Reply (comment, date, reviewID, userID) VALUES ("Vens me aqui falar não sei o quê e ainda me dás 3 estrelas fdp?? Nunca mais ficas na minha casa", "2019-12-20", 3, 3);
INSERT INTO Reply (comment, date, reviewID, userID) VALUES ("O que poderia ter sido melhor? Deixaram me a casa de pantanas e nem multa cobrei. Nunca mais vem cá ninguém no ano novo", "2020-01-06", 4, 3);
INSERT INTO Reply (comment, date, reviewID, userID) VALUES ("Calma pessoal lembrem-se que eu nada tive a ver com o 11 de setembro", "2019-12-23", 3, 5);

INSERT INTO Reply (comment, date, reviewID, userID) VALUES ("Pois se eu tivesse de viver num cúbiculo na prisão qd chegasse aqui tbm acharia luxo", "2019-12-04", 6, 4);

INSERT INTO Reply (comment, date, reviewID, userID) VALUES ("Peço imensa desculpa pelo inconveniente", "2019-12-25", 9, 2);
INSERT INTO Reply (comment, date, reviewID, userID) VALUES ("Fiquei e não me meteu assim tanta impressão. Depois são as 'gajas' que são esquisitas", "2019-12-22", 7, 2);

---------------------

-- Triggers that verify if the Reply date is more recent than the Review date (not likely)
CREATE TRIGGER IF NOT EXISTS VerifyDateReviewReplyInsert
BEFORE INSERT ON Reply
WHEN (new.date < (SELECT date
                  FROM Review
                  WHERE reviewID = new.reviewID))
BEGIN
    SELECT RAISE(ROLLBACK, 'Error in reply insertion - invalid date');
END;

CREATE TRIGGER IF NOT EXISTS VerifyDateReviewReplyUpdate
BEFORE UPDATE OF date ON Reply
WHEN (new.date < (SELECT date
                  FROM Review
                  WHERE reviewID = new.reviewID))
BEGIN
    SELECT RAISE(ROLLBACK, 'Error in reply update - invalid date');
END;

CREATE TRIGGER IF NOT EXISTS VerifyDateReviewUpdateReply
BEFORE UPDATE OF date ON Review
WHEN (new.date > (SELECT date
                  FROM Reply
                  WHERE reviewID = new.reviewID))
BEGIN
    SELECT RAISE(ROLLBACK, 'Error in review update - invalid date');
END;

---------------------

-- Triggers to check if a reservation is coincident with an availability
CREATE TRIGGER IF NOT EXISTS CheckReservationAvailabilityInsert
BEFORE INSERT ON Reservation
WHEN (NOT EXISTS (SELECT *
                  From Availability WHERE ((placeID = new.placeID)
                  AND ((new.startDate >= startDate) AND (new.startDate < endDate)
                    AND (new.endDate > startDate) AND (new.endDate <= endDate)))))
BEGIN
    SELECT RAISE(rollback,'Error in reservation insert - no availability for dates');
END;

CREATE TRIGGER IF NOT EXISTS CheckReservationAvailabilityUpdateStart
BEFORE UPDATE OF startDate ON Reservation
WHEN (NOT EXISTS (SELECT *
                  From Availability WHERE ((placeID = new.placeID)
                  AND ((new.startDate >= startDate) AND (new.startDate < endDate)
                    AND (new.endDate > startDate) AND (new.endDate <= endDate)))))
BEGIN
    SELECT RAISE(rollback,'Error in reservation update - no availability for dates');
END;

CREATE TRIGGER IF NOT EXISTS CheckReservationAvailabilityUpdateEnd
BEFORE UPDATE OF endDate ON Reservation
WHEN (NOT EXISTS (SELECT *
                  From Availability WHERE ((placeID = new.placeID)
                  AND ((new.startDate >= startDate) AND (new.startDate < endDate)
                    AND (new.endDate > startDate) AND (new.endDate <= endDate)))))
BEGIN
    SELECT RAISE(rollback,'Error in reservation update - no availability for dates');
END;


CREATE TRIGGER IF NOT EXISTS CheckAvailabilityUpdateStart
BEFORE UPDATE OF startDate ON Availability
WHEN (EXISTS (SELECT *
              From Reservation WHERE ((placeID = new.placeID)
              AND NOT ((new.startDate <= startDate) AND (new.startDate < endDate)
              AND (new.endDate > startDate) AND (new.endDate >= endDate)))))
BEGIN
    SELECT RAISE(rollback,'Error in availability update - invalid dates for reservations');
END;


CREATE TRIGGER IF NOT EXISTS CheckAvailabilityUpdateStart
BEFORE UPDATE OF endDate ON Availability
WHEN (EXISTS (SELECT *
              From Reservation WHERE ((placeID = new.placeID)
              AND NOT ((new.startDate <= startDate) AND (new.startDate < endDate)
              AND (new.endDate > startDate) AND (new.endDate >= endDate)))))
BEGIN
    SELECT RAISE(rollback,'Error in availability update - invalid dates for reservations');
END;


---------------------

-- Triggers to check date collisions on Reservation inserts and updates
CREATE TRIGGER IF NOT EXISTS CheckReservationDataCollisionInsert
BEFORE INSERT ON Reservation
WHEN (EXISTS (SELECT *
                From Reservation
                WHERE ((placeID = new.placeID) AND
                (new.startDate < endDate
                AND new.endDate > startDate))))
BEGIN
    SELECT RAISE(rollback,'Error in reservation insert - invalid date');
END;

CREATE TRIGGER IF NOT EXISTS CheckReservationDataCollisionUpdateStart
BEFORE UPDATE OF startDate ON Reservation
WHEN (EXISTS (SELECT *
                From Reservation
                WHERE ((placeID = new.placeID) AND
                (new.startDate < endDate
                AND new.endDate > startDate) AND (reservationID <> new.reservationID))))
BEGIN
    SELECT RAISE(rollback,'Error in reservation update - invalid date');
END;

CREATE TRIGGER IF NOT EXISTS CheckReservationDataCollisionUpdateEnd
BEFORE UPDATE OF endDate ON Reservation
WHEN (EXISTS (SELECT *
                From Reservation
                WHERE ((placeID = new.placeID) AND
                (new.startDate < endDate
                AND new.endDate > startDate) AND (reservationID <> new.reservationID))))
BEGIN
    SELECT RAISE(rollback,'Error in reservation update - invalid date');
END;
---------------------

-- Triggers that verify Availability collisions
CREATE TRIGGER IF NOT EXISTS CheckAvailabilityDataCollisionInsert
BEFORE INSERT ON Availability
WHEN (EXISTS (SELECT *
                From Availability
                WHERE( placeID = new.placeID
                AND new.startDate <= endDate
                AND new.endDate >= startDate)))
BEGIN
    SELECT RAISE(rollback,'Error in availability insert - invalid date');
END;

CREATE TRIGGER IF NOT EXISTS CheckAvailabilityDataCollisionUpdateStart
BEFORE UPDATE OF startDate ON Availability
WHEN (EXISTS (SELECT *
                From Availability
                WHERE( placeID = new.placeID 
                AND availabilityID <> new.availabilityID 
                AND new.startDate <= endDate
                AND new.endDate >= startDate)))
BEGIN
    SELECT RAISE(rollback,'Error in availability update - invalid date');
END;

CREATE TRIGGER IF NOT EXISTS CheckAvailabilityDataCollisionUpdateEnd
BEFORE UPDATE OF endDate ON Availability
WHEN (EXISTS (SELECT *
                From Availability
                WHERE( placeID = new.placeID
                AND availabilityID <> new.availabilityID
                AND new.startDate <= endDate
                AND new.endDate >= startDate)))
BEGIN
    SELECT RAISE(rollback,'Error in availability update - invalid date');
END;

---------------------

-- View for the top destinations of all time
CREATE VIEW IF NOT EXISTS TopDestinations
AS SELECT locationID, country, city, count(reservationID) as numReservations
   FROM Location NATURAL JOIN Place NATURAL JOIN Reservation
   GROUP BY locationID
   ORDER BY numReservations DESC;


-- View for the current trending destinations (top destinations of the last month)
CREATE VIEW IF NOT EXISTS TrendingDestinations
AS SELECT locationID, country, city, count(reservationID) as numReservations
   FROM Location NATURAL JOIN Place NATURAL JOIN Reservation
   WHERE startDate >= datetime('now', 'start of month')
   GROUP BY locationID
   ORDER BY numReservations DESC;


COMMIT TRANSACTION;
PRAGMA foreign_keys = on;