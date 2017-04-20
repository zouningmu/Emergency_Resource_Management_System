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

if (isset($_SESSION['Repair'])) {
    $Res_ID = $_SESSION['Repair'];
    unset($_SESSION['Repair']);
} else {
    $Res_ID = $_GET['Repair'];
    $_SESSION['Repair'] = $Res_ID;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $Start_date = $_POST['Start_date'];
    $Return_by = $_POST['Return_by'];

    $query1 = "UPDATE RESOURCES " .
             "SET Status_s = 'IN REPAIR' " .
             "WHERE Res_ID = $Res_ID";

    $result1 = mysql_query($query1);

    $query = "INSERT INTO REPAIRS " .
             "VALUES ('$Res_ID', '$Start_date', '$Return_by')";

    $result = mysql_query($query);
    if (!$result) {
        print '<p class="error">Error: ' . mysql_error() . '</p>';
        exit();    
    } else {
        header('Location: search_resources.php');
        exit();
    }
}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>ERMS repair in search_resources</title>
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
                    <li class="selected"><a href="search_resources.php">Search Rescources</a></li>
                    <li><a href="resource_status.php">Resource Status</a></li>
                    <li><a href="resource_report.php">Resource Report</a></li>
                    <li><a href="exit.php">Exit</a></li>
                </ul>
            </div>

            <div class="center_content">

                <div class="center_left">
                    <div class="title_name">New Repair Info</div>

                    <div class="features">

                        <div class="repair_search_resources_section">

                            <form name="repair_search_resources" action="repair_search_resources.php" method="post" id="form1">
                            <table width="85%">
                                <tr>
                                    <td class="heading">Resource ID</td>
                                    <td>
                                        <?php
                                            echo $Res_ID;
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="heading">Start date</td>
                                    <td>
                                        <input type="text" name="Start_date" class="ar_large" />
                                    </td>
                                </tr>

                                <tr>
                                    <td class="heading">Return by</td>
                                    <td>
                                        <input type="text" name="Return_by" class="ar_large" />
                                    </td>
                                </tr>
                        
                            </table>

                            <div class="clear"><br/></div>

                            <a href="javascript:repair_search_resources.submit();" class="fancy_button">save</a>
                            <a href="search_resources.php" class="fancy_button">cancel</a>

                            </form>

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