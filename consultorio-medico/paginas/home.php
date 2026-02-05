<?php

session_start();

include_once ('conexao/conexao.php');
include_once ('config/seguranca.php');

//Inclui o head
include_once './include/head.php';
?>
<title>Home</title>
</head>
<?php



//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";

$medico_id = $_SESSION['id'] ?? null;

if (!$medico_id) {
    header("Location: login.php");
    exit;
}

// ========================
// BUSCAR CONSULTAS DO DIA
// ========================
$sql = "
SELECT 
    c.id,
    c.horario,
    c.status,
    p.nome AS paciente
FROM consultas c
JOIN pacientes p ON p.id = c.paciente_id
WHERE c.medico_id = :medico
AND c.data = CURDATE()
AND c.status IN ('Check-in', 'Chamado', 'Em Atendimento')
ORDER BY c.horario ASC
";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':medico' => $medico_id
]);

$consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container mt-4">

    <h4 class="mb-3">
        <i class="bi bi-clipboard-heart"></i>
        Consultas de Hoje
    </h4>

    <?php if ($consultas): ?>
        <div class="row">
            <?php foreach ($consultas as $c): ?>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm card-status">
                        <div class="card-body">

                            <h5 class="card-title">
                                <i class="bi bi-clock"></i>
                                <?= substr($c['horario'], 0, 5) ?>
                            </h5>

                            <p class="mb-1">
                                <strong>Paciente:</strong> <?= htmlspecialchars($c['paciente']) ?>
                            </p>

                            <?php
                                $badge = 'secondary';
                                if ($c['status'] === 'Check-in') $badge = 'info';
                                if ($c['status'] === 'Em Atendimento') $badge = 'warning';
                                if ($c['status'] === 'Finalizada') $badge = 'success';
                            ?>

                            <span class="badge bg-<?= $badge ?>">
                                <?= $c['status'] ?>
                            </span>

                            <?php if ($c['status'] === 'Check-in'): ?>
                                <a href="chamar_paciente?id=<?= $c['id'] ?>"
                                   class="btn btn-warning btn-sm mb-2"
                                   onclick="return confirm('Chamar este paciente?')">
                                    <i class="bi bi-megaphone"></i>
                                    Chamar Paciente
                                </a>
                            <?php endif; ?>


                            <div class="mt-3 d-grid">
                                <a href="prontuario_medico?id=<?= $c['id'] ?>" 
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-file-earmark-medical"></i>
                                    Abrir Prontuário
                                </a>
                            </div>

                            
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-secondary">
            Nenhuma consulta agendada para hoje.
        </div>
    <?php endif; ?>

</div>


<?php

//Inclui o footer
include_once './include/footer.php';
?>