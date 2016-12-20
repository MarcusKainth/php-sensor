<?php
function title() {
    /* Returns name of website, use function to change all pages */
    return "Arduino Temperature Sensor";
}

function findUser() {
    /* Finds user in database and echos Fullname */
    include('connect.php');
    $id = $_SESSION["login"];
    $stmt = $mysqli->prepare("SELECT FirstName, LastName FROM Users WHERE UserID = ?");
    $stmt->bind_param("i", $id);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($mysqli) {
        if ($result) {
            $num_rows = $result->num_rows;

            if ($num_rows > 0) {
                /* Found user in database */
                while($row = $result->fetch_assoc()) {
                    return $row["FirstName"] . " " . $row["LastName"];
                }
            }
        }
    }
    /* If we get here something went wrong,
     * maybe user account deleted? */
    return "undef";
}

function checkCookies() {
    include('connect.php');
    /* Checks if user is logged in using PHPSESSID */
    if (empty($_SESSION["login"]) && !empty($_COOKIE["_session"])) {
        list($selector, $authenticator) = explode(":", $_COOKIE["_session"]);
        /* Gets cookie session and looks for it in database */
        $stmt = $mysqli->prepare("SELECT * FROM AuthTokens WHERE Selector = ?");
        $stmt->bind_param("s", $selector);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $num_rows = $result->num_rows;

            if ($num_rows > 0) {
                /* Found selector in database */
                while($row = $result->fetch_assoc()) {
                    if (hash_equals($row["Token"], hash("sha256", base64_decode($authenticator)))) {
                        /* Logs user back in using cookies */
                        $_SESSION["login"] = $row["UserID"];

                        /* Refreshes authenticator */
                        $authenticator = openssl_random_pseudo_bytes(33);

                        unset($_COOKIE["_session"]);
                        /* Adds new authenticator to client cookie */
                        setcookie(
                                  "_session",
                                  $selector.':'.base64_encode($authenticator),
                                  time() + 86400 * 10,
                                  "/",
                                  "marcuskainth.co.uk",
                                  true,
                                  true
                        );

                        /* Stores new value in database */
                        $stmt = $mysqli->prepare("UPDATE AuthTokens SET Token=?, Expires=? WHERE AuthID=?");
                        $stmt->bind_param("ssi", hash('sha256', $authenticator), date('Y-m-d H:i:s', time() + 86400 * 10), $row["AuthID"]);

                        $stmt->execute();
                    }
                }
            }
        }
    }
}

function removeCookies() {
    include('connect.php');
    /* If cookie is not empty */
    if (!empty($_COOKIE["_session"])) {
        list($selector, $authenticator) = explode(":", $_COOKIE["_session"]);
        /* Finds selector from client cookies and deletes from database */
        $stmt = $mysqli->prepare("DELETE FROM AuthTokens WHERE Selector = ?");
        $stmt->bind_param("s", $selector);

        $stmt->execute();
        /* Deletes cookie from client */
        unset($_COOKIE["_session"]);
        setcookie(
                  "_session",
                  null,
                  -1,
                  "/",
                  "marcuskainth.co.uk",
                  true,
                  true
        );

    }
}
?>
