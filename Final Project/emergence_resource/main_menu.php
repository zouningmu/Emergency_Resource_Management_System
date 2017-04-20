<?php

/* connect to database */
$connect = mysql_connect("localhost:3306", "gtuser", "gtuser123");
if (!$connect) {
    die("Failed to connect to database");
}
mysql_select_db("emergency_resource") or die( "Unable to select database");

session_start();
if (!isset($_SESSION['Username'])) {
    header('Location: login.php');
    exit();
}

$query0 = "SELECT  Name_s, Job_title, Date_hired " .
		 "FROM USERS " .
		 "INNER JOIN INDIVIDUAL ON USERS.Username = INDIVIDUAL.Username " .
		 "WHERE USERS.Username = '{$_SESSION['Username']}'";
$result0 = mysql_query($query0);
$row0 = mysql_fetch_array($result0);

$query1 = "SELECT USERS.Name_s, GOV_AGENCY.Jurisdiction " .
		 "FROM USERS " .
		 "INNER JOIN GOV_AGENCY ON USERS.username=GOV_AGENCY.Username " .
		 "WHERE USERS.Username = '{$_SESSION['Username']}'";
$result1 = mysql_query($query1);
$row1 = mysql_fetch_array($result1);

$query2 = "SELECT USERS.Name_s, COMPANY.Headquarter " .
		 "FROM USERS " .
		 "INNER JOIN COMPANY ON USERS.Username=COMPANY.Username " .
		 "WHERE USERS.Username = '{$_SESSION['Username']}'";
$result2 = mysql_query($query2);
$row2 = mysql_fetch_array($result2);

$query3 = "SELECT  USERS.Name_s, MUNICIPALITY.Population_size " .
		 "FROM USERS " .
		 "INNER JOIN MUNICIPALITY ON USERS.username=MUNICIPALITY.username " .
		 "WHERE USERS.Username = '{$_SESSION['Username']}'";
$result3 = mysql_query($query3);
$row3 = mysql_fetch_array($result3);

if ($row0) {
	$row = $row0;
    $row[1] = 'Job: ' . ' ' . $row[1];
} elseif ($row1) {
	$row = $row1;
    $row[1] = 'Jurisdiction: ' . ' ' . $row[1];
} elseif ($row2) {
	$row = $row2;
    $row[1] = 'Headquarter: ' . ' ' . $row[1];
} else {
	$row = $row3;
    $row[1] = 'Population: ' . ' ' . $row[1];
}
// $row[0] = 'User: ' . ' ' . $row[0];

if (!$row) {
	print "<p>Error: No data returned from database.  Administrator login NOT supported.</p>";
	print "<a href='logout.php'>Logout</a>";
	exit();
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>ERMS Menu</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>

	<body>

		<div id="main_container">

			<div id="header">
				<div class="logo"><img src="images/ambulance.png" border="0" alt="" title="" /></div>
			</div>

            <div class="menu">
                <ul>
                    <li class="selected"><a href="main_menu.php">Home</a></li>
                    <li><a href="add_resources.php">Add Resources</a></li>
                    <li><a href="add_incidents.php">Add Incidents</a></li>
                    <li><a href="search_resources.php">Search Rescources</a></li>
                    <li><a href="resource_status.php">Resource Status</a></li>
                    <li><a href="resource_report.php">Resource Report</a></li>
                    <li><a href="exit.php">Exit</a></li>
                </ul>
            </div>


            <div class="center_content">

                <div class="center_left">

					<div class="title_name">
                        <?php print $row[0] . '<br>' . $row[1]; ?>
                    </div>

<!-- 					<div>
						<a href="exit.php" class="fancy_button">Exit</a>
					</div> -->

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