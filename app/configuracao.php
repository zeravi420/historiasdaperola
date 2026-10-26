<?php
define('APP', dirname(__FILE__));
// Endereço do site descoberto automaticamente a partir da pasta onde o
// projeto está (funciona com "historiasdaperola", "historiasdaperola-main"
// ou qualquer outro nome de pasta dentro do htdocs).
$pastaDoSite = str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME'] ?? '/public/index.php')));
$pastaDoSite = rtrim($pastaDoSite, '/');
$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
define('URL', $protocolo . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $pastaDoSite);
define('APP_NAME', 'historias da perola');
const APP_VERSAO = '1.0.0';

// Fuso horário de Rondônia. O banco usa o mesmo fuso (ver Database.php),
// assim datas como "Há 5 minutos" ficam corretas.
date_default_timezone_set('America/Porto_Velho');
?>