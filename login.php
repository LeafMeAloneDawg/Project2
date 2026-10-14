<?php
/*
filename: login.php
author: Mohammad Aquib
created: 13/10/2026
last modified: 13/10/2026
description: Login page for HR managers. A successful login starts a session and sends the
             user to manage.php.

GenAI acknowledgement: the login logic in this file (password_verify, session handling,
CSRF token) was drafted with the assistance of GenAI, then reviewed, tested and edited
by Mohammad Aquib.
*/

require_once("session_init.php");
require_once("db_setup.php");     # also gives us $dbconn and makes sure the users table exists #

# Already logged in: no need to see the form again #
if (isset($_SESSION['user_id'])) {
    header("Location: manage.php");
    exit();
}

$error = "";
$notice = "";
$username = "";

if (isset($_GET['timeout'])) {
    $notice = "You were logged out after a period of inactivity. Please log in again.";
}
if (isset($_GET['loggedout'])) {
    $notice = "You have been logged out.";
}

# CSRF token: a random value stored in the session and sent back as a hidden field, so a form #
# on another website cannot submit a login on the user's behalf #
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$max_attempts = 5;      # failed logins allowed ... #
$lock_seconds = 60;     # ... before a short lockout #

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";          # passwords are never trimmed or altered #
    $token = $_POST["csrf_token"] ?? "";

    if (($_SESSION['lock_until'] ?? 0) > time()) {
        $wait = $_SESSION['lock_until'] - time();
        $error = "Too many failed attempts. Please try again in $wait seconds.";

    } elseif (!hash_equals($_SESSION['csrf_token'], $token)) {
        $error = "Your session expired. Please try again.";

    } elseif ($username === "" || $password === "") {
        $error = "Please enter both a username and a password.";

    } elseif (strlen($username) > 50 || strlen($password) > 255) {
        $error = "Invalid username or password.";

    } else {
        # Prepared statement: the username is never pasted into the SQL (stops SQL injection) #
        $stmt = mysqli_prepare($dbconn, "SELECT user_id, username, password_hash FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($user && password_verify($password, $user['password_hash'])) {
            # New session ID after login, so an ID known before login is useless (session fixation) #
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['last_activity'] = time();
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            unset($_SESSION['attempts'], $_SESSION['lock_until']);

            header("Location: manage.php");
            exit();
        }

        # Same message whether the username or the password was wrong, so attackers learn nothing #
        $error = "Invalid username or password.";

        $_SESSION['attempts'] = ($_SESSION['attempts'] ?? 0) + 1;
        if ($_SESSION['attempts'] >= $max_attempts) {
            $_SESSION['lock_until'] = time() + $lock_seconds;
            $_SESSION['attempts'] = 0;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Mohammad Aquib">
    <meta name="keywords" content="HR login, management, Melbourne Youth Support Network">
    <meta name="description" content="Login page for Melbourne Youth Support Network HR managers.">
    <title>HR Login | Melbourne Youth Support Network</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body class="apply-page">

    <a href="#main-content" class="skip-link">Skip to main content</a>

    <nav>
        <?php include ("nav.inc"); ?>
    </nav>

    <main id="main-content" class="apply-main">

        <div class="apply_intro_box">
            <h1>HR Manager Login</h1>
            <p>Authorised staff only. Log in to view and manage expressions of interest.</p>
        </div>

        <?php if ($notice !== ""): ?>
            <p class="form-message notice" role="status"><?php echo htmlspecialchars($notice); ?></p>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <p class="form-message error" role="alert"><strong>Error:</strong> <?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="post" action="login.php">
            <fieldset>
                <legend>Login details</legend>

                <div class="login_form">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" autocomplete="username"
                           maxlength="50" required value="<?php echo htmlspecialchars($username); ?>">

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" autocomplete="current-password" required>

                    <!-- Hidden field holding the CSRF token -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                </div>
            </fieldset>

            <div class="form_buttons">
                <input type="submit" value="Log in">
            </div>
        </form>

    </main>

    <footer>
        <?php include ("footer.inc"); ?>
    </footer>

</body>
</html>