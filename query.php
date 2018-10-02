<?php


abstract class ReportState
{
    const InAttesa = 'In attesa';
    const InLavorazione = 'In lavorazione';
    const Finito = 'Finito';
    
}


const QUERY_REPORT_BY_CITY =  "SELECT r.id, r.address, r.description, r.state, r.grade, r.user, t.name, l.lan, l.lng, c.name
                                FROM city as c, report as r, cdt, type_report as t, location as l
                                WHERE   r.location      = l.id
                                    AND r.city          = c.id
                                    AND r.type_report   = t.id
                                    AND c.name          = ?
                                GROUP BY r.id
                                ORDER BY r.id DESC";

const QUERY_REPORT_BY_CDT =  "SELECT r.id, r.address, r.description, r.state, r.grade, r.user, t.name, l.lan, l.lng, c.name
                                FROM city as c, report as r, cdt, type_report as t, location as l
                                WHERE   r.location      = l.id
                                    AND r.city          = c.id
                                    AND r.type_report   = t.id
                                    AND cdt.report		= r.id
                                    AND cdt.code        = ?
                                    ";

const QUERY_REPORT_BY_TEAM = "SELECT r.id, r.address, r.description, r.state, r.grade, r.user, t.name, l.lan, l.lng, c.name, tm.name
                                FROM city as c, report as r, cdt, type_report as t, location as l, team as tm
                                WHERE   r.location      = l.id
                                    AND r.city          = c.id
                                    AND r.type_report   = t.id
                                    AND r.team          = tm.id
                                    AND tm.name         = ?
                                    GROUP BY r.id
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

const QUERY_PHOTOS_BY_REPORT = "SELECT name
                                 FROM photo as p
                                 WHERE report = ?";

                                 
const QUERY_NEW_CDT         = "INSERT INTO cdt  (code, report)
                                    VALUES( ? , ? )";

const QUERY_FETCH_CDT       = " SELECT c.code
                                 FROM cdt as c
                                 WHERE c.code = ?";
// //SELECT tm.name, count(r.id)
// FROM report as r, team as tm
// WHERE r.team = tm.id
// group by r.team
?>