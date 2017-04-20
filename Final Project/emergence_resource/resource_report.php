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

//total number of resources and total number of resources in use for each ESF
$query = "SELECT R.Prim_ESF, " .
         "(SELECT count(*) FROM RESOURCES WHERE Username = '{$_SESSION['Username']}' AND Prim_ESF = R.Prim_ESF) AS Total_Resources, " .
         "(SELECT count(*) FROM RESOURCES WHERE Username = '{$_SESSION['Username']}' AND Status_s = 'IN USE' AND Prim_ESF = R.Prim_ESF) AS Resources_In_Use " .
         "FROM RESOURCES AS R " .
         "GROUP BY R.Prim_ESF " .
         "ORDER BY R.Prim_ESF";


$result= mysql_query($query);
if (!$result) {
    print "<p>Error: " . mysql_error() . "</p>";
    exit();
}

//total number of resources
$query_tot = "SELECT count(*) AS Num_tot " .
             "FROM RESOURCES " .
             "WHERE Username = '{$_SESSION['Username']}'";

$result_tot = mysql_query($query_tot);
if (!$result_tot) {
    print "<p>Error: " . mysql_error() . "</p>";
    exit();
}

//total number of resources in use
$query_tot_in_use = "SELECT count(*) AS Num_use " .
                    "FROM RESOURCES " .
                    "WHERE Status_s = 'IN USE' AND Username = '{$_SESSION['Username']}' ";


$result_tot_in_use= mysql_query($query_tot_in_use);
if (!$result_tot_in_use) {
    print "<p>Error: " . mysql_error() . "</p>";
    exit();
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Resource Report</title>
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
                    <li><a href="resource_status.php">Resource Status</a></li>
                    <li class="selected"><a href="resource_report.php">Resource Report</a></li>
                    <li><a href="exit.php">Exit</a></li>
                </ul>
            </div>

            <div class="center_content">

                <div class="center_left">
                    <div class="title_name">Resource Report by Primary Emergency Support Function</div>

                    <div class="features">

                        <div class="resouce_report_section">

                            <table>
                                <?php

                               $query = "SELECT R.Prim_ESF, " .
                                        "(SELECT count(*) FROM RESOURCES WHERE Username = '{$_SESSION['Username']}' AND Prim_ESF = R.Prim_ESF) AS Total_Resources, " .
                                        "(SELECT count(*) FROM RESOURCES WHERE Username = '{$_SESSION['Username']}' AND Status_s = 'IN USE' AND Prim_ESF = R.Prim_ESF) AS Resources_In_Use " .
                                        "FROM RESOURCES AS R " .
                                        "GROUP BY R.Prim_ESF " .
                                        "ORDER BY R.Prim_ESF";

                                $result= mysql_query($query);
                                if (!$result) {
                                    print "<p>Error: " . mysql_error() . "</p>";
                                    exit();
                                }

                                $row = mysql_fetch_array($result);
                                if (!$row) {
                                    print "<p>No data returned from database for resources in use.</p>";
                                } else {

                                    print "<tr>";
                                    print "<td class=\"heading\">Primary Emergency Support Function</td>";
                                    print "<td class=\"heading\">Total Resources</td>";
                                    print "<td class=\"heading\">Resources in Use</td>";
                                    print "</tr>";

                                    do {
                                        print "<tr>";
                                        print "<td>{$row['Prim_ESF']}</td>";
                                        print "<td>{$row['Total_Resources']}</td>";
                                        print "<td>{$row['Resources_In_Use']}</td>";
                                        print "</tr>";
                                    } while ($row = mysql_fetch_array($result));

                                    //total number of resources
                                    $query_tot = "SELECT count(*) AS Num_tot " .
                                             "FROM RESOURCES " .
                                             "WHERE Username = '{$_SESSION['Username']}'";

                                    $result_tot = mysql_query($query_tot);
                                    if (!$result_tot) {
                                        print "<p>Error: " . mysql_error() . "</p>";
                                        exit();
                                    }
                                    $row_tot = mysql_fetch_array($result_tot);

                                    //total number of resources in use
                                    $query_tot_in_use = "SELECT count(*) AS Num_use " .
                                                        "FROM RESOURCES " .
                                                        "WHERE Status_s = 'IN USE' AND Username = '{$_SESSION['Username']}'";

                                    $result_tot_in_use= mysql_query($query_tot_in_use);
                                    if (!$result_tot_in_use) {
                                        print "<p>Error: " . mysql_error() . "</p>";
                                        exit();
                                    }
                                    $row_tot_in_use = mysql_fetch_array($result_tot_in_use);
                                    if (!$row_tot || !$row_tot_in_use) {
                                        print "<p>No data returned from database for resources in use.</p>";
                                    } else {
                                    print "<tr>";
                                    print "<td>Total</td>";
                                    print "<td>{$row_tot['Num_tot']}</td>";
                                    print "<td>{$row_tot_in_use['Num_use']}</td>";
                                    print "</tr>";
                                    }
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