<?php
/*
filename: session_init.php
author: Mohammad Aquib
created: 13/10/2026
last modified: 13/10/2026
description: Starts the session with safer cookie settings. Include it at the very top of a
             page, BEFORE any HTML is sent, because starting a session sends a cookie header.
*/

if (session_status() === PHP_SESSION_NONE) {
    # Own session name so this site does not share a session with other projects on localhost #
    session_name("mysn_session");

    session_set_cookie_params([
        'lifetime' => 0,                        # cookie is deleted when the browser closes #
        'path' => '/',
        'httponly' => true,                     # JavaScript cannot read the session cookie #
        'samesite' => 'Lax',                    # cookie is not sent with cross-site form posts #
        'secure' => !empty($_SERVER['HTTPS'])   # HTTPS-only cookie whenever the site runs on HTTPS #
    ]);

    session_start();
}