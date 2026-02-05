<?php
session_start();
ob_start();
include_once 'conexao/conexao.php';

//$medico_id   = $_SESSION['medico_id'] ?? null;
$consulta_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($consulta_id) {
    $sql = "UPDATE consultas SET status = 'Em atendimento' WHERE id = :id ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $consulta_id);
    $stmt->execute();
}


header("Location: home");
exit;
