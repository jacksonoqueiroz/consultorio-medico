<?php
session_start();
include_once 'conexao/conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: home");
    exit;
}

$sql = "
SELECT 
    pr.*,
    p.nome AS paciente,
    m.nome AS medico,
    c.data,
    c.horario
FROM prontuarios pr
JOIN pacientes p ON p.id = pr.paciente_id
JOIN medicos m ON m.id = pr.medico_id
JOIN consultas c ON c.id = pr.consulta_id
WHERE pr.id = :id
";

$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);
$prontuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$prontuario) {
    header("Location: home");
    exit;
}
?>

<?php include 'include/head.php'; ?>
<?php include 'include/menu.php'; ?>

<div class="container mt-4">

<h4>
    <i class="bi bi-clipboard2-pulse"></i> Prontuário Médico
</h4>

<div class="card shadow-sm mt-3">
<div class="card-body">

<p><strong>Paciente:</strong> <?= $prontuario['paciente'] ?></p>
<p><strong>Médico:</strong> <?= $prontuario['medico'] ?></p>
<p><strong>Data:</strong> <?= date('d/m/Y', strtotime($prontuario['data'])) ?></p>
<p><strong>Horário:</strong> <?= substr($prontuario['horario'],0,5) ?></p>

<hr>

<p><strong>Queixa Principal</strong><br><?= nl2br($prontuario['queixa']) ?></p>
<p><strong>Anamnese</strong><br><?= nl2br($prontuario['anamnese']) ?></p>
<p><strong>Exame Físico</strong><br><?= nl2br($prontuario['exame']) ?></p>
<p><strong>Diagnóstico</strong><br><?= nl2br($prontuario['diagnostico']) ?></p>
<p><strong>Conduta</strong><br><?= nl2br($prontuario['conduta']) ?></p>
<p><strong>Observações</strong><br><?= nl2br($prontuario['observacoes']) ?></p>

<a href="javascript:history.back()" class="btn btn-secondary mt-3">
    <i class="bi bi-arrow-left"></i> Voltar
</a>

</div>
</div>

</div>

<?php include 'include/footer.php'; ?>
