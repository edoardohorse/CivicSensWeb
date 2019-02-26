CREATE DATABASE IF NOT EXISTS my_civicsens;

USE my_civicsens;


CREATE TABLE IF NOT EXISTS user(
    id int NOT NULL UNIQUE AUTO_INCREMENT,
    email varchar(100) NOT NULL UNIQUE,
    type enum('Ente','Team','User') DEFAULT 'User',
    password char(32) NOT NULL,
	city varchar(100) NOT NULL,

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
    type_report int not null,
    n_member int not null,
    user int NOT NULL,

    PRIMARY KEY(id),
    CONSTRAINT fk_team_type_report  FOREIGN KEY(type_report) REFERENCES type_report(id),
    CONSTRAINT fk_team_user         FOREIGN KEY(user) REFERENCES user(id) ON DELETE CASCADE            
);


CREATE TABLE IF NOT EXISTS report(
    id int NOT NULL AUTO_INCREMENT,
    address varchar(250) null,
    description varchar(300),
    state enum('In attesa','In lavorazione','Finito') DEFAULT 'In attesa',
    grade enum('HIGH','INTERMEDIATE','LOW') NOT NULL DEFAULT 'LOW' ,
    location int NOT NULL,
    user  varchar(100) NULL,
    type_report int not null,
    team int DEFAULT NULL,
    date datetime not null,
	lan double NOT NULL,
    lng double NOT NULL,
    code varchar(11) NOT NULL UNIQUE,
    
    PRIMARY KEY(id),
    CONSTRAINT fk_report_type_report       FOREIGN KEY(type_report)         REFERENCES type_report(id),
    CONSTRAINT fk_report_team              FOREIGN KEY(team)                REFERENCES team(id),
    CONSTRAINT fk_report_user              FOREIGN KEY(user)                REFERENCES user(email)  
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


