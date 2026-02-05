<?php
session_start();
include_once 'conexao/conexao.php';

$exame_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$exame_id) {
    header("Location: home");
    exit;
}

$sql = "
SELECT 
    e.*,
    p.nome AS paciente_nome,
    p.data_nascimento
FROM exames e
INNER JOIN pacientes p ON p.id = e.paciente_id
WHERE e.id = :id
";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':id', $exame_id);
$stmt->execute();

$exame = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exame) {
    echo '<div class="alert alert-danger">Exame não encontrado.</div>';
    exit;
}
?>

<?php include 'include/head.php'; ?>
<?php include 'include/menu.php'; ?>


<title>Resultado Exame: <?= htmlspecialchars($exame['paciente_nome']) ?></title>

<style>
@media print {
    .no-print {
        display: none !important;
    }

    body {
        background: #fff !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }
}

.logo-clinica {
    height: 60px;
    width: 60px;    
}


    .logo-clinica {
        height: 80px;
        width: 80px;
    }
}
</style>



<div class="container mt-4">

    <!-- CABEÇALHO DO LAUDO -->
<div class="laudo-header mb-4">
    <div class="d-flex align-items-center">
        <div class="me-3">
            <img src="assets/images/logo2.png" alt="Clínica Médica"
                 class="logo-clinica">
        </div>

        <div>
            <h5 class="mb-0 fw-bold">Clínica Médica Saúde Total</h5>
            <small>
                CNPJ: 00.000.000/0001-00 <br>
                Tel: (00) 0000-0000
            </small>
        </div>

        <div class="ms-auto text-end">
            <small>
                Data de emissão:<br>
                <?= date('d/m/Y') ?>
            </small>
        </div>
    </div>

    <hr>
</div>



    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <h4>
        <i class="bi bi-file-earmark-medical"></i>
        Resultado do Exame
    </h4>

    <div>
        <button onclick="window.print()" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-printer"></i> Imprimir
        </button>

        <a href="historico_exames?paciente=<?= $exame['paciente_id'] ?>"
           class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>


    <div class="card shadow-sm mb-3">
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Paciente:</strong><br>
                    <?= htmlspecialchars($exame['paciente_nome']) ?>
                </div>

                <div class="col-md-3">
                    <strong>Data do Exame:</strong><br>
                    <?= date('d/m/Y', strtotime($exame['data_solicitacao'])) ?>
                </div>

                <div class="col-md-3">
                    <strong>Status:</strong><br>
                    <?php if ($exame['status'] === 'Realizado'): ?>
                        <span class="badge bg-success">Realizado</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">
                            <?= $exame['status'] ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <hr>

            <div class="mb-3">
                <strong>Tipo de Exame:</strong>
                <div class="border rounded p-2 bg-light">
                    <?= htmlspecialchars($exame['tipo_exame']) ?>
                </div>
            </div>

            <div class="mb-3">
                <strong>Resultado / Laudo:</strong>
                <div class="border rounded p-3" style="min-height: 150px;">
                    <?= nl2br(htmlspecialchars($exame['resultado'] ?? 'Resultado não informado.')) ?>
                </div>
            </div>

            <?php if (!empty($exame['arquivo'])): ?>
                <div class="mt-3">
                    <strong>Arquivo do Exame:</strong><br>
                    <a href="../uploads/exames/<?= $exame['arquivo'] ?>"
                       target="_blank"
                       class="btn btn-outline-success btn-sm mt-1">
                        <i class="bi bi-file-earmark-pdf"></i>
                        Abrir Arquivo
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>

<?php include 'include/footer.php'; ?>
