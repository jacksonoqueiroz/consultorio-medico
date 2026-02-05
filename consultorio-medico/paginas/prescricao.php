<?php
session_start();
include_once 'conexao/conexao.php';

$medico_id   = $_SESSION['id'] ?? null;
$consulta_id = filter_input(INPUT_GET, 'consulta', FILTER_VALIDATE_INT);

if (!$medico_id || !$consulta_id) {
    header("Location: home");
    exit;
}

// Buscar dados da consulta
$sql = "
SELECT 
    c.id,
    p.nome AS paciente,
    p.id AS paciente_id,
    m.nome AS medico
FROM consultas c
JOIN pacientes p ON p.id = c.paciente_id
JOIN medicos m ON m.id = c.medico_id
WHERE c.id = :id
";

$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $consulta_id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dados) {
    header("Location: home");
    exit;
}

// SALVAR PRESCRIÇÃO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sql = "
    INSERT INTO prescricoes
    (consulta_id, medico_id, paciente_id, prescricao, orientacoes, data_prescricao)
    VALUES
    (:consulta, :medico, :paciente, :prescricao, :orientacoes, CURDATE())
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':consulta'   => $consulta_id,
        ':medico'     => $medico_id,
        ':paciente'   => $dados['paciente_id'],
        ':prescricao' => $_POST['prescricao'],
        ':orientacoes'=> $_POST['orientacoes']
    ]);

    header("Location: prescricao_imprimir?id=" . $conn->lastInsertId());
    exit;
}

// ========================
// HEAD + MENU
// ========================
include_once 'include/head.php';
?>


<title>Prescrição Médica</title>

<div class="container mt-4">

<h4><i class="bi bi-file-earmark-text"></i> Prescrição Médica</h4>

<p>
<strong>Paciente:</strong> <?= $dados['paciente'] ?><br>
<strong>Médico:</strong> <?= $dados['medico'] ?><br>
<strong>Data:</strong> <?= date('d/m/Y') ?>
</p>

<form method="post">

<div class="mb-3">
    <label>Medicamentos / Posologia</label>
    <textarea name="prescricao" class="form-control" rows="6" required></textarea>
</div>

<div class="mb-3">
    <label>Orientações Gerais</label>
    <textarea name="orientacoes" class="form-control"></textarea>
</div>

<button class="btn btn-success">
    <i class="bi bi-printer"></i> Gerar Receita
</button>

<a href="prontuario_medico?id=<?= $consulta_id ?>" class="btn btn-secondary">
    Voltar
</a>

</form>
</div>

</body>
</html>
