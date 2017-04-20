<?php

/* connect to database */
$connect = mysql_connect("localhost:3306", "gtuser", "gtuser123");
if (!$connect) {
    die("Failed to connect to database");
}
mysql_select_db("emergency_resource") or die( "Unable to select database");

$errorMsg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST['Username']) or empty($_POST['Password'])) {
        $errorMsg = "Please provide both Username and Password.";
    }
    else {

        $Username = mysql_real_escape_string($_POST['Username']);
        $Password = mysql_real_escape_string($_POST['Password']);

        $query = "SELECT * FROM users WHERE Username = '$Username' AND Password_s = '$Password'";
        $result = mysql_query($query);

        if (mysql_num_rows($result) == 0) {
            /* login failed */
            $errorMsg = "Login failed.  Please try again.";

        }
        else {
            /* login successful */
            session_start();
            $_SESSION['Username'] = $Username;

            /* redirect to the profile page */
            header('Location: main_menu.php');
            exit();
        }

    }

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <title>Login to ERMS</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>

    <body>

        <div id="main_container">

            <div class="center_content">

                <div class="text_box">

                    <form name="login" action="login.php" method="post">

                        <div class="title">ERMS - Emergency Resource Management System</div>
                            <div class="login_form_row">
                            <label class="login_label">Username</label>
                            <input type="text" name="Username" class="login_input" />
                        </div>

                        <div class="login_form_row">
                            <label class="login_label">Password</label>
                            <input type="password" name="Password" class="login_input" />
                        </div>

                        <a href="javascript:login.submit();" class="fancy_button">Login</a>

                    <form/>

                    <?php
                    if (!empty($errorMsg)) {
                        print "<div class='login_form_row' style='color:red'>$errorMsg</div>";
                    }
                    ?>

                </div>

                <div class="clear"><br/></div>

            </div>

            <div id="footer">
                <div class="left_footer">6400 Fall16 Team028</div>
                <div class="right_footer">Template by:<a href="http://csstemplatesmarket.com" target="_blank">CSS Templates Market</a></div>
            </div>

        </div>
    </body>
</html>