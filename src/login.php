<?php
include ('includes/connect.php');

/* Checks that the database is accessible */
if ($mysqli) {
    if ($_POST["username"] && $_POST["password"]) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        /* Prepares query to find if user exists in database */
        $stmt = $mysqli->prepare("SELECT UserID, Password FROM Users WHERE Username = ?");
        $stmt->bind_param("s", $username);
        /* Executes query */
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            /* If query is successful find number of rows user exists in database */
            $num_rows = $result->num_rows;

            if ($num_rows == 1) {
                /* User should only exist once for successful login */
                while ($row = $result->fetch_assoc()) {
                    /* Finds password in row and validates */
                    if (password_verify(base64_encode(hash('sha256', $password, true)), $row["Password"])) {
                        unset($_SESSION);
                        session_destroy();
                        session_start();
                        /* Sets up session with UserID */
                        $_SESSION["login"] = $row["UserID"];
                        if ($_POST["rememberme"]) {
                            /* If remember me is ticked */
                            $selector = base64_encode(openssl_random_pseudo_bytes(9));
                            $authenticator = openssl_random_pseudo_bytes(33);
                            /* Creates cookies to store session */
                            setcookie(
                               "_session",
                               $selector.':'.base64_encode($authenticator),
                               time() + 86400 * 10,
                               "/",
                               "marcuskainth.co.uk",
                                true,
                                true
                            );

                            $stmt = $mysqli->prepare("INSERT INTO AuthTokens (Selector, Token, UserID, Expires) VALUES (?, ?, ?, ?)");
                            $stmt->bind_param("ssis", $selector, hash('sha256', $authenticator), $_SESSION["login"], date('Y-m-d H:i:s', time() + 86400 * 10));

                            $stmt->execute();
                            /* Cookie stored in database */
                        }
                        header("Location: index.php");
                    } else {
                        /* Else password is entered incorrectly */
                        session_start();
                        $_SESSION["error"] = "Password is incorrect";
                        header("Location: index.php");
                    }
                }
            } else {
                /* Else user was not found in database */
                session_start();
                $_SESSION["error"] = "User does not exist";
                header("Location: index.php");
            }
        }
    } else {
        /* Nothing posted */
        header("Location: index.php");
    }
}
?>
