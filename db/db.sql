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
    n_member int not null,

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
    date datetime not null,
    
    PRIMARY KEY(id),
    CONSTRAINT fk_report_type_report       FOREIGN KEY(type_report)         REFERENCES type_report(id),
    CONSTRAINT fk_report_team              FOREIGN KEY(team)                REFERENCES team(id),
    CONSTRAINT fk_report_location   FOREIGN KEY(location)   REFERENCES location(id) ON DELETE CASCADE 
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
(40.536698, 17.438685),      -- Colombo                         14
(11,11),
(12,12),
(13,13),
(14,14),
(15,15),
(16,16),
(17,17),
(18,18),
(19,19),
(20,20),
(21,21),
(22,22),
(23,23),
(24,24),
(25,25),
(26,26),
(27,27),
(28,28),
(29,29),
(30,30),
(31,31),
(32,32),
(33,33),
(34,34),
(35,35),
(36,36),
(37,37),
(38,38),
(39,39),
(40,40),
(41,41),
(42,42),
(43,43),
(44,44),
(45,45),
(46,46),
(47,47),
(48,48),
(49,49),
(50,50),
(51,51),
(52,52),
(53,53),
(54,54),
(55,55),
(56,56),
(57,57),
(58,58),
(59,59),
(60,60),
(61,61),
(62,62),
(63,63),
(64,64),
(65,65),
(66,66),
(67,67),
(68,68),
(69,69),
(70,70),
(71,71),
(72,72),
(73,73),
(74,74),
(75,75),
(76,76),
(77,77),
(78,78),
(79,79),
(80,80),
(81,81),
(82,82),
(83,83),
(84,84),
(85,85),
(86,86),
(87,87),
(88,88),
(89,89),
(90,90),
(91,91),
(92,92),
(93,93),
(94,94),
(95,95),
(96,96),
(97,97),
(98,98),
(99,99),
(100,100),
(101,101),
(102,102),
(103,103),
(104,104);


-- City --
INSERT INTO city (name, location, bound_south, bound_north)
VALUES
('Grottaglie',  1, 2,3),
('Taranto',     4, 5,6);

-- User --
INSERT INTO user (email) VALUES
('edoardohorse@gmail.com'),
('iweek16@gmail.com');

INSERT INTO team(name, type_report,n_member) VALUES
('Enel1',1,5),
('Enel2',1,6),
('Enel3',1,3),
('Enel4',1,10),
('Enel5',1,7),
('Idraulico1',2,5),
('Idraulico2',2,6),
('Idraulico3',2,3),
('Idraulico4',2,10),
('Idraulico5',2,7);
('Stradale1',3,5),
('Stradale2',3,6),
('Stradale3',3,3),
('Stradale4',3,10),
('Stradale5',3,7);



-- Report --
INSERT INTO report (city, description, address, location, user,grade,type_report,team,date)
VALUES
-- ======= Team Enel
(1,'Descrizione prova enel 1','Via prova enel 1',11, null,'LOW',1,1,NOW()),
(1,'Descrizione prova enel 2','Via prova enel 2',12, null,'LOW',1,1,NOW()),
(1,'Descrizione prova enel 3','Via prova enel 3',13, null,'LOW',1,1,NOW()),
(1,'Descrizione prova enel 4','Via prova enel 4',14, null,'LOW',1,1,NOW()),
(1,'Descrizione prova enel 5','Via prova enel 5',15, null,'LOW',1,1,NOW()),

(1,'Descrizione prova enel 6','Via prova enel 6',16, null,'LOW',1,2,NOW()),
(1,'Descrizione prova enel 7','Via prova enel 7',17, null,'LOW',1,2,NOW()),

(1,'Descrizione prova enel 8','Via prova enel 8',18, null,'LOW',1,3,NOW()),
(1,'Descrizione prova enel 9','Via prova enel 9',19, null,'LOW',1,3,NOW()),
(1,'Descrizione prova enel 10','Via prova enel 10',20, null,'INTERMEDIATE',1,3,NOW()),
(1,'Descrizione prova enel 11','Via prova enel 11',21, null,'INTERMEDIATE',1,3,NOW()),
(1,'Descrizione prova enel 12','Via prova enel 12',22, null,'INTERMEDIATE',1,3,NOW()),
(1,'Descrizione prova enel 13','Via prova enel 13',23, null,'INTERMEDIATE',1,3,NOW()),
(1,'Descrizione prova enel 14','Via prova enel 14',24, null,'INTERMEDIATE',1,3,NOW()),
(1,'Descrizione prova enel 15','Via prova enel 15',25, null,'INTERMEDIATE',1,3,NOW()),
(1,'Descrizione prova enel 16','Via prova enel 16',26, null,'INTERMEDIATE',1,3,NOW()),

(1,'Descrizione prova enel 17','Via prova enel 17',27, null,'INTERMEDIATE',1,4,NOW()),
(1,'Descrizione prova enel 18','Via prova enel 18',28, null,'INTERMEDIATE',1,4,NOW()),
(1,'Descrizione prova enel 19','Via prova enel 19',29, null,'INTERMEDIATE',1,4,NOW()),
(1,'Descrizione prova enel 20','Via prova enel 20',30, null,'HIGH',1,4,NOW()),
(1,'Descrizione prova enel 21','Via prova enel 21',31, null,'HIGH',1,4,NOW()),
(1,'Descrizione prova enel 22','Via prova enel 22',32, null,'HIGH',1,4,NOW()),

(1,'Descrizione prova enel 23','Via prova enel 23',33, null,'HIGH',1,5,NOW()),
(1,'Descrizione prova enel 24','Via prova enel 24',34, null,'HIGH',1,5,NOW()),
(1,'Descrizione prova enel 25','Via prova enel 25',35, null,'HIGH',1,5,NOW()),
(1,'Descrizione prova enel 26','Via prova enel 26',36, null,'HIGH',1,5,NOW()),
(1,'Descrizione prova enel 27','Via prova enel 27',37, null,'HIGH',1,5,NOW()),
(1,'Descrizione prova enel 28','Via prova enel 28',38, null,'HIGH',1,5,NOW()),
(1,'Descrizione prova enel 29','Via prova enel 29',39, null,'HIGH',1,5,NOW()),
(1,'Descrizione prova enel 30','Via prova enel 30',40, null,'HIGH',1,5,NOW()),
(1,'Descrizione prova enel 31','Via prova enel 31',41, null,'HIGH',1,5,NOW()),
(1,'Descrizione prova enel 32','Via prova enel 32',42, null,'HIGH',1,5,NOW()),


-- ======= Team idraulica
(1,'Descrizione prova idro 1','Via prova idro 1',42, null,'LOW',2,1,NOW()),
(1,'Descrizione prova idro 2','Via prova idro 2',43, null,'LOW',2,1,NOW()),
(1,'Descrizione prova idro 3','Via prova idro 3',44, null,'LOW',2,1,NOW()),
(1,'Descrizione prova idro 4','Via prova idro 4',45, null,'LOW',2,1,NOW()),
(1,'Descrizione prova idro 5','Via prova idro 5',46, null,'LOW',2,1,NOW()),

(1,'Descrizione prova idro 6','Via prova idro 6',47, null,'LOW',2,2,NOW()),
(1,'Descrizione prova idro 7','Via prova idro 7',48, null,'LOW',2,2,NOW()),

(1,'Descrizione prova idro 8','Via prova idro 8',49, null,'LOW',2,3,NOW()),
(1,'Descrizione prova idro 9','Via prova idro 9',50, null,'LOW',2,3,NOW()),
(1,'Descrizione prova idro 10','Via prova idro 10',51, null,'INTERMEDIATE',2,3,NOW()),
(1,'Descrizione prova idro 11','Via prova idro 11',52, null,'INTERMEDIATE',2,3,NOW()),
(1,'Descrizione prova idro 12','Via prova idro 12',53, null,'INTERMEDIATE',2,3,NOW()),
(1,'Descrizione prova idro 13','Via prova idro 13',54, null,'INTERMEDIATE',2,3,NOW()),
(1,'Descrizione prova idro 14','Via prova idro 14',55, null,'INTERMEDIATE',2,3,NOW()),
(1,'Descrizione prova idro 15','Via prova idro 15',56, null,'INTERMEDIATE',2,3,NOW()),
(1,'Descrizione prova idro 16','Via prova idro 16',57, null,'INTERMEDIATE',2,3,NOW()),

(1,'Descrizione prova idro 17','Via prova idro 17',58, null,'INTERMEDIATE',2,4,NOW()),
(1,'Descrizione prova idro 18','Via prova idro 18',59, null,'INTERMEDIATE',2,4,NOW()),
(1,'Descrizione prova idro 19','Via prova idro 19',60, null,'INTERMEDIATE',2,4,NOW()),
(1,'Descrizione prova idro 20','Via prova idro 20',61, null,'HIGH',2,4,NOW()),
(1,'Descrizione prova idro 21','Via prova idro 21',62, null,'HIGH',2,4,NOW()),
(1,'Descrizione prova idro 22','Via prova idro 22',63, null,'HIGH',2,4,NOW()),

(1,'Descrizione prova idro 23','Via prova idro 23',64, null,'HIGH',2,5,NOW()),
(1,'Descrizione prova idro 24','Via prova idro 24',65, null,'HIGH',2,5,NOW()),
(1,'Descrizione prova idro 25','Via prova idro 25',66, null,'HIGH',2,5,NOW()),
(1,'Descrizione prova idro 26','Via prova idro 26',67, null,'HIGH',2,5,NOW()),
(1,'Descrizione prova idro 27','Via prova idro 27',68, null,'HIGH',2,5,NOW()),
(1,'Descrizione prova idro 28','Via prova idro 28',69, null,'HIGH',2,5,NOW()),
(1,'Descrizione prova idro 29','Via prova idro 29',70, null,'HIGH',2,5,NOW()),
(1,'Descrizione prova idro 30','Via prova idro 30',71, null,'HIGH',2,5,NOW()),
(1,'Descrizione prova idro 31','Via prova idro 31',72, null,'HIGH',2,5,NOW()),
(1,'Descrizione prova idro 32','Via prova idro 32',73, null,'HIGH',2,5,NOW());

-- ========= Team Smottamento
(1,'Descrizione prova smott 1','Via prova smott idro 1',74, null,'LOW',3,1,NOW()),
(1,'Descrizione prova smott 2','Via prova smott idro 2',75, null,'LOW',3,1,NOW()),
(1,'Descrizione prova smott 3','Via prova smott idro 3',76, null,'LOW',3,1,NOW()),
(1,'Descrizione prova smott 4','Via prova smott idro 4',77, null,'LOW',3,1,NOW()),
(1,'Descrizione prova smott 5','Via prova smott idro 5',78, null,'LOW',3,1,NOW()),
(1,'Descrizione prova smott 6','Via prova smott idro 6',79, null,'LOW',3,2,NOW()),
(1,'Descrizione prova smott 7','Via prova smott idro 7',80, null,'LOW',3,2,NOW()),
(1,'Descrizione prova smott 8','Via prova smott idro 8',81, null,'LOW',3,3,NOW()),
(1,'Descrizione prova smott 9','Via prova smott idro 9',82, null,'LOW',3,3,NOW()),
(1,'Descrizione prova smott 10','Via prova smott idro 10',83, null,'INTERMEDIATE',3,3,NOW()),
(1,'Descrizione prova smott 11','Via prova smott idro 11',84, null,'INTERMEDIATE',3,3,NOW()),
(1,'Descrizione prova smott 12','Via prova smott idro 12',85, null,'INTERMEDIATE',3,3,NOW()),
(1,'Descrizione prova smott 13','Via prova smott idro 13',86, null,'INTERMEDIATE',3,3,NOW()),
(1,'Descrizione prova smott 14','Via prova smott idro 14',87, null,'INTERMEDIATE',3,3,NOW()),
(1,'Descrizione prova smott 15','Via prova smott idro 15',88, null,'INTERMEDIATE',3,3,NOW()),
(1,'Descrizione prova smott 16','Via prova smott idro 16',89, null,'INTERMEDIATE',3,3,NOW()),
(1,'Descrizione prova smott 17','Via prova smott idro 17',90, null,'INTERMEDIATE',3,4,NOW()),
(1,'Descrizione prova smott 18','Via prova smott idro 18',91, null,'INTERMEDIATE',3,4,NOW()),
(1,'Descrizione prova smott 19','Via prova smott idro 19',92, null,'INTERMEDIATE',3,4,NOW()),
(1,'Descrizione prova smott 20','Via prova smott idro 20',93, null,'HIGH',3,4,NOW()),
(1,'Descrizione prova smott 21','Via prova smott idro 21',94, null,'HIGH',3,4,NOW()),
(1,'Descrizione prova smott 22','Via prova smott idro 22',95, null,'HIGH',3,4,NOW()),
(1,'Descrizione prova smott 23','Via prova smott idro 23',96, null,'HIGH',3,5,NOW()),
(1,'Descrizione prova smott 24','Via prova smott idro 24',97, null,'HIGH',3,5,NOW()),
(1,'Descrizione prova smott 25','Via prova smott idro 25',98, null,'HIGH',3,5,NOW()),
(1,'Descrizione prova smott 26','Via prova smott idro 26',99, null,'HIGH',3,5,NOW()),
(1,'Descrizione prova smott 27','Via prova smott idro 27',100, null,'HIGH',3,5,NOW()),
(1,'Descrizione prova smott 28','Via prova smott idro 28',101, null,'HIGH',3,5,NOW()),
(1,'Descrizione prova smott 29','Via prova smott idro 29',102, null,'HIGH',3,5,NOW()),
(1,'Descrizione prova smott 30','Via prova smott idro 30',103, null,'HIGH',3,5,NOW()),
(1,'Descrizione prova smott 31','Via prova smott idro 31',104, null,'HIGH',3,5,NOW()),
(1,'Descrizione prova smott 32','Via prova smott idro 32',105, null,'HIGH',3,5,NOW());





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