<?php
session_start();
include_once 'conexao/conexao.php';

$paciente_id = filter_input(INPUT_GET, 'paciente', FILTER_VALIDATE_INT);

if (!$paciente_id) {
    header("Location: home");
    exit;
}

$sql = "
SELECT 
    e.id,
    e.tipo_exame,
    e.status,
    e.data_solicitacao,
    p.nome AS paciente_nome
FROM exames e
INNER JOIN pacientes p ON p.id = e.paciente_id
WHERE e.paciente_id = :paciente
ORDER BY e.data_solicitacao DESC
";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':paciente', $paciente_id);
$stmt->execute();

$exames = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'include/head.php'; ?>
<?php include 'include/menu.php'; ?>

<title>Histórico de Exames</title>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>
            <i class="bi bi-clipboard2-pulse"></i>
            Histórico de Exames
        </h4>
        <a href="home" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <?php if (empty($exames)): ?>
        <div class="alert alert-info">
            Nenhum exame encontrado para este paciente.
        </div>
    <?php else: ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Exame</th>
                        <th>Data do Pedido</th>
                        <th>Status</th>
                        <th class="text-center">Ação</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach ($exames as $exame): ?>
                    <tr>
                        <td><?= htmlspecialchars($exame['tipo_exame']) ?></td>

                        <td>
                            <?= date('d/m/Y H:i', strtotime($exame['data_solicitacao'])) ?>
                        </td>

                        <td>
                            <?php if ($exame['status'] === 'Solicitado'): ?>
                                <span class="badge bg-warning text-dark">
                                    Solicitado
                                </span>
                            <?php elseif ($exame['status'] === 'Realizado'): ?>
                                <span class="badge bg-success">
                                    Realizado
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    <?= $exame['status'] ?>
                                </span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center">
                            <?php if ($exame['status'] === 'Realizado'): ?>
                                <a href="ver_exame?id=<?= $exame['id'] ?>"
                                   class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-file-earmark-medical"></i>
                                    Ver Resultado
                                </a>
                            <?php else: ?>
                                <span class="text-muted">
                                    Aguardando laboratório
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>

        </div>
    </div>

    <?php endif; ?>

</div>

<?php include 'include/footer.php'; ?>
