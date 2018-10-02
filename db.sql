CREATE TABLE IF NOT EXISTS location(
    id int NOT NULL AUTO_INCREMENT,
    lan double NOT NULL,
    lng double NOT NULL,
   
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS city(
    id int NOT NULL AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    location int NOT NULL,
    bound_south int NOT NULL,
    bound_north int NOT NULL,

    PRIMARY KEY(id)
    -- CONSTRAINT fk_city_location FOREIGN KEY(location) REFERENCES location(id) ON DELETE CASCADE,
    -- CONSTRAINT fk_city_location_bound_south FOREIGN KEY(bound_south) REFERENCES location(id) ON DELETE CASCADE,
    -- CONSTRAINT fk_city_location_bound_north FOREIGN KEY(bound_north) REFERENCES location(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS user(
    id int NOT NULL UNIQUE AUTO_INCREMENT,
    email varchar(100) NOT NULL UNIQUE,

    PRIMARY KEY(email)
);

CREATE TABLE IF NOT EXISTS type_report(
    id int NOT NULL AUTO_INCREMENT,
    name varchar(200) not null,

    PRIMARY KEY(id)
);



CREATE TABLE IF NOT EXISTS team(
    id int NOT NULL AUTO_INCREMENT,
    name varchar(150) not null,
    type_report int,

    PRIMARY KEY(id),
    CONSTRAINT fk_team_type_report FOREIGN KEY(type_report) REFERENCES type_report(id) 
);

CREATE TABLE IF NOT EXISTS report(
    id int NOT NULL AUTO_INCREMENT,
    city int NOT NULL,
    address varchar(250) null,
    description varchar(300),
    state enum('In attesa','In lavorazione','Finito') DEFAULT 'In attesa',
    grade enum('HIGH','INTERMEDIATE','LOW') NOT NULL DEFAULT 'LOW' ,
    location int NOT NULL,
    user  varchar(100) NULL,
    type_report int not null,
    team int DEFAULT NULL,
    
    PRIMARY KEY(id),
    CONSTRAINT fk_report_type_report       FOREIGN KEY(type_report)         REFERENCES type_report(id),
    CONSTRAINT fk_report_team              FOREIGN KEY(team)                REFERENCES team(id)
    -- CONSTRAINT fk_report_city       FOREIGN KEY(city)       REFERENCES city(id),
    -- CONSTRAINT fk_report_location   FOREIGN KEY(location)   REFERENCES location(id) ON DELETE CASCADE, 
    -- CONSTRAINT fk_report_user       FOREIGN KEY(user)   REFERENCES user(email)
);

CREATE TABLE IF NOT EXISTS history_report(
    id int NOT NULL AUTO_INCREMENT,
    date datetime not null,
    note text not NULL,
    report int not null,

    PRIMARY KEY(id),
    CONSTRAINT fk_history_report_report FOREIGN KEY(report) REFERENCES report(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS photo(
    id int NOT NULL AUTO_INCREMENT,
    name varchar(150) NOT NULL,
    report int NOT NULL,
   
    PRIMARY KEY(id),
    CONSTRAINT fk_photo_report       FOREIGN KEY(report)       REFERENCES report(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS cdt(
    id int NOT NULL AUTO_INCREMENT,
    code varchar(11) NOT NULL UNIQUE,
    report int NOT NULL,
   
    PRIMARY KEY(id),
    CONSTRAINT fk_cdt_report       FOREIGN KEY(report)       REFERENCES report(id) ON DELETE CASCADE
);




INSERT INTO type_report(name) 
VALUES
('Guasti elettrici'),
('Guasti idraulici'),
('Smottamento manto stradale'),
('Igiene Urbana');

-- TRUNCATE location;
-- TRUNCATE report;
-- TRUNCATE city;
-- TRUNCATE user;
-- TRUNCATE photo;


-- Coords --
INSERT INTO location (lan, lng)
VALUES
(40.537479, 17.435701),     -- Grottaglie                       1
(40.526467, 17.424387),     -- Grottaglie Bound south           2
(40.550834, 17.444002),     -- Grottaglie Bount north           3


(40.464361, 17.247030),     -- Taranto                          4
(40.456693, 17.223396),     -- Taranto Bound south              5
(40.483089, 17.295812),     -- Taranto Bount north              6


(40.465112, 17.251864),     -- Dante Alighieri                  7
(40.467224, 17.257430),     -- Giuseppe Messina                 8
(40.473268, 17.242792),     -- Pitagora                         9
(40.456271, 17.267316),     -- Istria                            10

(40.536122, 17.433656),      -- Matteotti                       11
(40.537869, 17.434836),      -- Ennio                           12
(40.539320, 17.432186),      -- Diaz                            13  
(40.536698, 17.438685)      -- Colombo                         14
;



-- City --
INSERT INTO city (name, location, bound_south, bound_north)
VALUES
('Grottaglie',  1, 2,3),
('Taranto',     4, 5,6);

-- User --
INSERT INTO user (email) VALUES
('edoardohorse@gmail.com'),
('iweek16@gmail.com');

-- Report --
INSERT INTO report (city, description, address, location, user,grade,type_report,team)
VALUES
-- (2,'Buca in via Dante, vergogna',null,7, null),                                                     -- 1
-- (2,'Lampione spento, riparatelo',null,8,null),                                                      -- 2
-- (2,'Cavo scoperto in via Pitagora, qualcuno potrebbe toccarlo',null,9,null),                        -- 3
-- (2,'Tubo esploso, è tutto allagato in via Istria',null,10,null),                                    -- 4

(1,'Buca in via Matteoti, vergogna','Viale Giacomo Matteotti, 37',11, null,'HIGH',1),                        -- 5
(1,'Lampione spento in via Ennio, riparatelo','Via Ennio, 177',12, null,'INTERMEDIATE',2),                           -- 6
(1,'Cavo scoperto in via Diaz, qualcuno potrebbe toccarlo','SP72, 20',13,null,'LOW',2),                     -- 7
(1,'Tubo esploso, è tutto allagato in via Colombo','Via Cristoforo Colombo, 71',14,null,'LOW',3);           -- 8

-- -- Photos --
-- INSERT INTO photo(name, report) VALUES
-- ('prova (1).jpg',1),
-- ('prova (2).jpg',1),
-- ('prova (3).jpg',1),
-- ('prova (4).jpg',2),
-- ('prova (5).jpg',2),
-- ('prova (6).jpg',2),
-- ('prova (7).jpg',3),
-- ('prova (8).jpg',3),
-- ('prova (9).jpg',3),
-- ('prova (10).jpg',4),
-- ('prova (11).jpg',4),
-- ('prova (12).jpg',4),
-- ('prova (13).jpg',5),
-- ('prova (14).jpg',5),
-- ('prova (15).jpg',5),
-- ('prova (16).jpg',6),
-- ('prova (17).jpg',6),
-- ('prova (18).jpg',6),
-- ('prova (19).jpg',7),
-- ('prova (20).jpg',7),
-- ('prova (21).jpg',7),
-- ('prova (22).jpg',8),
-- ('prova (23).jpg',8),
-- ('prova (24).jpg',8);

25	IMG_20180607_185856.jpg	1
26	IMG_20180611_135255.jpg	2
27	prova1.jpg	1
28	prova2.jpg	2
29	prova3.jpg	4


INSERT INTO team(name, type_report) VALUES
('Enel1',1),
('Enel2',1),
('Enel3',1),
('Enel4',1),
('Enel5',1);


cdt
1KrGd0xQDUo	1
EXvLpKS6V4M	2
OE2rMzdBk8I	3
sjk8J3ByDqW	4