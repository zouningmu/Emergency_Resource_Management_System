<?php

/* connect to database */
$connect = mysql_connect("localhost:3306", "gtuser", "gtuser123");
if (!$connect) {
    die("Failed to connect to database");
}
mysql_select_db("emergency_resource") or die( "Unable to select database");

session_start();
if (!isset($_SESSION['Username'])) {
    header('Location: main_menu.php');
    exit();
}

$query_resources_in_use = "SELECT R.Res_ID, Res_name, Description, USERS.Name_s, Start_date, Return_by " .
         "FROM RESOURCES AS R " .
         "INNER JOIN USERS ON USERS.Username = R.Username " .
         "INNER JOIN REQUEST AS RE ON R.Res_ID = RE.Res_ID " .
         "INNER JOIN INCIDENT AS I ON I.Inc_ID = RE.Inc_ID " .
         "Where I.Username = '{$_SESSION['Username']}' AND R.Status_s = 'IN USE' " .
         "ORDER BY R.Res_ID";

$result_resources_in_use = mysql_query($query_resources_in_use);
if (!$result_resources_in_use) {
    print "<p>Error: " . mysql_error() . "</p>";
    exit();
}

$query_resources_requested_by_me = "SELECT R.Res_ID, R.Res_name, I.Description, USERS.Name_s, RE.Return_by " .
                                   "FROM RESOURCES AS R " .
                                   "INNER JOIN USERS ON USERS.Username = R.Username " .
                                   "INNER JOIN REQUEST AS RE ON R.Res_ID = RE.Res_ID " .
                                   "INNER JOIN INCIDENT AS I ON I.Inc_ID = RE.Inc_ID " .
                                   "WHERE I.Username = '{$_SESSION['Username']}' AND R.Status_s != 'IN USE' " .
                                   "ORDER BY R.Res_ID";

$result_resources_requested_by_me = mysql_query($query_resources_requested_by_me);
if (!$result_resources_requested_by_me) {
    print "<p>Error: " . mysql_error() . "</p>";
    exit();
}

$query_resource_requests_received_by_me = "SELECT R.Res_ID, R.Res_name, I.Description, USERS.Name_s, RE.Return_by " .
                                          "FROM RESOURCES AS R " .
                                          "INNER JOIN USERS ON USERS.Username = R.Username " .
                                          "INNER JOIN REQUEST AS RE ON R.Res_ID = RE.Res_ID " .
                                          "INNER JOIN INCIDENT AS I ON I.Inc_ID = RE.Inc_ID " .
                                          "WHERE R.Username = '{$_SESSION['Username']}' " .
                                          "ORDER BY RE.Return_by";


$result_resource_requests_received_by_me = mysql_query($query_resource_requests_received_by_me);
if (!$result_resource_requests_received_by_me) {
    print "<p>Error: " . mysql_error() . "</p>";
    exit();
}

$query_repair_scheduled = "SELECT R.Res_ID, R.Res_name, RE.Start_date, RE.Return_by " .
                          "FROM RESOURCES AS R " .
                          "INNER JOIN REPAIRS AS RE ON R.Res_ID = RE.Res_ID " .
                          "WHERE R.Username = '{$_SESSION['Username']}' AND R.Status_s = 'IN REPAIR'";


$result_repair_scheduled = mysql_query($query_repair_scheduled);
if (!$result_repair_scheduled) {
    print "<p>Error: " . mysql_error() . "</p>";
    exit();
}

/* return resource */
if (!empty($_GET['return_resource'])) {

    $Res_ID = mysql_real_escape_string($_GET['return_resource']);

    $query = "UPDATE RESOURCES AS R " .
             "SET R.Status_s = 'AVAILABLE' " .
             "WHERE R.Res_ID = $Res_ID";

    $result = mysql_query($query);
    if (!$result) {
        print '<p class="error">Error: ' . mysql_error() . '</p>';
        exit();
    }
}

/* cancel resources requested by me */
if (!empty($_GET['cancel_resource'])) {

    $Res_ID = mysql_real_escape_string($_GET['cancel_resource']);

    $query = "DELETE FROM REQUEST " .
             "WHERE REQUEST.Res_ID = $Res_ID";

    $result = mysql_query($query);
    if (!$result) {
        print '<p class="error">Error: ' . mysql_error() . '</p>';
        exit();
    }
}

/* deploy resource requests received by me */
if (!empty($_GET['deploy_resource'])) {

    $Res_ID = mysql_real_escape_string($_GET['deploy_resource']);

    $query = "UPDATE RESOURCES AS R " .
             "SET R.Status_s = 'IN USE' " .
             "WHERE R.Res_ID = $Res_ID";

    $result = mysql_query($query);
    if (!$result) {
        print '<p class="error">Error: ' . mysql_error() . '</p>';
        exit();
    }
}

/* reject resource requests received by me */
if (!empty($_GET['reject_resource'])) {

    $Res_ID = mysql_real_escape_string($_GET['reject_resource']);

    $query = "DELETE FROM REQUEST " .
             "WHERE REQUEST.Res_ID = $Res_ID";


    $result = mysql_query($query);
    if (!$result) {
        print '<p class="error">Error: ' . mysql_error() . '</p>';
        exit();
    }
}

/* cancel scheduled repair*/
if (!empty($_GET['cancel_repair'])) {

    $Res_ID = mysql_real_escape_string($_GET['cancel_repair']);

    $query = "UPDATE RESOURCES AS R " .
             "SET R.Status_s = 'AVAILABLE' " .
             "WHERE R.Res_ID = $Res_ID";

    $result = mysql_query($query);
    if (!$result) {
        print '<p class="error">Error: ' . mysql_error() . '</p>';
        exit();
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Emergency resouce status</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>

    <body>

        <div id="main_container">

            <div id="header">
                <div class="logo"><img src="images/ambulance.png" border="0" alt="" title="" /></div>
            </div>

            <div class="menu">
                <ul>
                    <li><a href="main_menu.php">Home</a></li>
                    <li><a href="add_resources.php">Add Resources</a></li>
                    <li><a href="add_incidents.php">Add Incidents</a></li>
                    <li><a href="search_resources.php">Search Rescources</a></li>
                    <li class="selected"><a href="resource_status.php">Resource Status</a></li>
                    <li><a href="resource_report.php">Resource Report</a></li>
                    <li><a href="exit.php">Exit</a></li>
                </ul>
            </div>

            <div class="center_content">

                <div class="center_left">
                    <div class="title_name">Resources in use</div>

                    <div class="features">

                        <div class="resouce_status_section">

                            <table>
                                <?php

                                $query_resources_in_use = "SELECT R.Res_ID, Res_name, Description, USERS.Name_s, Start_date, Return_by " .
                                         "FROM RESOURCES AS R " .
                                         "INNER JOIN USERS ON USERS.Username = R.Username " .
                                         "INNER JOIN REQUEST AS RE ON R.Res_ID = RE.Res_ID " .
                                         "INNER JOIN INCIDENT AS I ON I.Inc_ID = RE.Inc_ID " .
                                         "Where I.Username = '{$_SESSION['Username']}' AND R.Status_s = 'IN USE' " .
                                         "ORDER BY R.Res_ID";

                                $result_resources_in_use = mysql_query($query_resources_in_use);
                                if (!$result_resources_in_use) {
                                    print "<p class='error'>Error: " . mysql_error() . "</p>";
                                    exit();
                                }

                                $row_resources_in_use = mysql_fetch_array($result_resources_in_use);
                                if (!$row_resources_in_use) {
                                    print "<p>No data returned from database for resources in use.</p>";
                                } else {

                                    print "<tr>";
                                    print "<td class=\"heading\">ID</td>";
                                    print "<td class=\"heading\">Resource Name</td>";
                                    print "<td class=\"heading\">Incident</td>";
                                    print "<td class=\"heading\">Owner</td>";
                                    print "<td class=\"heading\">Start Date</td>";
                                    print "<td class=\"heading\">Return by</td>";
                                    print "<td class=\"heading\">Description</td>";
                                    print "<td class=\"heading\">Action</td>";
                                    print "</tr>";

                                    do {
                                        print "<tr>";
                                        print "<td>{$row_resources_in_use['Res_ID']}</td>";
                                        print "<td>{$row_resources_in_use['Res_name']}</td>";
                                        print "<td>{$row_resources_in_use['Description']}</td>";
                                        print "<td>{$row_resources_in_use['Name_s']}</td>";
                                        print "<td>{$row_resources_in_use['Start_date']}</td>";
                                        print "<td>{$row_resources_in_use['Return_by']}</td>";
                                        print "<td>{$row_resources_in_use['Description']}</td>";
                                        print '<td><a href="resource_status.php?return_resource=' . urlencode($row_resources_in_use['Res_ID']) . '">Return</a></td>';
                                        print "</tr>";
                                    } while ($row_resources_in_use = mysql_fetch_array($result_resources_in_use));
                                }

                                ?>

                            </table>


                        </div>

                     </div>

                </div>

                <div class="center_left">
                    <div class="title_name">Resources Requested by me</div>

                    <div class="features">

                        <div class="resouce_status_section">

                            <table>
                                <?php

                                $query_resources_requested_by_me = "SELECT R.Res_ID, R.Res_name, I.Description, USERS.Name_s, RE.Return_by " .
                                                                   "FROM RESOURCES AS R " .
                                                                   "INNER JOIN USERS ON USERS.Username = R.Username " .
                                                                   "INNER JOIN REQUEST AS RE ON R.Res_ID = RE.Res_ID " .
                                                                   "INNER JOIN INCIDENT AS I ON I.Inc_ID = RE.Inc_ID " .
                                                                   "WHERE I.Username = '{$_SESSION['Username']}' AND R.Status_s != 'IN USE' " .
                                                                   "ORDER BY R.Res_ID";

                                $result_resources_requested_by_me = mysql_query($query_resources_requested_by_me);
                                if (!$result_resources_requested_by_me) {
                                    print "<p>Error: " . mysql_error() . "</p>";
                                    exit();
                                }

                                $row_resource_requested_by_me = mysql_fetch_array($result_resources_requested_by_me);
                                if (!$row_resource_requested_by_me) {
                                    print "<p>No data returned from database for resources requested by me.</p>";
                                } else {

                                    print "<tr>";
                                    print "<td class=\"heading\">ID</td>";
                                    print "<td class=\"heading\">Resource Name</td>";
                                    print "<td class=\"heading\">Incident</td>";
                                    print "<td class=\"heading\">Owner</td>";
                                    print "<td class=\"heading\">Return by</td>";
                                    print "<td class=\"heading\">Action</td>";
                                    print "</tr>";

                                    do {
                                        print "<tr>";
                                        print "<td>{$row_resource_requested_by_me['Res_ID']}</td>";
                                        print "<td>{$row_resource_requested_by_me['Res_name']}</td>";
                                        print "<td>{$row_resource_requested_by_me['Description']}</td>";
                                        print "<td>{$row_resource_requested_by_me['Name_s']}</td>";
                                        print "<td>{$row_resource_requested_by_me['Return_by']}</td>";
                                        print '<td><a href="resource_status.php?cancel_resource=' . urlencode($row_resource_requested_by_me['Res_ID']) . '">Cancel</a></td>';
                                        print "</tr>";
                                    } while ($row_resource_requested_by_me = mysql_fetch_array($result_resources_requested_by_me));
                                }

                                ?>

                            </table>


                        </div>

                     </div>

                </div>

                <div class="center_left">
                    <div class="title_name">Resource Request received by me</div>

                    <div class="features">

                        <div class="resouce_status_section">

                            <table>
                                <?php

                                $query_resource_requests_received_by_me = "SELECT R.Res_ID, R.Res_name, I.Description, USERS.Name_s, RE.Return_by " .
                                          "FROM RESOURCES AS R " .
                                          "INNER JOIN USERS ON USERS.Username = R.Username " .
                                          "INNER JOIN REQUEST AS RE ON R.Res_ID = RE.Res_ID " .
                                          "INNER JOIN INCIDENT AS I ON I.Inc_ID = RE.Inc_ID " .
                                          "WHERE R.Username = '{$_SESSION['Username']}' " .
                                          "ORDER BY RE.Return_by";


                                $result_resource_requests_received_by_me = mysql_query($query_resource_requests_received_by_me);
                                if (!$result_resource_requests_received_by_me) {
                                    print "<p>Error: " . mysql_error() . "</p>";
                                    exit();
                                }

                                $row_resource_requests_received_by_me = mysql_fetch_array($result_resource_requests_received_by_me);
                                if (!$row_resource_requests_received_by_me) {
                                    print "<p>No data returned from database for resource requestes received by me.</p>";
                                } else {

                                    print "<tr>";
                                    print "<td class=\"heading\">ID</td>";
                                    print "<td class=\"heading\">Resource Name</td>";
                                    print "<td class=\"heading\">Incident</td>";
                                    print "<td class=\"heading\">Requested By</td>";
                                    print "<td class=\"heading\">Return by</td>";
                                    print "<td class=\"heading\">Action</td>";
                                    print "</tr>";

                                    do {
                                        print "<tr>";
                                        print "<td>{$row_resource_requests_received_by_me['Res_ID']}</td>";
                                        print "<td>{$row_resource_requests_received_by_me['Res_name']}</td>";
                                        print "<td>{$row_resource_requests_received_by_me['Description']}</td>";
                                        print "<td>{$row_resource_requests_received_by_me['Name_s']}</td>";
                                        print "<td>{$row_resource_requests_received_by_me['Return_by']}</td>";
                                        print '<td><a href="resource_status.php?deploy_resource=' . urlencode($row_resource_requests_received_by_me['Res_ID']) . '">Deploy</a>
                                                    &nbsp;
                                                   <a href="resource_status.php?reject_resource=' . urlencode($row_resource_requests_received_by_me['Res_ID']) . '">Reject</a> </td>';
                                        print "</tr>";
                                    } while ($row_resource_requests_received_by_me = mysql_fetch_array($result_resource_requests_received_by_me));
                                }

                                ?>

                            </table>


                        </div>

                     </div>

                </div>

                <div class="center_left">
                    <div class="title_name">Repairs Scheduled/In-progress</div>

                    <div class="features">

                        <div class="resouce_status_section">

                            <table>
                                <?php

                                $query_repair_scheduled = "SELECT R.Res_ID, R.Res_name, RP.Start_date, RP.Return_by " .
                                                          "FROM RESOURCES AS R " .
                                                          "INNER JOIN REPAIRS AS RP ON R.Res_ID = RP.Res_ID " .
                                                          "WHERE R.Username = '{$_SESSION['Username']}' AND R.Status_s = 'IN REPAIR'";


                                $result_repair_scheduled = mysql_query($query_repair_scheduled);
                                if (!$result_repair_scheduled) {
                                    print "<p>Error: " . mysql_error() . "</p>";
                                    exit();
                                }

                                $row_repair_scheduled = mysql_fetch_array($result_repair_scheduled);
                                if (!$row_repair_scheduled) {
                                    print "<p>No data returned from database for resources requested by me.</p>";
                                } else {

                                    print "<tr>";
                                    print "<td class=\"heading\">ID</td>";
                                    print "<td class=\"heading\">Resource Name</td>";
                                    print "<td class=\"heading\">Start on</td>";
                                    print "<td class=\"heading\">Ready by</td>";
                                    print "<td class=\"heading\">Action</td>";
                                    print "</tr>";

                                    do {
                                        print "<tr>";
                                        print "<td>{$row_repair_scheduled['Res_ID']}</td>";
                                        print "<td>{$row_repair_scheduled['Res_name']}</td>";
                                        print "<td>{$row_repair_scheduled['Start_date']}</td>";
                                        print "<td>{$row_repair_scheduled['Return_by']}</td>";
                                        print '<td><a href="resource_status.php?cancel_repair=' . urlencode($row_repair_scheduled['Res_ID']) . '">Cancel</a></td>';
                                        print "</tr>";
                                    } while ($row_repair_scheduled = mysql_fetch_array($result_repair_scheduled));
                                }

                                ?>

                            </table>


                        </div>

                     </div>

                </div>

                <div class="clear"></div>

            </div>


            <div id="footer">
                <div class="left_footer">6400 Fall16 Team028</div>
                <div class="right_footer">Template by:<a href="http://csstemplatesmarket.com" target="_blank">CSS Templates Market</a></div>
            </div>


        </div>

    </body>
</html>