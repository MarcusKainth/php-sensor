<?php
include('includes/functions.php');
session_start();
checkCookies();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="description" content="Login page for arduino sensor">
        <meta name="keywords" content="arduino, login, material">
        <meta name="theme-color" content="#3f51b5">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="manifest" href="includes/json/manifest.json">
        <meta name="apple-mobile-web-app-title" content="Arduino Sensor">
        <link rel="icon" sizes="192x192" href="includes/images/icons/launcher-icon-4x.png">
        <link rel="apple-touch-icon" href="includes/images/icons/launcher-icon-ios.png">
        <title>Login | <?php echo title(); ?></title>
        <link id="stylesheet" href="includes/css/login.css" type="text/css" rel="stylesheet">
        <!-- Most meta data is used for the webapps on both iOS and Android -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js" type="text/javascript"></script>
        <script>
            /* Checks if the device is running the webapp */
            function isRunningStandalone() {
                return ((window.matchMedia('(display-mode: standalone)').matches) || (navigator.standalone = navigator.standalone || (screen.height-document.documentElement.clientHeight<40)));
            }
            /* Changes CSS to webapp version also keeps user signed in */
            if (isRunningStandalone()) {
                document.getElementById("stylesheet").setAttribute("href", "includes/css/loginMobile.css");
                document.getElementById("rememberme").checked = true;
            }
        </script>
        <!-- Code below stops webapp from opening new tabs on hyperlinks -->
        <script>(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone")</script>
    </head>
    <body>
<?php
/* If logged in takes you to the monitor page */
if (isset($_SESSION["login"]) && $_SESSION["login"]) {
    header("Location: monitor.php");
} else {
/* Runs the login page */
?>
        <div class="login-wrapper">
            <div class="img">
                <h1 class="desktop-title">Login</h1>
                <h1 class="mobile-title">Arduino</h1>
                <h2 class="mobile-title">Temperature Sensor</h2>
            </div>
            <div class="inner-wrapper">
                <div class="error">
                    <p><?php /* Error message */ if(isset($_SESSION["error"]) && $_SESSION["error"]) { echo $_SESSION["error"]; } ?></p>
                </div>
                <form action="login.php" method="POST">
                    <div class="row margin">
                        <input id="username" type="text" name="username" autocomplete="off" required="" />
                        <label class="center-align" for="username">
                            Username
                        </label>
                    </div>
                    <div class="row margin">
                        <input id="password" type="password" name="password" autocomplete="off" required="" />
                        <label class="center-align" for="password">
                            Password
                        </label>
                    </div>
                    <div class="row margin checkbox">
                        <input id="rememberme" type="checkbox" name="rememberme" value="1" />
                        <label class="center-align" for="rememberme">
                            <span class="box"></span>
                            Remember Me
                        </label>
                    </div>
                    <div class="row btn-row">
                        <input type="submit" value="Login" />
                    </div>
                </form>
                <div class="row">
                    <div class="btn">
                        <a href="register.php">Register</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="mobile-register">
            <a href="register.php">Don't have an account? Register now</a>
        </div>
        <script>
            /* Causes labels to get smaller on focus */
            $("#username").keyup(function(){
                if( $(this).val() != ""){
                    $("label[for='username']").addClass("active");
                } else {
                    $("label[for='username']").removeClass("active");
                }
            });

            $("#password").keyup(function(){
                if( $(this).val() != ""){
                    $("label[for='password']").addClass("active");
                } else {
                    $("label[for='password']").removeClass("active");
                }
            });
        </script>
<?php
/* Destroys session if errors occur */
unset($_SESSION);
session_destroy();
}
?>
    </body>
</html>
