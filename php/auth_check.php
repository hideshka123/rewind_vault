<?php
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    jsonResponse(['success' => false, 'message' => 'Требуется авторизация'], 401);
}

$user = getCurrentUser();
jsonResponse(['success' => true, 'user' => $user]);
?>