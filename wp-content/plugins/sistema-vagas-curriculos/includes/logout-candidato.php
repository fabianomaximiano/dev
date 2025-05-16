<?php
if (!defined('ABSPATH')) exit;

function svc_logout_candidato() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    unset($_SESSION['candidato_id']);
    session_destroy();

    wp_redirect(site_url('/login-candidato'));
    exit;
}
