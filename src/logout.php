<?php
include('includes/functions.php');
session_start();
/* Checks if user has cookies stored in database */
checkCookies();

if (isset($_SESSION["login"]) && $_SESSION["login"]) {
    if (ini_get("session.use_cookies")) {
        /* Removes PHPSESSID cookie from client */
        $params = session_get_cookie_params();
        setcookie(session_name(), "", -1,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    unset($_SESSION);
    session_destroy();
    /* Removes remember me cookies and redirects */
    removeCookies();
    header("Location: index.php");
} else {
    header("Location: index.php");
}
?>