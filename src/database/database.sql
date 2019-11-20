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
							CONSTRAINT unique_user_username UNIQUE,
    password       TEXT		CONSTRAINT nn_user_password NOT NULL,
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
    imageID        INTEGER PRIMARY KEY,
    image          TEXT    CONSTRAINT nn_image_image NOT NULL,
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
INSERT INTO User (name, username, password, email, description, birthDate, gender, locationID ) VALUES ("Ruben Almeida", "ruben", "1234", "up201704618@fe.up.pt", "Um jovem chavale com calidades", "1999-08-25", "M", 3);
INSERT INTO User (name, username, password, email, description, birthDate, gender, locationID ) VALUES ("Eduardo Ribeiro", "eduribas", "1234", "up201705421@fe.up.pt", "Um bom moço que oferece casa a garinas e avisa que tequilha faz mal", "1999-04-04", "M", 3);
INSERT INTO User (name, username, password, email, birthDate, gender, locationID ) VALUES ("Manuel Coutinho", "mcgano", "1234", "isso.agora@fe.up.pt", "1969-04-20", "O", 7);
INSERT INTO User (name, username, password, email, birthDate, gender, locationID ) VALUES ("Cristiano Reinaldo", "cr72", "1234", "melhor.do.mundo@gmail.com", "1985-02-05", "O", 2);
INSERT INTO User (name, username, password, email, description, birthDate, gender, locationID ) VALUES ("José Figueiras", "ny911", "1234", "inocente@gmail.com", "Pessoal, eu não tive NADA a ver com o 11 de setembro. NADA!!", "2001-09-11", "M", 9);
INSERT INTO User (name, username, password, email, description, birthDate, gender, locationID ) VALUES ("Sócrates", "expm", "1234", "inocente2@gmail.com", "Ex Primeiro Ministro, com grandes amigos", "2001-09-11", "M", 2);
INSERT INTO User (name, username, password, email, description, birthDate, gender ) VALUES ("Quota de Mulheres", "metoo", "1234", "women.missing@gmail.com", "Entrei porque o site tinha quotas de sexo. Beijos a todos", "1987-03-08", "F");

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
INSERT INTO Image(image, userID, placeID) VALUES ("https://avatars3.githubusercontent.com/u/48838522?s=400&v=4", 1, null);
INSERT INTO Image(image, userID, placeID) VALUES ("https://avatars2.githubusercontent.com/u/38781152?s=400&v=4", 2, null);
INSERT INTO Image(image, userID, placeID) VALUES ("http://www.risadas.pt/img/videos/06/cristiano_ronaldo_e_cristiano_reinaldo_o_melhor_jogador_do_m_img_1006.jpg", 4, null);
INSERT INTO Image(image, userID, placeID) VALUES ("https://thumbs.web.sapo.io/?W=800&H=0&delay_optim=1&epic=N2U3X7hhyJSuXSDaiqdMBVRCLvdUlnoFz3od/3gbpzNUcPuXtvaWTZ+FCsxxn2MzdMEcGbFoGwsqTYiR7Ar1mTJm9TTDGM5Ewa+RbP3TNzbObCM=", 5, null);
INSERT INTO Image(image, userID, placeID) VALUES ("http://2.bp.blogspot.com/-bKazzdWVNbQ/VRNCn0rFLOI/AAAAAAAAJBc/NEkaSqRrC6c/s1600/socrates.jpg", 6, null);
INSERT INTO Image(image, userID, placeID) VALUES ("https://i.kym-cdn.com/news_feeds/icons/mobile/000/015/652/4b1.jpg", 7, null);

INSERT INTO Image(image, userID, placeID) VALUES ("https://media8.era.pt/apartamento-t3-santa-maria-da-feira-mozelos_6_1_919572_13408665_8_1_SezzmuO6oVgDrR%2fyaIIxvvFvM%2fmtaiiZANnij5GyVWTEB8h4ZPpqcw%3d%3d", null, 1);
INSERT INTO Image(image, userID, placeID) VALUES ("https://media8.era.pt/apartamento-t3-santa-maria-da-feira-mozelos_6_1_919572_13408664_8_1_gz3rg2Te2VGKE3R570SvqorPFhSv262LBKQzpyZmTrXEB8h4ZPpqcw%3d%3d", null, 1);
INSERT INTO Image(image, userID, placeID) VALUES ("https://media8.era.pt/apartamento-t3-santa-maria-da-feira-mozelos_6_1_919572_13408657_8_1_cMdMO5ArfMZrFdudVF6h6myosfT4i2RE%2b0nDT4e76UPEB8h4ZPpqcw%3d%3d", null, 1);
INSERT INTO Image(image, userID, placeID) VALUES ("https://media8.era.pt/apartamento-t3-santa-maria-da-feira-mozelos_6_1_919572_13408668_8_1_3vG%2bk15Ga9qNkzqMcXSeQF0cfspNODgMf86X7vp8hFvEB8h4ZPpqcw%3d%3d", null, 1);
INSERT INTO Image(image, userID, placeID) VALUES ("https://media8.era.pt/apartamento-t3-santa-maria-da-feira-mozelos_6_1_919572_13408659_8_1_tG9WXNC74B6H2JcR5wgeFEhw18wNrs%2blC82dePhTIxPEB8h4ZPpqcw%3d%3d", null, 1);
INSERT INTO Image(image, userID, placeID) VALUES ("https://media7.era.pt/apartamento-t1-gondomar-rio-tinto-centro_6_1_1049728_13409479_7_1_Nn6tmBm1jE7I2r0wWW%2bEaQn687wo%2b8IoHVk5%2bSaiRZTEB8h4ZPpqcw%3d%3d", null, 2);
INSERT INTO Image(image, userID, placeID) VALUES ("https://media7.era.pt/apartamento-t1-gondomar-rio-tinto-centro_6_1_1049728_13409481_7_1_hN8M4Tbpk5y0X1a26WNTyRgXb4yjEyMTrq%2bGYals8fTEB8h4ZPpqcw%3d%3d", null, 2);
INSERT INTO Image(image, userID, placeID) VALUES ("https://media7.era.pt/apartamento-t1-gondomar-rio-tinto-centro_6_1_1049728_13409482_7_1_6KaceUrX7Mo8fil79OlQQ6X4FX9Gbb8NWTX1lAx08irEB8h4ZPpqcw%3d%3d", null, 2);
INSERT INTO Image(image, userID, placeID) VALUES ("https://apollo-ireland.akamaized.net/v1/files/eyJmbiI6ImZkYmdsYXMwNXNwdC1BUFQiLCJ3IjpbeyJmbiI6IjRqYWZwbDloM2ZndC1BUFQiLCJzIjoiMTQiLCJwIjoiMTAsLTEwIiwiYSI6IjAifV19.Q73gr1IaNLcLZ--VCSzAoiINvs9UMBaCdmQDwz20xS0/image;s=1280x1024;q=80", null, 3);
INSERT INTO Image(image, userID, placeID) VALUES ("https://apollo-ireland.akamaized.net/v1/files/eyJmbiI6Imgyamh1emV2MXV1bTEtQVBUIiwidyI6W3siZm4iOiI0amFmcGw5aDNmZ3QtQVBUIiwicyI6IjE0IiwicCI6IjEwLC0xMCIsImEiOiIwIn1dfQ.L6uUEMADPw1Vontucoxm-oEtXDFGbx1_9RAg6vJFR5o/image;s=1280x1024;q=80", null, 3);
INSERT INTO Image(image, userID, placeID) VALUES ("https://apollo-ireland.akamaized.net/v1/files/eyJmbiI6IndsNG85c2RqY2w5ZTItQVBUIiwidyI6W3siZm4iOiI0amFmcGw5aDNmZ3QtQVBUIiwicyI6IjE0IiwicCI6IjEwLC0xMCIsImEiOiIwIn1dfQ.ZJhzvcxhkKkEPTNfooogpuNQ4GiXiCwl75npe8li0OE/image;s=1280x1024;q=80", null, 3);
INSERT INTO Image(image, userID, placeID) VALUES ("https://apollo-ireland.akamaized.net/v1/files/eyJmbiI6ImdhM3UzYzR1dW80bzItQVBUIiwidyI6W3siZm4iOiI0amFmcGw5aDNmZ3QtQVBUIiwicyI6IjE0IiwicCI6IjEwLC0xMCIsImEiOiIwIn1dfQ.C2elMmgw3wi1X5AX8Mtmizmzg0OsaIFY32yfCukf-Rc/image;s=1280x1024;q=80", null, 3);
INSERT INTO Image(image, userID, placeID) VALUES ("https://apollo-ireland.akamaized.net/v1/files/eyJmbiI6IjdtNzh2aG9xcGJrcTItQVBUIiwidyI6W3siZm4iOiI0amFmcGw5aDNmZ3QtQVBUIiwicyI6IjE0IiwicCI6IjEwLC0xMCIsImEiOiIwIn1dfQ.-zquVlXp0j3SJ6b8TdKQbuqiO78yXi8kww3BY-WOUuA/image;s=1280x1024;q=80", null, 4);
INSERT INTO Image(image, userID, placeID) VALUES ("https://apollo-ireland.akamaized.net/v1/files/eyJmbiI6ImtzY3lkMTM2MDlucDMtQVBUIiwidyI6W3siZm4iOiI0amFmcGw5aDNmZ3QtQVBUIiwicyI6IjE0IiwicCI6IjEwLC0xMCIsImEiOiIwIn1dfQ.yFZbmETjc5PSsXDKLfVqVb-2t2tXpItFy5YdE0kKKgw/image;s=1280x1024;q=80", null, 4);
INSERT INTO Image(image, userID, placeID) VALUES ("https://a0.muscache.com/4ea/air/v2/pictures/0de9f78f-1542-46ec-bcde-4a3c2e33a1f1.jpg?t=r:w2500-h1500-sfit,e:fjpg-c90", null, 5);
INSERT INTO Image(image, userID, placeID) VALUES ("https://a0.muscache.com/4ea/air/v2/pictures/26361338-4a84-4a73-9fcb-7836f8314bf2.jpg?t=r:w2500-h1500-sfit,e:fjpg-c90", null, 5);
INSERT INTO Image(image, userID, placeID) VALUES ("https://a0.muscache.com/4ea/air/v2/pictures/e31c6b12-0de9-496e-b326-3939cde17288.jpg?t=r:w2500-h1500-sfit,e:fjpg-c90", null, 5);
INSERT INTO Image(image, userID, placeID) VALUES ("https://a0.muscache.com/4ea/air/v2/pictures/b0d7198e-4301-42d0-bab0-d9029fc01da0.jpg?t=r:w2500-h1500-sfit,e:fjpg-c90", null, 5);
INSERT INTO Image(image, userID, placeID) VALUES ("https://a0.muscache.com/4ea/air/v2/pictures/513cb5f2-add5-4d98-86e7-bcce1f844d3b.jpg?t=r:w2500-h1500-sfit,e:fjpg-c90", null, 5);
INSERT INTO Image(image, userID, placeID) VALUES ("https://a0.muscache.com/4ea/air/v2/pictures/7162cfe7-5dbe-4e4b-92e7-86fe78c51312.jpg?t=r:w2500-h1500-sfit,e:fjpg-c90", null, 5);

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