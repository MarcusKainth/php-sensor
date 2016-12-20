<?php
include ('includes/functions.php');
/* Checks if session exists and if you're logged in */
session_start();
checkCookies();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="description" content="Registration page for arduino sensor">
        <meta name="keywords" content="arduino, register, material">
        <meta name="theme-color" content="#3f51b5">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="manifest" href="includes/json/manifest.json">
        <meta name="apple-mobile-web-app-title" content="Arduino Sensor">
        <link rel="icon" sizes="192x192" href="includes/images/icons/launcher-icon-4x.png">
        <link rel="apple-touch-icon" href="includes/images/icons/launcher-icon-ios.png">
        <title>Register | <?php echo title(); ?></title>
        <link id="stylesheet" href="includes/css/login.css" type="text/css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js" type="text/javascript"></script>
        <script>
            /* To see if the webapp is running */
            function isRunningStandalone() {
                return ((window.matchMedia('(display-mode: standalone)').matches) || (navigator.standalone = navigator.standalone || (screen.height-document.documentElement.clientHeight<40)));
            }

            if (isRunningStandalone()) {
                /* Switches to webapp style if true */
                document.getElementById("stylesheet").setAttribute("href", "includes/css/loginMobile.css");
            }
        </script>
        <!-- To stop new tabs opening on webapp -->
        <script>(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone")</script>
    </head>
    <body>
<?php
if(isset($_SESSION["login"]) && $_SESSION["login"]) {
    /* If logged in then redirect */
    header("Location: index.php");
} else if($_POST["username"] && $_POST["password"] && $_POST["firstname"] && $_POST["lastname"] && $_POST["email"] && false) {
    include ('includes/connect.php');
    /* Stops SQL injection attacks using prepared queries */
    $username = $_POST["username"];
    $password = $_POST["password"];
    /* Capitalises first letter in both first and last name */
    $firstname = ucwords($_POST["firstname"]);
    $lastname = ucwords($_POST["lastname"]);
    $email = $_POST["email"];
    /* Hashes password with salt to be stored in the database */
    $hashedPassword = password_hash(base64_encode(hash('sha256', $password, true)), PASSWORD_BCRYPT);

    /* Check if user already exists */
    $stmt = $mysqli->prepare("SELECT Username FROM Users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result) {
        $num_rows = $result->num_rows;

        if($num_rows > 0) {
            /* User already exists */
            $_SESSION["error"] = "User already exists";
            header("Location: index.php");
        } else {
            $stmt = $mysqli->prepare("SELECT Email FROM Users WHERE Email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $result = $stmt->get_result();

            if($result) {
                $num_rows = $result->num_rows;
                if($num_rows > 0) {
                    /* Email already in database */
                    $_SESSION["error"] = "Email exists in database";
                    header("Location: index.php");
                } else {
                    /* User does not exists */
                    $stmt = $mysqli->prepare("INSERT INTO Users (Username, Password, FirstName, LastName, Email) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $username, $hashedPassword, $firstname, $lastname, $email);
                    $stmt->execute();

                    /* Gets UserID to store in session */
                    $stmt = $mysqli->prepare("SELECT UserID FROM Users WHERE Username = ?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute();

                    $result = $stmt->get_result();
                    while($row = $result->fetch_assoc()) {
                        /* UserID stored in session */
                        $_SESSION["login"] = $row["UserID"];
                    }
                }
            }
            header("Location: index.php");
        }
    }
} else {
?>
        <div class="login-wrapper longer">
            <div class="reg-mobile img">
                <h1>Register</h1>
            </div>
            <div class="reg-mobile-inner inner-wrapper">
                <div class="error">
                    <p>Registration disabled!</p>
                </div>
                <form action="register.php" method="POST">
                    <div class="row margin">
                        <input id="username" type="text" name="username" autocomplete="off" required="" />
                        <label class="center-align" for="username">
                            Username
                        </label>
                    </div>
                    <div class="row margin">
                        <input id="firstname" type="text" name="firstname" autocomplete="off" required="" />
                        <label class="center-align" for="firstname">
                            Forename
                        </label>
                    </div>
                    <div class="row margin">
                        <input id="lastname" type="text" name="lastname" autocomplete="off" required="" />
                        <label class="center-align" for="lastname">
                            Surname
                        </label>
                    </div>
                    <div class="row margin">
                        <input id="email" type="email" name="email" autocomplete="off" required="" />
                        <label class="center-align" for="email">
                            Email
                        </label>
                    </div>
                    <div class="row margin">
                        <input id="password" type="password" name="password" autocomplete="off" required="" />
                        <label class="center-align" for="password">
                            Password
                        </label>
                    </div>
                    <div class="row">
                        <input type="submit" value="Register" disabled />
                    </div>
                </form>
                <div class="row">
                    <div class="btn">
                        <a href="index.php">Go Back</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- JQuery for label animations -->
        <script>
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

            $("#firstname").keyup(function(){
                if( $(this).val() != ""){
                    $("label[for='firstname']").addClass("active");
                } else {
                    $("label[for='firstname']").removeClass("active");
                }
            });

            $("#lastname").keyup(function(){
                if( $(this).val() != ""){
                    $("label[for='lastname']").addClass("active");
                } else {
                    $("label[for='lastname']").removeClass("active");
                }
            });

            $("#email").keyup(function(){
                if( $(this).val() != ""){
                    $("label[for='email']").addClass("active");
                } else {
                    $("label[for='email']").removeClass("active");
                }
            });
        </script>
<?php
}
?>
    </body>
</html>
