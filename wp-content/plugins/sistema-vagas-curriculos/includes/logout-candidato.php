<?php
// add_shortcode('logout_candidato', 'svc_logout_candidato');

// function svc_logout_candidato() {
//     if (session_status() === PHP_SESSION_NONE) session_start();

//     if (!empty($_SESSION['candidato_id'])) {
//         unset($_SESSION['candidato_id']);
//         session_destroy();
//     }

//     wp_redirect(site_url('/login-candidato'));
//     exit;
// }


if (!defined('ABSPATH')) exit;

add_shortcode('logout_candidato', function () {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Destroi apenas a sessão do candidato
    if (isset($_SESSION['candidato_id'])) {
        unset($_SESSION['candidato_id']);
    }

    // Opcional: Destroi toda a sessão
    // session_destroy();

    wp_redirect(home_url());
    exit;
});
