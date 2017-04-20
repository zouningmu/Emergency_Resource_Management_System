<?php

/* connect to database */
$connect = mysql_connect("localhost:3306", "gtuser", "gtuser123");
if (!$connect) {
    die("Failed to connect to database");
}
mysql_select_db("emergency_resource") or die( "Unable to select database");

session_start();
// $_SESSION['email'] = "michael@bluthco.com";
if (!isset($_SESSION['Username'])) {
    header('Location: main_menu.php');
    exit();
}

/* read current max incident id */
$query_id = "SELECT MAX(Inc_ID) FROM INCIDENT";
$result_id = mysql_query($query_id);
$inc_id = mysql_fetch_array($result_id)[0] + 1;
// if (!$result) {
//     $inc_id = 1;
// } else {
//     $inc_id = mysql_fetch_array($result_id)[0] + 1;
// }

/* if form was submitted, then save new data */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // /* validate form */
    // if (empty($_POST['inc_date'])) {
    //     $dateErr = "Date is required.";
    // } elseif (is_date($_POST['inc_data'])) {
    //     $inc_date = $_POST['inc_date'];
    // } else {
    //     $dateErr = "Not a valid date.";
    //     $inc_date = '';
    // }

    // if (empty($_POST['latitude'])) {
    //     $latErr = "Latitude is required.";
    // } else {
    //     $lat = mysql_real_escape_string($_POST['latitude']);
    // }

    // if (empty($_POST['longitude'])) {
    //     $longErr = "Longitude is required.";
    // } else {
    //     $long = mysql_real_escape_string($_POST['longitude']);
    // }

    $inc_date = $_POST['inc_date'];
    $lat = mysql_real_escape_string($_POST['latitude']);
    $long = mysql_real_escape_string($_POST['longitude']);
    $desc = mysql_real_escape_string($_POST['description']);

    /* add incident */
    $query_inc = "INSERT INTO INCIDENT VALUES " .
            "('{$_SESSION['Username']}', '$inc_id', '$inc_data', " .
            "'$desc', '$lat', '$long')";

    $result = mysql_query($query_inc);
    if (!$result) {
        print '<p class="error">Error: Failed to add a rescource. ' . mysql_error() . '</p>';
    } else {
        header('Location: main_menu.php');
        exit();
    }
}

function is_date( $str ) {
    $stamp = strtotime( $str );
    if (!is_numeric($stamp)) {
        return false;
    }
    $month = date( 'm', $stamp );
    $day   = date( 'd', $stamp );
    $year  = date( 'Y', $stamp );

    if (checkdate($month, $day, $year)) {
        return true;
    }
    return false;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>ERMS Add Incident</title>
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
                    <li class="selected"><a href="add_incidents.php">Add Incidents</a></li>
                    <li><a href="search_resources.php">Search Rescources</a></li>
                    <li><a href="resource_status.php">Resource Status</a></li>
                    <li><a href="resource_report.php">Resource Report</a></li>
                    <li><a href="exit.php">Exit</a></li>
                </ul>
            </div>

            <div class="center_content">

                <div class="center_left">
                    <div class="title_name">New Incident Info</div>

                    <div class="features">

                        <div class="add_incidents_section">

                            <form name="add_incidents" action="add_incidents.php" method="post" id="form1">

                            <table width="85%">
                                <tr>
                                    <td class="item_label">Incident ID</td>
                                    <td>
                                        <?php
                                            echo $inc_id;
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="item_label">Date</td>
                                    <td>
                                        <input type="text" name="inc_date" class="ar_small" />
                                    </td>
                                </tr>


                                <tr>
                                    <td class="item_label">Description</td>
                                    <td>
                                        <input type="text" name="description" class="ar_large" size="150"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="item_label">Latitude</td>
                                    <td>
                                        <input type="text" name="latitude" class="ar_small" />
                                    </td>
                                </tr>

                                <tr>
                                    <td class="item_label">Longitude</td>
                                    <td>
                                        <input type="text" name="longitude" class="ar_small" />
                                    </td>
                                </tr>

                            <div class="clear"></div>

                            </table>

                            <a href="javascript:add_incidents.submit();" class="fancy_button">save</a>
                            <a href="main_menu.php" class="fancy_button">cancel</a>

                            </form>

                        </div>

<!--                         <?php
                            if ($result) {
                                print "<div> Success </div>";
                            }
                        ?> -->

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