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

/* read owner */
$query_name = "SELECT Name_s " .
         "FROM Users " .
         "WHERE Users.Username = '{$_SESSION['Username']}'";
$result = mysql_query($query_name);
$owner = mysql_fetch_array($result)[0];

/* read current max resource id */
$query_res_id = "SELECT MAX(Res_ID) FROM Resources";
$result_id = mysql_query($query_res_id);
$res_id = mysql_fetch_array($result_id)[0] + 1;
// if (!$result) {
//     $res_id = 1;
// } else {
//     $res_id = mysql_fetch_array($result_id)[0] + 1;
// }
// $capacities = array();

/* if form was submitted, then save new data */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $res_name = mysql_real_escape_string($_POST['resource_name']);
    $pri_esf = mysql_real_escape_string($_POST['primary_esf']);
    $add_esf_array = $_POST['additional_esf'];
    $model = mysql_real_escape_string($_POST['model']);
    $caps = mysql_real_escape_string($_POST['capacities']);

    $lat = mysql_real_escape_string($_POST['latitude']);
    $long = mysql_real_escape_string($_POST['longitude']);
    $price = mysql_real_escape_string($_POST['price']);
    $unit = mysql_real_escape_string($_POST['cost_unit']);

    /* Add resource */
    $query_res = "INSERT INTO RESOURCES VALUES " .
             "('{$_SESSION['Username']}', '$res_id', '$res_name', '$pri_esf', " .
             "'$model', '$lat', '$long', 'AVAILABLE', '$price', '$unit')";
    mysql_query($query_res);

    $add_esf_cts = count($add_esf_array);
    for ($x = 0; $x < $add_esf_cts; $x++) {
        $add_esf = $add_esf_array[$x];
        $query_add_esf = "INSERT INTO RES_ADDI_ESF VALUES " .
                        " ('$res_id', '$add_esf')";
        mysql_query($query_add_esf);
    }

    // $query_caps = "INSERT INTO RES_CAP VALUES ('$res_id', '$caps')";

    mysql_query($query_add_esf);
    mysql_query($query_caps);

    header('Location: main_menu.php');
    exit();

    // if (!mysql_query($query_res) || !mysql_query($query_add_esf) || mysql_query($query_caps)) {
    //     print '<p class="error">Error: Failed to add a rescource. ' . mysql_error() . '</p>';
    // } else {
    //     header('Location: main_menu.php');
    //     exit();
    // }
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
        <title>ERMS Add Rescource</title>
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
                    <li class="selected"><a href="add_resources.php">Add Resources</a></li>
                    <li><a href="add_incidents.php">Add Incidents</a></li>
                    <li><a href="search_resources.php">Search Rescources</a></li>
                    <li><a href="resource_status.php">Resource Status</a></li>
                    <li><a href="resource_report.php">Resource Report</a></li>
                    <li><a href="exit.php">Exit</a></li>
                </ul>
            </div>

            <div class="center_content">

                <div class="center_left">
                    <div class="title_name">New Resource Info</div>

                    <div class="features">

                        <div class="add_resources_section">

                            <form name="add_resources" action="add_resources.php" method="post" id="form1">
                            <table width="85%">
                                <tr>
                                    <td class="heading">Resource ID</td>
                                    <td>
                                        <?php
                                            echo $res_id;
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="heading">Owner</td>
                                    <td>
                                        <?php
                                            echo $owner;
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="heading">Resource Name</td>
                                    <td>
                                        <input type="text" name="resource_name" class="ar_large" />
                                    </td>
                                </tr>

                                <tr>
                                    <td class="heading">Primary ESF</td>
                                    <td>
                                        <?php
                                            $query_esf = "SELECT ESF_ID_Desc FROM ESF ORDER BY ESF_ID_Desc";
                                            $result_esf = mysql_query($query_esf);
                                            echo '<select name="primary_esf" class="ar_large">';
                                                while ($esf = mysql_fetch_array($result_esf)) {
                                                    echo '<option value="'.$esf[0].'">'.$esf[0].'</option>';
                                                }
                                            echo '</select>';
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="heading">Additional ESF</td>
                                    <td>
                                        <?php
                                            $query_esf = "SELECT ESF_ID_Desc FROM ESF ORDER BY ESF_ID_Desc";
                                            $result_esf = mysql_query($query_esf);
                                            echo '<select multiple="multiple" name="additional_esf[]" class="ar_large">';
                                                while ($esf = mysql_fetch_array($result_esf)) {
                                                    echo '<option value="'.$esf[0].'">'.$esf[0].'</option>';
                                                }
                                            echo '</select>';
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="heading">Model</td>
                                    <td>
                                        <input type="text" name="model" class="ar_large" />
                                    </td>
                                </tr>
                            </table>

                            &nbsp;
                            <!-- <div class="clear"></div> -->

                            <table width="85%">
                                <tr>
                                    <td class="heading">Capacities</td>
                                    <td>
                                        <input type="text" name="capacities" class="ar_large" />
                                    </td>
                                </tr>
                            </table>

                            &nbsp;
                            <!-- <div class="clear"></div> -->

                            <table width="85%">
                                <tr>
                                    <td class="heading">Latitude</td>
                                    <td>
                                        <input type="text" name="latitude" class="ar_small" />
                                    </td>
                                    <td class="heading">Longitude</td>
                                    <td>
                                        <input type="text" name="longitude" class="ar_small" />
                                    </td>

                                <tr>
                                    <td class="heading">Cost</td>
                                    <td>
                                        $ <input type="text" name="price" class="ar_smaller" />
                                    </td>
                                    <td class="heading">Unit</td>
                                    <td>
                                        <?php
                                            $query_unit = "SELECT Unit FROM COST_UNIT";
                                            $result_unit = mysql_query($query_unit);
                                            echo '<select name="cost_unit" class="ar_small">';
                                                while ($unit = mysql_fetch_array($result_unit)) {
                                                    echo '<option value="'.$unit[0].'">'.$unit[0].'</option>';
                                                }
                                            echo '</select>';
                                        ?>
                                    </td>
                                </tr>

                            </table>

                            <div class="clear"><br/></div>

                            <a href="javascript:add_resources.submit();" class="fancy_button">save</a>
                            <a href="main_menu.php" class="fancy_button">cancel</a>

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