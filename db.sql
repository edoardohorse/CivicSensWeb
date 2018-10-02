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
    team int not null,
    report int not null,

    PRIMARY KEY(id),
    CONSTRAINT fk_history_report_report FOREIGN KEY(report) REFERENCES report(id) ON DELETE CASCADE,
    CONSTRAINT fk_history_report_team FOREIGN KEY(team) REFERENCES team(id)
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

INSERT INTO team(name, type_report) VALUES
('Enel1',1),
('Enel2',1),
('Enel3',1),
('Enel4',1),
('Enel5',1);



-- Report --
INSERT INTO report (city, description, address, location, user,grade,type_report,team)
VALUES
(1,'Descrizione prova 1','Via prova 1',11, null,'LOW',1,1),
(1,'Descrizione prova 2','Via prova 2',11, null,'LOW',1,1),
(1,'Descrizione prova 3','Via prova 3',11, null,'LOW',1,1),
(1,'Descrizione prova 4','Via prova 4',11, null,'LOW',1,1),
(1,'Descrizione prova 5','Via prova 5',11, null,'LOW',1,1),

(1,'Descrizione prova 6','Via prova 6',11, null,'LOW',1,2),
(1,'Descrizione prova 7','Via prova 7',11, null,'LOW',1,2),

(1,'Descrizione prova 8','Via prova 8',11, null,'LOW',1,3),
(1,'Descrizione prova 9','Via prova 9',11, null,'LOW',1,3),
(1,'Descrizione prova 10','Via prova 10',11, null,'LOW',1,3),
(1,'Descrizione prova 11','Via prova 11',11, null,'LOW',1,3),
(1,'Descrizione prova 12','Via prova 12',11, null,'LOW',1,3),
(1,'Descrizione prova 13','Via prova 13',11, null,'LOW',1,3),
(1,'Descrizione prova 14','Via prova 14',11, null,'LOW',1,3),
(1,'Descrizione prova 15','Via prova 15',11, null,'LOW',1,3),
(1,'Descrizione prova 16','Via prova 16',11, null,'LOW',1,3),

(1,'Descrizione prova 17','Via prova 17',11, null,'LOW',1,4),
(1,'Descrizione prova 18','Via prova 18',11, null,'LOW',1,4),
(1,'Descrizione prova 19','Via prova 19',11, null,'LOW',1,4),
(1,'Descrizione prova 20','Via prova 20',11, null,'LOW',1,4),
(1,'Descrizione prova 21','Via prova 21',11, null,'LOW',1,4),
(1,'Descrizione prova 22','Via prova 22',11, null,'LOW',1,4),

(1,'Descrizione prova 23','Via prova 23',11, null,'LOW',1,5),
(1,'Descrizione prova 24','Via prova 24',11, null,'LOW',1,5),
(1,'Descrizione prova 25','Via prova 25',11, null,'LOW',1,5),
(1,'Descrizione prova 26','Via prova 26',11, null,'LOW',1,5),
(1,'Descrizione prova 27','Via prova 27',11, null,'LOW',1,5),
(1,'Descrizione prova 28','Via prova 28',11, null,'LOW',1,5),
(1,'Descrizione prova 29','Via prova 29',11, null,'LOW',1,5),
(1,'Descrizione prova 30','Via prova 30',11, null,'LOW',1,5),
(1,'Descrizione prova 31','Via prova 31',11, null,'LOW',1,5),
(1,'Descrizione prova 32','Via prova 32',11, null,'LOW',1,5);




INSERT INTO cdt(code, report) VALUES
('quijgj6bIz1',1),
('PAI8WpHJfDR',2),
('emBIK3vaxuk',3),
('8EDrcP92Sbm',4),
('J2LX3S9XQmI',5),
('wlhTbNYQnz5',6),
('ajRv3128cIc',7),
('vPAOj5RdDbz',8),
('i7eSIj72UZi',9),
('3v1OQd6i213',10),
('7vh1CmZbz9Z',11),
('gHWN4QOCAeQ',12),
('r6wmUCVNbSN',13),
('aY2zm6IVwMM',14),
('OOIZEv5lfRY',15),
('ev9xE7JGVWE',16),
('Ja4rMkCbkhr',17),
('LRfPXieXjYC',18),
('8sxQpHR4iUK',19),
('yYbZXu0UEwW',20),
('c51H3JjpOcq',21),
('tEm48YTQ0sh',22),
('6Xp5N1FuHu2',23),
('FkPaoAZvC3C',24),
('2RDHSgGIPwX',25),
('wS8aWokOKdN',26),
('jZKTjGi3pTS',27),
('wL7Hun0IARC',28),
('EtxRBE0FXYu',29),
('d3uBSMpstGh',30),
('3ADUeIK79bt',31),
('b0XJBiNQd9i',32);

-- (2,'Buca in via Dante, vergogna',null,7, null),                                                     -- 1
-- (2,'Lampione spento, riparatelo',null,8,null),                                                      -- 2
-- (2,'Cavo scoperto in via Pitagora, qualcuno potrebbe toccarlo',null,9,null),                        -- 3
-- (2,'Tubo esploso, è tutto allagato in via Istria',null,10,null),                                    -- 4
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
INSERT INTO photo(name, report) VALUES
('IMG_20180607_185856.jpg',1),
('IMG_20180611_135255.jpg',1),
('prova1.jpg',1);


INSERT INTO history_report(note,team,date,report) VALUES
('1 nota',1,NOW(),1);
SELECT SLEEP(5);

INSERT INTO history_report(note,team,date,report) VALUES
('2 nota',1,NOW(),1);

SELECT SLEEP(5);
INSERT INTO history_report(note,team,date,report) VALUES
('3 nota',1,NOW(),1);

SELECT SLEEP(5);
INSERT INTO history_report(note,team,date,report) VALUES
('4 nota',1,NOW(),1);