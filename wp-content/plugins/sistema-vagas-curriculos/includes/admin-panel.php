<?php
add_action('admin_menu', 'svc_adicionar_menu_admin');

function svc_adicionar_menu_admin() {
    add_menu_page('Gerenciar Vagas', 'Vagas', 'manage_options', 'svc_vagas', 'svc_pagina_vagas', 'dashicons-businessman', 6);
    add_submenu_page('svc_vagas', 'Currículos', 'Currículos', 'manage_options', 'svc_curriculos', 'svc_pagina_curriculos');
}

function svc_pagina_vagas() {
    echo '<div class="wrap"><h1>Vagas de Emprego</h1><p>Esta é a página de administração das vagas.</p></div>';
}

function svc_pagina_curriculos() {
    echo '<div class="wrap"><h1>Currículos de Candidatos</h1><p>Esta é a página de administração dos currículos.</p></div>';
}
