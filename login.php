<?php
session_start();

require_once('Connections/dbConDelivery.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use your own query to fetch user data from the database
    $query = "SELECT * FROM staff WHERE username = '$username' AND password = '$password'";
    mysql_select_db($database_dbConDelivery, $dbConDelivery);
    $result = mysql_query($query);

    if ($result) {
        $row = mysql_fetch_assoc($result);

        // Check if a user with the provided credentials exists
        if ($row) {
            // Set session variables or perform other actions as needed
            $_SESSION['user_id'] = $row['staffID'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['name'] = $row['staffNM'];
            $_SESSION['role'] = $row['role'];

            // Redirect based on user's role
            switch ($_SESSION['role']) {
                case 'admin':
                    header("Location: adminDashboard.php");
                    exit();
                case 'rider':
                    header("Location: riderDashboard.php");
                    exit();
            }
        } else {
            echo '<div class="alert alert-danger" style="position:absolute;" role="alert">Invalid username or password</div>';
        }
    } else {
        echo "Error in query: " . mysql_error();
    }

}
?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>CMS | Login</title>

    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Archivo+Black&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat&amp;display=swap">
    <link rel="stylesheet" href="assets/css/multistep.css">
    <link rel="stylesheet"
        href="assets/css/-Fixed-Navbar-start-with-transparency-background-BS4--transparency-menu.css">
    <link rel="stylesheet" href="assets/css/Search-Input-Responsive-with-Icon.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="d-flex justify-content-center" style="height: 100vh; background: url(&quot;assets/img/04.jpg&quot;); background-size: 1136px 936px;
  background-repeat: repeat;">
    <form name="login"
        style="width: 350px;margin-left: auto;margin-right: auto;background: #ffffff;margin-top: auto;margin-bottom: auto;padding: 40px;border-radius: 10px;box-shadow: 1px 1px 18px 0px;"
        method="POST">
        <div class="row justify-content-center">
            <div class="col"><img src="assets/img/Logo%20Black.png">
                <h3 style="text-align: center;margin-top: 32px;margin-bottom: 14px;font-weight: bold;">Login</h3>
                <div><label class="form-label">Username</label><input id="username" class="form-control" type="text"
                        name="username"></div>
                <div style="margin-top: 10px"><label class="form-label">Password</label><input id="password"
                        class="form-control" type="password" name="password"><span class="eyes__login" id="eyes__login">
                        <i class='bx bx-hide' id="eye__hide-icon"></i>
                        <i class='bx bx-show-alt' id="eye__show-icon"></i>
                    </span></div>
                <div style="text-align: center;margin-top: 19px;margin-bottom: 14px;">
                    <button class="btn btn-primary" type="submit">Login</button>
                </div>
            </div>
        </div>
        <div style="text-align: center;"><a href="index.php">&lt; Return to homepage</a></div>
    </form>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/multistep.js"></script>
    <script src="assets/js/-Fixed-Navbar-start-with-transparency-background-BS4--transparency-menu.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>