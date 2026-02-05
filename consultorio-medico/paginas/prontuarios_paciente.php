<?php
session_start();
include_once 'conexao/conexao.php';

$paciente_id = filter_input(INPUT_GET, 'paciente', FILTER_VALIDATE_INT);

if (!$paciente_id) {
    header("Location: home");
    exit;
}

// Paciente
$stmt = $conn->prepare("SELECT nome FROM pacientes WHERE id = :id");
$stmt->execute([':id' => $paciente_id]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paciente) {
    header("Location: home");
    exit;
}

// Prontuários
$sql = "
SELECT 
    pr.id,
    pr.data_atendimento,
    pr.queixa,
    m.nome AS medico,
    c.horario
FROM prontuarios pr
JOIN medicos m ON m.id = pr.medico_id
JOIN consultas c ON c.id = pr.consulta_id
WHERE pr.paciente_id = :paciente
ORDER BY pr.data_atendimento DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute([':paciente' => $paciente_id]);
$prontuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'include/head.php'; ?>
<?php include 'include/menu.php'; ?>

<title>Prontuario: <?= $paciente['nome'] ?></title>

<div class="container mt-4">

<h4>
    <i class="bi bi-folder2-open"></i>
    Histórico de Prontuários
</h4>

<p><strong>Paciente:</strong> <?= $paciente['nome'] ?></p>

<div class="card shadow-sm mt-3">
<div class="card-body">

<?php if (!$prontuarios): ?>
    <div class="alert alert-warning">
        Nenhum prontuário registrado.
    </div>
<?php else: ?>

<table class="table table-hover align-middle">
<thead>
<tr>
    <th>Data</th>
    <th>Horário</th>
    <th>Médico</th>
    <th>Queixa</th>
    <th>Ações</th>
</tr>
</thead>
<tbody>

<?php foreach ($prontuarios as $p): ?>
<tr>
    <td><?= date('d/m/Y', strtotime($p['data_atendimento'])) ?></td>
    <td><?= substr($p['horario'],0,5) ?></td>
    <td><?= $p['medico'] ?></td>
    <td><?= mb_strimwidth($p['queixa'], 0, 40, '...') ?></td>
    <td>
        <a href="prontuario_visualizar?id=<?= $p['id'] ?>" 
           class="btn btn-sm btn-outline-primary">
           <i class="bi bi-eye"></i> Ver
        </a>
    </td>
</tr>
<?php endforeach; ?>

</tbody>
</table>

<?php endif; ?>

<a href="home" class="btn btn-secondary mt-3">
    <i class="bi bi-arrow-left"></i> Voltar
</a>

</div>
</div>
</div>

<?php include 'include/footer.php'; ?>
