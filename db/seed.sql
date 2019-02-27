

INSERT INTO type_report(name) 
VALUES
('Guasti elettrici'),
('Guasti idraulici'),
('Smottamento manto stradale'),
('Igiene Urbana');



-- User --
INSERT INTO user (email,type, password,city) VALUES
('user@a','User','1a1dc91c907325c69271ddf0c944bc72','Francavilla Fontana'),
('Enel1@a','Team','1a1dc91c907325c69271ddf0c944bc72','Taranto'),
('Enel2@a','Team','1a1dc91c907325c69271ddf0c944bc72','Milano'),
('Enel3@a','Team','1a1dc91c907325c69271ddf0c944bc72','Napoli'),
('Enel4@a','Team','1a1dc91c907325c69271ddf0c944bc72','Palermo'),
('Enel5@a','Team','1a1dc91c907325c69271ddf0c944bc72','Oria'),
('Idraulico1@a','Team','1a1dc91c907325c69271ddf0c944bc72','Catania'),
('Idraulico2@a','Team','1a1dc91c907325c69271ddf0c944bc72','Latiano'),
('Idraulico3@a','Team','1a1dc91c907325c69271ddf0c944bc72','Ostuni'),
('Idraulico4@a','Team','1a1dc91c907325c69271ddf0c944bc72','Manduria'),
('Idraulico5@a','Team','1a1dc91c907325c69271ddf0c944bc72','Tortona'),
('Stradale1@a','Team','1a1dc91c907325c69271ddf0c944bc72','Arezzo'),
('Stradale2@a','Team','1a1dc91c907325c69271ddf0c944bc72','Perugia'),
('Stradale3@a','Team','1a1dc91c907325c69271ddf0c944bc72','Foggia'),
('Stradale4@a','Team','1a1dc91c907325c69271ddf0c944bc72','Andria'),
('Stradale5@a','Team','1a1dc91c907325c69271ddf0c944bc72','Bari'),
('ente@a','Ente','1a1dc91c907325c69271ddf0c944bc72','Francavilla Fontana'),
('ente2@a','Ente','1a1dc91c907325c69271ddf0c944bc72','Grottaglie');

INSERT INTO team(name, type_report,n_member,user) VALUES
('Enel1',1,5, 2),
('Enel2',1,6, 3),
('Enel3',1,3, 4),
('Enel4',1,10, 5),
('Enel5',1,7, 6),
('Idraulico1',2,5, 7),
('Idraulico2',2,6, 8),
('Idraulico3',2,3, 9),
('Idraulico4',2,10, 10),
('Idraulico5',2,7, 11),
('Stradale1',3,5, 12),
('Stradale2',3,6, 13),
('Stradale3',3,3, 14),
('Stradale4',3,10, 15),
('Stradale5',3,7, 16);



-- Report --
INSERT INTO report (description, address,user,grade,type_report,team,date,lan,lng,code)
VALUES
-- ======= Team Enel
('Descrizione prova enel 1','Via prova enel 1','ente@a','LOW',1,1,NOW(),'40.537479','17.435701','quijgj6bIz1'),
('Descrizione prova enel 2','Via prova enel 2','ente@a','LOW',1,1,NOW(),'40.526467','17.424387','PAI8WpHJfDR'),
('Descrizione prova enel 3','Via prova enel 3','ente@a','LOW',1,1,NOW(),'40.550834','17.444002','emBIK3vaxuk'),
('Descrizione prova enel 4','Via prova enel 4','ente@a','LOW',1,1,NOW(),'40.464361','17.247030','8EDrcP92Sbm'),
('Descrizione prova enel 5','Via prova enel 5','ente@a','LOW',1,1,NOW(),'40.456693','17.223396','J2LX3S9XQmI'),

('Descrizione prova enel 6','Via prova enel 6','ente@a','LOW',1,2,NOW(),'40.483089','17.295812','wlhTbNYQnz5'),
('Descrizione prova enel 7','Via prova enel 7','ente@a','LOW',1,2,NOW(),'40.465112','17.251864','ajRv3128cIc'),

('Descrizione prova enel 8','Via prova enel 8','ente@a','LOW',1,3,NOW(),'40.467224','17.257430','vPAOj5RdDbz'),
('Descrizione prova enel 9','Via prova enel 9','ente@a','LOW',1,3,NOW(),'40.473268','17.242792','i7eSIj72UZi'),
('Descrizione prova enel 10','Via prova enel 10','ente@a','INTERMEDIATE',1,3,NOW(),'40.456271','17.267316','3v1OQd6i213'),
('Descrizione prova enel 11','Via prova enel 11','ente@a','INTERMEDIATE',1,3,NOW(),'40.536122','17.433656','7vh1CmZbz9Z'),
('Descrizione prova enel 12','Via prova enel 12','ente@a','INTERMEDIATE',1,3,NOW(),'40.537869','17.434836','gHWN4QOCAeQ'),
('Descrizione prova enel 13','Via prova enel 13','ente@a','INTERMEDIATE',1,3,NOW(),'40.539320','17.432186','r6wmUCVNbSN'),
('Descrizione prova enel 14','Via prova enel 14','ente@a','INTERMEDIATE',1,3,NOW(),'40.536698','17.438685','aY2zm6IVwMM'),
('Descrizione prova enel 15','Via prova enel 15','ente@a','INTERMEDIATE',1,3,NOW(),'40.537479','17.435701','OOIZEv5lfRY'),
('Descrizione prova enel 16','Via prova enel 16','ente@a','INTERMEDIATE',1,3,NOW(),'40.526467','17.424387','ev9xE7JGVWE'),

('Descrizione prova enel 17','Via prova enel 17','ente@a','INTERMEDIATE',1,4,NOW(),'40.550834','17.444002','Ja4rMkCbkhr'),
('Descrizione prova enel 18','Via prova enel 18','ente@a','INTERMEDIATE',1,4,NOW(),'40.464361','17.247030','LRfPXieXjYC'),
('Descrizione prova enel 19','Via prova enel 19','ente@a','INTERMEDIATE',1,4,NOW(),'40.456693','17.223396','8sxQpHR4iUK'),
('Descrizione prova enel 20','Via prova enel 20','ente@a','HIGH',1,4,NOW(),'40.483089','17.295812','yYbZXu0UEwW'),
('Descrizione prova enel 21','Via prova enel 21','ente@a','HIGH',1,4,NOW(),'40.465112','17.251864','c51H3JjpOcq'), 
('Descrizione prova enel 22','Via prova enel 22','ente@a','HIGH',1,4,NOW(),'40.467224','17.257430','tEm48YTQ0sh'),

('Descrizione prova enel 23','Via prova enel 23','ente@a','HIGH',1,5,NOW(),'40.473268','17.242792','6Xp5N1FuHu2'),
('Descrizione prova enel 24','Via prova enel 24','ente@a','HIGH',1,5,NOW(),'40.456271','17.267316','FkPaoAZvC3C'),
('Descrizione prova enel 25','Via prova enel 25','ente@a','HIGH',1,5,NOW(),'40.536122','17.433656','2RDHSgGIPwX'),
('Descrizione prova enel 26','Via prova enel 26','ente@a','HIGH',1,5,NOW(),'40.537869','17.434836','wS8aWokOKdN'),
('Descrizione prova enel 27','Via prova enel 27','ente@a','HIGH',1,5,NOW(),'40.539320','17.432186','jZKTjGi3pTS'),
('Descrizione prova enel 28','Via prova enel 28','ente@a','HIGH',1,5,NOW(),'40.536698','17.438685','wL7Hun0IARC'),
('Descrizione prova enel 29','Via prova enel 29','ente@a','HIGH',1,5,NOW(),'40.537479','17.435701','EtxRBE0FXYu'),
('Descrizione prova enel 30','Via prova enel 30','ente@a','HIGH',1,5,NOW(),'40.526467','17.424387','d3uBSMpstGh'),
('Descrizione prova enel 31','Via prova enel 31','ente@a','HIGH',1,5,NOW(),'40.550834','17.444002','3ADUeIK79bt'),
('Descrizione prova enel 32','Via prova enel 32','ente@a','HIGH',1,5,NOW(),'40.464361','17.247030','b0XJBiNQd9i'),

-- ======= Team idraulica
('Descrizione prova idro 1','Via prova idro 1','ente2@a','LOW',2,6,NOW(),'40.456693','17.223396','BSLXyOJA1UV'),
('Descrizione prova idro 2','Via prova idro 2','ente2@a','LOW',2,6,NOW(),'40.483089','17.295812','UV9jEZvcQ5v'),
('Descrizione prova idro 3','Via prova idro 3','ente2@a','LOW',2,6,NOW(),'40.465112','17.251864','dxS7UqAaMLE'),
('Descrizione prova idro 4','Via prova idro 4','ente2@a','LOW',2,6,NOW(),'40.467224','17.257430','a4OacflnmxU'),
('Descrizione prova idro 5','Via prova idro 5','ente2@a','LOW',2,6,NOW(),'40.473268','17.242792','mMUT26vmMpa'),
('Descrizione prova idro 6','Via prova idro 6','ente2@a','LOW',2,7,NOW(),'40.456271','17.267316','ObtNO4cMLDm'),
('Descrizione prova idro 7','Via prova idro 7','ente2@a','LOW',2,7,NOW(),'40.536122','17.433656','mhAHGpyMXp0'),
('Descrizione prova idro 8','Via prova idro 8','ente2@a','LOW',2,8,NOW(),'40.537869','17.434836','k5zPaAfYHtU'),
('Descrizione prova idro 9','Via prova idro 9','ente2@a','LOW',2,8,NOW(),'40.539320','17.432186','lRU7OdEdziH'),
('Descrizione prova idro 10','Via prova idro 10','ente2@a','INTERMEDIATE',2,8,NOW(),'40.536698','17.438685','wz4QpaaAmKo'),
('Descrizione prova idro 11','Via prova idro 11','ente2@a','INTERMEDIATE',2,8,NOW(),'40.537479','17.435701','iSl2JcIB6VP'),
('Descrizione prova idro 12','Via prova idro 12','ente2@a','INTERMEDIATE',2,8,NOW(),'40.526467','17.424387','UNzM6j1xG9k'),
('Descrizione prova idro 13','Via prova idro 13','ente2@a','INTERMEDIATE',2,8,NOW(),'40.550834','17.444002','zxIvuXiD6eC'),
('Descrizione prova idro 14','Via prova idro 14','ente2@a','INTERMEDIATE',2,8,NOW(),'40.464361','17.247030','vCadMxvBN66'),
('Descrizione prova idro 15','Via prova idro 15','ente2@a','INTERMEDIATE',2,8,NOW(),'40.456693','17.223396','3pOVQwxhf15'),
('Descrizione prova idro 16','Via prova idro 16','ente2@a','INTERMEDIATE',2,8,NOW(),'40.483089','17.295812','lvDVKmXpBfq'),
('Descrizione prova idro 17','Via prova idro 17','ente2@a','INTERMEDIATE',2,9,NOW(),'40.465112','17.251864','ecJwhex8Cak'),
('Descrizione prova idro 18','Via prova idro 18','ente2@a','INTERMEDIATE',2,9,NOW(),'40.467224','17.257430','pMr9nZhiBxm'),
('Descrizione prova idro 19','Via prova idro 19','ente2@a','INTERMEDIATE',2,9,NOW(),'40.473268','17.242792','p3omeHSKlgw'),
('Descrizione prova idro 20','Via prova idro 20','ente2@a','HIGH',2,9,NOW(),'40.456271','17.267316','iRvcNkv0bYo'),
('Descrizione prova idro 21','Via prova idro 21','ente2@a','HIGH',2,9,NOW(),'40.536122','17.433656','ytLUhflRVgB'),
('Descrizione prova idro 22','Via prova idro 22','ente2@a','HIGH',2,9,NOW(),'40.537869','17.434836','Etk5Xn7DDCi'),
('Descrizione prova idro 23','Via prova idro 23','ente2@a','HIGH',2,10,NOW(),'40.539320','17.432186','4hvienfbba1'),
('Descrizione prova idro 24','Via prova idro 24','ente2@a','HIGH',2,10,NOW(),'40.536698','17.438685','crm95XMIV88'),
('Descrizione prova idro 25','Via prova idro 25','ente2@a','HIGH',2,10,NOW(),'40.537479','17.435701','OGm7YuCAVKc'),
('Descrizione prova idro 26','Via prova idro 26','ente2@a','HIGH',2,10,NOW(),'40.526467','17.424387','PDW9haLrCT9'),
('Descrizione prova idro 27','Via prova idro 27','ente2@a','HIGH',2,10,NOW(),'40.550834','17.444002','y2OAvb3r4h5'),
('Descrizione prova idro 28','Via prova idro 28','ente2@a','HIGH',2,10,NOW(),'40.464361','17.247030','0SqlLlUR4UI'),
('Descrizione prova idro 29','Via prova idro 29','ente2@a','HIGH',2,10,NOW(),'40.456693','17.223396','AVpTJDKquKQ'),
('Descrizione prova idro 30','Via prova idro 30','ente2@a','HIGH',2,10,NOW(),'40.483089','17.295812','L8iOwmo27l7'),
('Descrizione prova idro 31','Via prova idro 31','ente2@a','HIGH',2,10,NOW(),'40.465112','17.251864','R2wn2qSPZ1J'),
('Descrizione prova idro 32','Via prova idro 32','ente2@a','HIGH',2,10,NOW(),'40.467224','17.257430','W9gM5oWVXZe'),

-- ========= Team Smottamento
('Descrizione prova smott 1','Via prova smott idro 1','ente@a','LOW',3,11,NOW(),'40.473268','17.242792','eMLrm3YddVq'),
('Descrizione prova smott 2','Via prova smott idro 2','ente@a','LOW',3,11,NOW(),'40.456271','17.267316','B2WHQ99ZexM'),
('Descrizione prova smott 3','Via prova smott idro 3','ente@a','LOW',3,11,NOW(),'40.536122','17.433656','cuOW1SRXsQi'),
('Descrizione prova smott 4','Via prova smott idro 4','ente@a','LOW',3,11,NOW(),'40.537869','17.434836','bm2XwZ5dfUP'),
('Descrizione prova smott 5','Via prova smott idro 5','ente@a','LOW',3,11,NOW(),'40.539320','17.432186','zX1IJpAf0zb'),
('Descrizione prova smott 6','Via prova smott idro 6','ente@a','LOW',3,12,NOW(),'40.536698','17.438685','dmKJ2HdbbPJ'),
('Descrizione prova smott 7','Via prova smott idro 7','ente@a','LOW',3,12,NOW(),'40.537479','17.435701','CYQyl4NGgVo'),
('Descrizione prova smott 8','Via prova smott idro 8','ente@a','LOW',3,13,NOW(),'40.526467','17.424387','DP9X6U60678'),
('Descrizione prova smott 9','Via prova smott idro 9','ente@a','LOW',3,13,NOW(),'40.550834','17.444002','HfY4YU9bWVI'),
('Descrizione prova smott 10','Via prova smott idro 10','ente2@a','INTERMEDIATE',3,13,NOW(),'40.464361','17.247030','SOWUrnF5VdC'),
('Descrizione prova smott 11','Via prova smott idro 11','ente2@a','INTERMEDIATE',3,13,NOW(),'40.456693','17.223396','NH9w7vn08Q4'),
('Descrizione prova smott 12','Via prova smott idro 12','ente2@a','INTERMEDIATE',3,13,NOW(),'40.483089','17.295812','aU8ai0mTG9M'),
('Descrizione prova smott 13','Via prova smott idro 13','ente2@a','INTERMEDIATE',3,13,NOW(),'40.465112','17.251864','0C91Qm2tIsQ'),
('Descrizione prova smott 14','Via prova smott idro 14','ente2@a','INTERMEDIATE',3,13,NOW(),'40.467224','17.257430','PIKSadyDdUq'),
('Descrizione prova smott 15','Via prova smott idro 15','ente2@a','INTERMEDIATE',3,13,NOW(),'40.473268','17.242792','JGc99Vwafya'),
('Descrizione prova smott 16','Via prova smott idro 16','ente2@a','INTERMEDIATE',3,13,NOW(),'40.456271','17.267316','oR3oAs3ddSs'),
('Descrizione prova smott 17','Via prova smott idro 17','ente2@a','INTERMEDIATE',3,14,NOW(),'40.536122','17.433656','zBajoJF4rL7'),
('Descrizione prova smott 18','Via prova smott idro 18','ente2@a','INTERMEDIATE',3,14,NOW(),'40.537869','17.434836','oKI7gdpzrmR'),
('Descrizione prova smott 19','Via prova smott idro 19','ente2@a','INTERMEDIATE',3,14,NOW(),'40.539320','17.432186','vXme6A1a4n8'),
('Descrizione prova smott 20','Via prova smott idro 20','ente2@a','HIGH',3,14,NOW(),'40.536698','17.438685','uCT9I8JyLjV'),
('Descrizione prova smott 21','Via prova smott idro 21','ente2@a','HIGH',3,14,NOW(),'40.539320','17.432186','dymWq4m8ZwF'),
('Descrizione prova smott 22','Via prova smott idro 22','ente2@a','HIGH',3,14,NOW(),'40.536698','17.438685','kQLtBROmcSq'),
('Descrizione prova smott 23','Via prova smott idro 23','ente2@a','HIGH',3,15,NOW(),'40.537479','17.435701','yuHIPPYPEn7'),
('Descrizione prova smott 24','Via prova smott idro 24','ente2@a','HIGH',3,15,NOW(),'40.526467','17.424387','B1pPgBpStkF'),
('Descrizione prova smott 25','Via prova smott idro 25','ente2@a','HIGH',3,15,NOW(),'40.550834','17.444002','zm9G9kaH0D3'),
('Descrizione prova smott 26','Via prova smott idro 26','ente2@a','HIGH',3,15,NOW(),'40.464361','17.247030','9dioN4t0MsJ'),
('Descrizione prova smott 27','Via prova smott idro 27','ente2@a','HIGH',3,15,NOW(),'40.456693','17.223396','6OGK1i9qaxY'),
('Descrizione prova smott 28','Via prova smott idro 28','ente2@a','HIGH',3,15,NOW(),'40.483089','17.295812','ce3aeFDIf55'),
('Descrizione prova smott 29','Via prova smott idro 29','ente2@a','HIGH',3,15,NOW(),'40.465112','17.251864','uIq0s2Gl6oI'),
('Descrizione prova smott 30','Via prova smott idro 30','ente2@a','HIGH',3,15,NOW(),'40.467224','17.257430','E4BwbhSwdu4'),
('Descrizione prova smott 31','Via prova smott idro 31','ente2@a','HIGH',3,15,NOW(),'40.473268','17.242792','hEMo2z25Gzd'),
('Descrizione prova smott 32','Via prova smott idro 32','ente2@a','HIGH',3,15,NOW(),'40.456271','17.267316','hrEfWBHOYxC');


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