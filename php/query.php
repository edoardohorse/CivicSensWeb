<?php




const QUERY_HEADER_REPORT = "SELECT r.id, r.address, r.description, r.state, r.grade, r.user, r.date,
                                    t.name as type, l.lan, l.lng, c.name as city, tm.name as team, cdt.code as cdt";

const QUERY_REPORT_BY_ENTE =  QUERY_HEADER_REPORT."
                                FROM city as c, report as r, cdt, type_report as t, location as l, team as tm
                                WHERE   r.location      = l.id
                                    AND r.city          = c.id
                                    AND r.type_report   = t.id
                                    AND r.team          = tm.id
                                    AND cdt.report		= r.id
                                GROUP BY r.id
                                ORDER BY r.state DESC, r.date DESC";

const QUERY_REPORT_BY_CITY =  QUERY_HEADER_REPORT."
                                FROM city as c, report as r, cdt, type_report as t, location as l, team as tm
                                WHERE   r.location      = l.id
                                    AND r.city          = c.id
                                    AND r.type_report   = t.id
                                    AND r.team          = tm.id
                                    AND cdt.report		= r.id
                                    AND c.name          = ?
                                GROUP BY r.id
                                ORDER BY r.state DESC, r.date DESC";

const QUERY_REPORT_BY_CDT =  QUERY_HEADER_REPORT."
                                FROM city as c, report as r, cdt, type_report as t, location as l, team as tm
                                WHERE   r.location      = l.id
                                    AND r.city          = c.id
                                    AND r.type_report   = t.id
                                    AND cdt.report		= r.id
                                    AND r.team          = tm.id
                                    AND cdt.code        = ?
                                    ";

const QUERY_REPORT_BY_TEAM_BY_ID = QUERY_HEADER_REPORT."
                                FROM city as c, report as r, cdt, type_report as t, location as l, team as tm
                                WHERE   r.location      = l.id
                                    AND r.city          = c.id
                                    AND r.type_report   = t.id
                                    AND r.team          = tm.id
                                    AND cdt.report		= r.id
                                    AND tm.id         = ?
                                    ORDER BY r.state DESC, r.date DESC
                                    ";

const QUERY_REPORT_BY_ID = QUERY_HEADER_REPORT."
                                FROM city as c, report as r, cdt, type_report as t, location as l, team as tm
                                WHERE   r.location      = l.id
                                    AND r.city          = c.id
                                    AND r.type_report   = t.id
                                    AND r.team          = tm.id
                                    AND cdt.report		= r.id
                                    AND r.id            = ?
                                    ";


const QUERY_USER_BY_EMAIL   = "SELECT id, email, name, surname
                                FROM user
                                WHERE email = ?";

const QUERY_CITY_BY_NAME    = "SELECT c.id, c.name, l.lan, l.lng
                                FROM city as c, location as l
                                WHERE c.location = l.id
                                and c.name = ?";

const QUERY_BOUND_SOUTH_CITY= "SELECT lan,lng
                                FROM city as c, location as l
                                WHERE c.bound_south = l.id
                                and c.name = ?";

const QUERY_BOUND_NORTH_CITY= "SELECT lan,lng
                                FROM city as c, location as l
                                WHERE c.bound_north = l.id
                                and c.name = ?";

const QUERY_USER_SIGN_UP   = "INSERT INTO user  (email, type, password)
                                VALUES( ? , ? , ? )";

const QUERY_CITY_ID         = "SELECT id FROM city WHERE name = ?";

const QUERY_NEW_LOCATION    = "INSERT INTO location(lan, lng) VALUES( ?, ? )";

const QUERY_NEW_REPORT      = "INSERT INTO report( city, description, location, address,grade,date,type_report, team)
                                VALUES( (SELECT id FROM city WHERE name = ?), ? , ? , ?, ?, NOW(), ?, ?)";    
                                
const QUERY_FETCH_LIST_TEAM_BY_TYPE_REPORT = " SELECT tm.id, tm.name, count(r.id) as n_report, tm.type_report
                                                    FROM report as r, (
                                                        SELECT team.id, team.name, tp.name as type_report, tp.id as type_report_id
					                                        FROM team, type_report as tp
					                                        WHERE team.type_report = ?
					                                        AND team.type_report = tp.id
				                                        )as tm
                                                WHERE r.team = tm.id
                                                AND r.type_report = tm.type_report_id
                                                group by r.team";

const QUERY_FETCH_LIST_TEAM = "SELECT tm.id, tm.name, tp.name as type_report,  tp.id as type_report_id, u.email
                                    FROM team as tm, type_report as tp, user as u
                                    WHERE  tm.type_report = tp.id
                                    AND     tm.user = u.id";

// Usata per la creazione di un report
// permette di ottenere l'id del team con il minor numero
// di segnalazioni
const QUERY_TEAM_MIN_REPORT = "SELECT list.*
                                FROM (".QUERY_FETCH_LIST_TEAM_BY_TYPE_REPORT."
                                ORDER BY n_report ASC) as list
                                LIMIT 1";

const QUERY_NEW_PHOTOS      = "INSERT INTO photo(name, report) VALUES ( ? , ? )";

const QUERY_FETCH_PHOTOS = "SELECT name
                                 FROM photo as p
                                 WHERE report = ?";

const QUERY_FETCH_HISTORY = "SELECT h.note, h.date, tm.name as team
                                FROM history_report as h, team as tm
                                WHERE h.team = tm.id
                                AND report = ?
                                ORDER BY date DESC";

const QUERY_ADD_HISTORY_REPORT = "INSERT INTO history_report(note,team,date,report)
                                    VALUES ( ? , ? ,NOW() ,? )";


const QUERY_ADD_HISTORY_REPORT_BY_NAME_TEAM = 
                                "INSERT INTO history_report(note,report,date,team)
                                    VALUES ( ? , ? ,NOW() , (
                                        SELECT id
                                            FROM team
                                            WHERE name = ?
                                        )
                                        )";

const QUERY_DELETE_REPORT = "DELETE FROM location WHERE id = (SELECT location FROM report WHERE  id =  ?)";

const QUERY_FETCH_TEAM_BY_EMAIL = "SELECT tm.id, tp.name, tm.n_member, tm.name
                                    FROM team as tm, type_report as tp, user as u
                                    WHERE tm.type_report = tp.id
                                    AND   tm.user        = u.id 
                                    AND   u.email = ?";
                                 
const QUERY_NEW_CDT         = "INSERT INTO cdt  (code, report)
                                    VALUES( ? , ? )";

const QUERY_FETCH_CDT       = " SELECT c.code
                                 FROM cdt as c
                                 WHERE c.code = ?";

const QUERY_EDIT_REPORT_TEAM_BY_NAME = "UPDATE report
                                        SET team = (SELECT id FROM team WHERE team.name = ?)
                                        WHERE id = ?";
const QUERY_EDIT_REPORT_STATE = "UPDATE report
                                SET state = ? 
                                WHERE id = ?";

const QUERY_LOGIN             = "SELECT email, type, password FROM user WHERE email = ?";

const QUERY_ADD_TEAM          = "INSERT INTO team (name, type_report,n_member,user) VALUES ( ?, ? , ?, ?)";

const QUERY_DELETE_TEAM       = "DELETE FROM user WHERE id = (SELECT user FROM team WHERE name = ?)";

const QUERY_LIST_TYPE_REPORT  = "SELECT * FROM type_report";

const QUERY_CHANGE_NAME_TEAM = "UPDATE team SET name=? WHERE name=?"
?>