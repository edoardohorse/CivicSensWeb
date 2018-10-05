<?php




const QUERY_HEADER_REPORT = "SELECT r.id, r.address, r.description, r.state, r.grade, r.user, t.name as type, l.lan, l.lng, c.name as city, tm.name as team, cdt.code as cdt";

const QUERY_REPORT_BY_CITY =  QUERY_HEADER_REPORT."
                                FROM city as c, report as r, cdt, type_report as t, location as l, team as tm
                                WHERE   r.location      = l.id
                                    AND r.city          = c.id
                                    AND r.type_report   = t.id
                                    AND r.team          = tm.id
                                    AND cdt.report		= r.id
                                    AND c.name          = ?
                                GROUP BY r.id
                                ORDER BY r.id DESC";

const QUERY_REPORT_BY_CDT =  QUERY_HEADER_REPORT."
                                FROM city as c, report as r, cdt, type_report as t, location as l, team as tm
                                WHERE   r.location      = l.id
                                    AND r.city          = c.id
                                    AND r.type_report   = t.id
                                    AND cdt.report		= r.id
                                    AND r.team          = tm.id
                                    AND cdt.code        = ?
                                    ";

const QUERY_REPORT_BY_TEAM = QUERY_HEADER_REPORT."
                                FROM city as c, report as r, cdt, type_report as t, location as l, team as tm
                                WHERE   r.location      = l.id
                                    AND r.city          = c.id
                                    AND r.type_report   = t.id
                                    AND r.team          = tm.id
                                    AND cdt.report		= r.id
                                    AND tm.name         = ?
                                    GROUP BY r.id
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

const QUERY_USER_SIGN_UP   = "INSERT INTO user  (email, name, surname)
                                VALUES( ? , ? , ? )";

const QUERY_CITY_ID         = "SELECT id FROM city WHERE name = ?";

const QUERY_NEW_LOCATION    = "INSERT INTO location(lan, lng) VALUES( ?, ? )";

const QUERY_NEW_REPORT      = "INSERT INTO report(city, description, location, address,grade)
                                VALUES( ? , ? , ? , ?, ?)";                            

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

const QUERY_FETCH_TEAM_BY_NAME = "SELECT id
                                    FROM team
                                    WHERE name = ?";
                                 
const QUERY_NEW_CDT         = "INSERT INTO cdt  (code, report)
                                    VALUES( ? , ? )";

const QUERY_FETCH_CDT       = " SELECT c.code
                                 FROM cdt as c
                                 WHERE c.code = ?";

const QUERY_EDIT_REPORT_TEAM = "UPDATE report
                                SET team = ? 
                                WHERE id = ?";
const QUERY_EDIT_REPORT_STATE = "UPDATE report
                                SET state = ? 
                                WHERE id = ?";
/*
SELECT tm.name, count(r.id) as n_report
FROM report as r, team as tm
WHERE r.team = tm.id
group by r.team
*/
?>