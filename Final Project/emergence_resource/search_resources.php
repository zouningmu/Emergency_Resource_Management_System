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

if (isset($_SESSION['Request'])) {
    unset($_SESSION['Request']);
}

if (isset($_SESSION['Inc'])) {
    unset($_SESSION['Inc']);
}

/* read owner */
$query_name = "SELECT Name_s " .
         "FROM Users " .
         "WHERE Users.Username = '{$_SESSION['Username']}'";
$result = mysql_query($query_name);
$owner = mysql_fetch_array($result)[0];

unset($result);

/* if form was submitted, then execute query to search for resources */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$inputKeyword = mysql_real_escape_string($_POST['Keyword']);
	$ESF = mysql_real_escape_string($_POST['SelectESF']);
	$Distance = mysql_real_escape_string($_POST['targetDistance']);
	$Incident_des = mysql_real_escape_string($_POST['selectIncident']);

	$query_search_resources = "SELECT R.Res_ID, R.Res_name, Username, Model, Prim_ESF, Home_lat,Home_long, Price, Cost_unit, Status_s, ADDESF.ID_Desc, Res_capability " .
			 "FROM resources AS R " .
			 "LEFT OUTER JOIN res_addi_esf AS ADDESF ON R.Res_ID = ADDESF.Res_ID " .
			 "LEFT OUTER JOIN res_cap AS CAP ON R.Res_ID = CAP.Res_ID " ;

	if (!empty($inputKeyword) or !empty($ESF) or !empty($Distance) and !empty($Incident_des)) {

  		$query_search_resources = $query_search_resources . "WHERE (1=0 " ;

		if (!empty($inputKeyword)) {
			$query_search_resources = $query_search_resources . " OR ( Res_name LIKE '%$inputKeyword%' OR Model LIKE '%$inputKeyword%' OR Res_capability LIKE '%$inputKeyword%' ) " ;
 		}
   		if (!empty($ESF)) {
			$query_search_resources = $query_search_resources . " AND ( Prim_ESF='$ESF' OR ADDESF.ID_Desc='$ESF' ) " ;
		}

			$query_incidentlong = "SELECT Longitude FROM incident WHERE incident.Description='$Incident_des' " ;
			$query_incidentlati = "SELECT Latitude FROM incident WHERE incident.Description='$Incident_des' " ;
			//$query_incidentID = "SELECT Inc_ID FROM incident WHERE incident.Description='$Incident_des' " ;
			$result_incidentlong = mysql_query($query_incidentlong);
			$result_incidentlati = mysql_query($query_incidentlati);
			//$result_incidentID  = mysql_query($query_incidentID );
			$incidentlong = mysql_fetch_array($result_incidentlong)[0];
			$incidentlati = mysql_fetch_array($result_incidentlati)[0];
			//$incidentID = mysql_fetch_array($result_incidentID)[0];

     	if (!empty($Distance)) {

			$query_search_resources = $query_search_resources . " AND 6371 * 2 * ATAN2( SQRT(POWER(SIN(RADIANS(Home_lat-'$incidentlati')/2), 2) " .
              "+ COS(RADIANS('$incidentlati'))*COS(RADIANS(Home_lat))*POWER(SIN(RADIANS(Home_long-'$incidentlong')/2), 2)) , SQRT(1-(POWER(SIN(RADIANS(Home_lat-'$incidentlati')/2), 2) " .
              "+ COS(RADIANS('$incidentlati'))*COS(RADIANS(Home_lat))*POWER(SIN(RADIANS(Home_long-'$incidentlong')/2), 2))))      <= '$Distance' " ;
		}

		$query_search_resources = $query_search_resources . ") ";
	}




	$query_search_resources = $query_search_resources . " ORDER BY ATAN2( SQRT(POWER(SIN(RADIANS(Home_lat-'$incidentlati')/2), 2) " .
              "+ COS(RADIANS('$incidentlati'))*COS(RADIANS(Home_lat))*POWER(SIN(RADIANS(Home_long-'$incidentlong')/2), 2)) , SQRT(1-(POWER(SIN(RADIANS(Home_lat-'$incidentlati')/2), 2) " .
              "+ COS(RADIANS('$incidentlati'))*COS(RADIANS(Home_lat))*POWER(SIN(RADIANS(Home_long-'$incidentlong')/2), 2)))) " ;  //need to change to distance

	//print '<p class="error">Final query = ' . $query . '</p>';

	$result_search_resources = mysql_query($query_search_resources);
	if (!$result_search_resources) {
		print '<p class="error">Error: ' . mysql_error() . '</p>';
		exit();
	}
	

}


//If Deploy button is clicked: Update resource; Input expected return date;
	
	
if (!empty($_GET['deploy_resource'])) {

    $Res_ID = mysql_real_escape_string($_GET['deploy_resource']);

    $query = "UPDATE RESOURCES " .
             "SET Status_s = 'IN USE' " .
             "WHERE Res_ID = $Res_ID " ;

    $result = mysql_query($query);
    if (!$result) {
        print '<p class="error">Error: ' . mysql_error() . '</p>';
        exit();
    }
}

    /*
//If Repair button is clicked: Update resource; Display Repair form; 
//Input repair start date ($Start_date) and available date ($Return_by)
if (!empty($_GET['repair_resource'])) {

    $Res_ID = mysql_real_escape_string($_GET['repair_resource']);

    $query = "UPDATE RESOURCES " .
             "SET Status_s = 'IN REPAIR' " .
             "WHERE Res_ID = $Res_ID " ;

    $result = mysql_query($query);
    if (!$result) {
        print '<p class="error">Error: ' . mysql_error() . '</p>';
        exit();
    }
}

//If Request button is clicked: Update resource and request; 
//Display Request form; Input request start date ($Start_date) and return date ($Return_by);
if (!empty($_GET['request_resource'])) {

    $Res_ID = mysql_real_escape_string($_GET['request_resource']);
	
    $query_incidentID = "SELECT Inc_ID FROM incident WHERE incident.Description='$Incident_des' " ;
	$result_incidentID  = mysql_query($query_incidentID );
	$incidentID = mysql_fetch_array($result_incidentID)[0];

	
	$query_currentdate="SELECT CURDATE() " ;
	$query_futuredate="SELECT DATE_ADD(CURDATE(), INTERVAL +10 DAY) " ;
	$result_currentdate = mysql_query($query_currentdate);
	$result_futuredate = mysql_query($query_futuredate);
	$Start_date =  mysql_fetch_array($result_currentdate)[0];
	$Return_by=  mysql_fetch_array($result_futuredate)[0];

	

    $query = "INSERT INTO REQUEST " .
			 "VALUES ($incidentID, $Res_ID, $Start_date, $Return_by) " ;

    $result = mysql_query($query);
    if (!$result) {
        print '<p class="error">Error: ' . mysql_error() . '</p>';
        exit();
    }
    
}

*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Search Resource</title>
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

					<div class="title_name">Search Resources</div>


					<div class="features">

						<div class="profile_section">

							<form name="searchform" action="search_resources.php" method="post">
							<table width="80%">


								<tr>
                                    <td class="heading">Keyword</td>
                                    <td>
                                        <input type="text" name="Keyword"/>
                                    </td>
                                </tr>

                               <tr>
                                    <td class="heading">ESF</td>
                                    <td>
                                        <?php
                                            $query_esf = "SELECT ESF_ID_Desc FROM ESF ORDER BY ESF_ID_Desc";
                                            $result_esf = mysql_query($query_esf);
                                            echo '<select name="SelectESF" class="ar_large">';
                                                while ($ShowESF = mysql_fetch_array($result_esf)) {
                                                    echo '<option value="'.$ShowESF[0].'">'.$ShowESF[0].'</option>';
                                                }
                                            echo '</select>';
                                        ?>
                                    </td>
                                </tr>


								<tr>
									<td class="heading">Location</td>
									<td>  Within <input type="number" name="targetDistance" /> Kilometers of incident </td>
								</tr>


								<tr>
                                    <td class="heading">Incident</td>
                                    <td>
                                        <?php
                                            $query_incident = "SELECT Description FROM incident WHERE Username='{$_SESSION['Username']}' ORDER BY Inc_ID";
                                            $result_incident = mysql_query($query_incident);
                                            echo '<select name="selectIncident" class="ar_large">';
                                                while ($Description = mysql_fetch_array($result_incident)) {
                                                    echo '<option value="'.$Description[0].'">'.$Description[0].'</option>';
                                                }
                                            echo '</select>';
                                        ?>
                                    </td>
                                </tr>

							</table>

							<a href="javascript:searchform.submit();" class="fancy_button">search</a>
                            <a href="main_menu.php" class="fancy_button">cancel</a>

							</form>


						</div>

						<?php
						if (isset($result_search_resources)) {

							print "<div class='profile_section'>";
							print "<div class='title_name'>Search Results</div>";
							print "<table width='80%'>";
							print "<tr><td class='heading'>ID</td>
							<td class='heading'>Name</td>
							<td class='heading'>Owner</td><td class='heading'>Cost</td>
							<td class='heading'>Status</td>
							<td class='heading'>Distance(Km)</td>
							<td class='heading'>Action</td></tr>";

							while ($search_data = mysql_fetch_array($result_search_resources)){

								//$Showdata=POW(SIN(30*M_PI/180),2);
								//$search_showdata = mysql_fetch_array($result_search_resources);

								$ShowDistance=6371 * 2 * ATAN2( SQRT(POW(SIN(($search_data['Home_lat']-$incidentlati)*M_PI/180/2), 2)+ COS($incidentlati*M_PI/180)*COS($search_data['Home_lat']*M_PI/180)*POW(SIN(($search_data['Home_long']-$incidentlong)*M_PI/180/2), 2)) ,
								SQRT(1-(POW(SIN(($search_data['Home_lat']-$incidentlati)*M_PI/180/2), 2)+ COS($incidentlati*M_PI/180)*COS($search_data['Home_lat']*M_PI/180)*POW(SIN(($search_data['Home_long']-$incidentlong)*M_PI/180/2), 2))));

								$ShowDistance_round=round($ShowDistance, 2);
								//$Showdata=$search_data['Home_lat']-$incidentlati;

                                $query_incidentID = "SELECT Inc_ID FROM incident WHERE incident.Description = '$Incident_des'";
                                $result_incidentID  = mysql_query($query_incidentID);
                                $Inc_ID = mysql_fetch_array($result_incidentID)['Inc_ID'];

								print "<tr>";
								print "<td>{$search_data['Res_ID']}</td>";
								print "<td>{$search_data['Res_name']}</td>";
								print "<td>{$search_data['Username']}</td>";
								print "<td>{$search_data['Price']}/{$search_data['Cost_unit']}</td>";
								print "<td>{$search_data['Status_s']}</td>";
								print "<td>{$ShowDistance_round}</td>";
								//print "<td>{$current_date}</td>";

                                if ($search_data['Status_s'] == 'IN REPAIR'){
                                    print "<td></td>";
                                } else if ($search_data['Username'] == $_SESSION['Username']){
                                    print '<td><a href="search_resources.php?deploy_resource=' . urlencode($search_data['Res_ID']) . '">Deploy</a>
                                            &nbsp;
                                            <a href="repair_search_resources.php?Repair=' . urldecode($search_data['Res_ID']) . '">Repair</a></td>';
                                } else {
                                        print '<td><a href="request_search_resources.php?Request=' . urldecode($search_data['Res_ID']) . '&Inc=' . urldecode($Inc_ID) . '">Request</a></td>'; 
                                }
                                print "</tr>";

							}

							print "</table>";
							print "</div>";

						}
						?>

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