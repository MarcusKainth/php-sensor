<?php
include('includes/functions.php');
/* Checks if user is already logged in */
session_start();
checkCookies();
if(isset($_SESSION["login"]) && $_SESSION["login"]) {
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>Settings | <?php echo title(); ?></title>
        <script src="https://www.google.com/jsapi" type="text/javascript"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <meta name="theme-color" content="#009688">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="manifest" href="includes/json/manifest.json">
        <meta name="apple-mobile-web-app-title" content="Arduino Sensor">
        <link rel="icon" sizes="192x192" href="includes/images/icons/launcher-icon-4x.png">
        <link rel="apple-touch-icon" href="includes/images/icons/launcher-icon-ios.png">
        <link id="stylesheet" href="includes/css/settings.css" type="text/css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" type="text/css" rel="stylesheet">
        <script>
            /* Checks if the device is running the webapp */
            function isRunningStandalone() {
                return ((window.matchMedia('(display-mode: standalone)').matches) || (navigator.standalone = navigator.standalone || (screen.height-document.documentElement.clientHeight<40)));
            }
            /* Changes CSS to webapp version also keeps user signed in */
            if (isRunningStandalone()) {
                document.getElementById("stylesheet").setAttribute("href", "includes/css/settingsMobile.css");
                $(window).scroll(function(){
                    $(".navbar").css("top",Math.max(8,55-$(this).scrollTop()));
                });
                $(document).ready(function() {
                    /* Adds 40px at top of page which is size 
                     * of status bar on iOS to stop overlapping
                     */
                    if (navigator.standalone = navigator.standalone || (screen.height-document.documentElement.clientHeight<40)) {
                        document.getElementById("ios-statusbar").style.background = "#00796b";
                        document.getElementById("ios-statusbar").style.height = "40px";
                    }
                });
            }
        </script>
    </head>
    <body>
        <header>
            <div id="ios-statusbar"></div>
            <div class="topbar">
                <div class="container">
                    <div class="mobile nav-btn">
                        <i class="material-icons">&#xE5D2;</i>
                    </div>
                    <div class="logo">
                        <a class="desktop" href="index.php">Arduino Settings</a>
                        <span class="mobile">Settings</span>
                    </div>
                    <div class="mobile more-vert">
                        <i class="material-icons">&#xE5D4;</i>
                    </div>
                    <div class="logged-in">Hello, <?php /* Users full name */ echo findUser(); ?> | <span><a href="logout.php">Logout</a></span>
                    </div>
                </div>
            </div>
            <div class="navbar">
                <div class="container">
                    <nav>
                        <ul>
                            <li><a href="monitor.php">HOME</a></li>
                            <li><a class="active" href="settings.php">SETTINGS</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
        <div class="container flex">
            <div class="main-heading">
                <div class="desktop main-left">
                    <h1>Settings</h1>
                    <h4>Profile specific settings</h4>
                </div>
            </div>
            <form action="settings.php" method="POST">
                <div class="row margin">
                    <input id="forename" type="text" name="forename" autocomplete="off" />
                    <label class="center-align" for="forename">
                        Forename
                    </label>
                </div>
                <div class="row margin">
                    <input id="lastname" type="text" name="lastname" autocomplete="off" />
                    <label class="center-align" for="lastname">
                        Lastname
                    </label>
                </div>
                <div class="row margin">
                    <input id="email" type="email" name="email" autocomplete="off" />
                    <label class="center-align" for="email">
                        Email
                    </label>
                </div>
                <div class="row margin">
                    <input id="password" type="password" name="password" autocomplete="off" />
                    <label class="center-align" for="password">
                        Password
                    </label>
                </div>
                <div class="row btn-row">
                    <input type="submit" value="Update" />
                </div>
            </form>
            <script>
                /* Causes labels to get smaller on focus */
                $("#forename").keyup(function(){
                    if( $(this).val() != ""){
                        $("label[for='forename']").addClass("active-label");
                    } else {
                        $("label[for='forename']").removeClass("active-label");
                    }
                });

                $("#lastname").keyup(function(){
                    if( $(this).val() != ""){
                        $("label[for='forename']").addClass("active-label");
                    } else {
                        $("label[for='forename']").removeClass("active-label");
                    }
                });

                $("#email").keyup(function(){
                    if( $(this).val() != ""){
                        $("label[for='email']").addClass("active-label");
                    } else {
                        $("label[for='email']").removeClass("active-label");
                    }
                });

                $("#password").keyup(function(){
                    if( $(this).val() != ""){
                        $("label[for='password']").addClass("active-label");
                    } else {
                        $("label[for='password']").removeClass("active-label");
                    }
                });
            </script>
        </div>
    </body>
</html>
<?php
    if ($_POST["forename"] || $_POST["lastname"] || $_POST["email"] || $_POST["password"]) {
        include('includes/connect.php');
        if ($_POST["forename"]) {
            $stmt = $mysqli->prepare("UPDATE Users SET FirstName = ? WHERE UserID = ?");
            $stmt->bind_param("si", $_POST["forename"], $_SESSION["login"]);
            /* Executes query */
            $stmt->execute();
        }

        if ($_POST["lastname"]) {
            $stmt = $mysqli->prepare("UPDATE Users SET LastName = ? WHERE UserID = ?");
            $stmt->bind_param("si", $_POST["lastname"], $_SESSION["login"]);
            /* Executes query */
            $stmt->execute();
        }

        if ($_POST["email"]) {
            $stmt = $mysqli->prepare("UPDATE Users SET Email = ? WHERE UserID = ?");
            $stmt->bind_param("si", $_POST["email"], $_SESSION["login"]);
            /* Executes query */
            $stmt->execute();
        }

        if ($_POST["password"]) {
            $stmt = $mysqli->prepare("UPDATE Users SET Password = ? WHERE UserID = ?");
            $hashedPassword = password_hash(base64_encode(hash('sha256', $password, true)), PASSWORD_BCRYPT);
            $stmt->bind_param("si", $hashedPassword, $_SESSION["login"]);
            /* Executes query */
            $stmt->execute();
        }
        header("Location: index.php");
    }
} else {
    /* If not logged in redirect to login page */
    header("Location: index.php");
}
?>
