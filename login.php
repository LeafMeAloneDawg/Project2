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

        <form method="post" action="login.php">
            <fieldset>
                <legend>Login details</legend>

                <div class="login_form">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" autocomplete="username" maxlength="50" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" autocomplete="current-password" required>
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