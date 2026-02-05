<?php
session_start();
ob_start();
unset($_SESSION['id'], $_SESSION['nome'], $_SESSION['imagem']);
$_SESSION['msg'] = '<div class="alert alert-success" role="alert">Deslogado do sistema!</div>';
header("Location: " . URL . "login");