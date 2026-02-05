<?php
include_once 'conexao/conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) exit;

// ========================
// BUSCA PEDIDO (CABEÇALHO)
// ========================
$stmt = $conn->prepare("
SELECT 
    pe.id,
    pe.observacoes,
    pe.data_pedido,
    p.nome AS paciente,
    m.nome AS medico
FROM pedidos_exames pe
JOIN pacientes p ON p.id = pe.paciente_id
JOIN medicos m ON m.id = pe.medico_id
WHERE pe.id = :id
");
$stmt->execute([':id' => $id]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) exit;

// ========================
// BUSCA EXAMES (LISTA)
// ========================
$stmtExames = $conn->prepare("
SELECT tipo_exame
FROM exames
WHERE pedido_exame_id = :pedido
");
$stmtExames->execute([':pedido' => $id]);
$exames = $stmtExames->fetchAll(PDO::FETCH_ASSOC);

// ========================
// HEAD (SEM MENU)
// ========================
include_once 'include/head.php';
?>

<title>Imprimir Pedido de Exames</title>

<style>
@media print {
    button, .no-print {
        display: none !important;
    }
    body {
        margin: 0;
    }
}
</style>
</head>
<body>

<div class="container mt-4">

    <!-- CABEÇALHO -->
    <div class="d-flex align-items-center mb-3">
        <div class="me-3">
            <img src="assets/images/logo2.png" alt="Clínica Médica" style="width: 120px;">
        </div>

        <div>
            <h5 class="mb-0 fw-bold">Clínica Médica Saúde Total</h5>
            <small>
                CNPJ: 00.000.000/0001-00<br>
                Tel: (00) 0000-0000
            </small>
        </div>
    </div>

    <hr>

    <h4 class="text-center mb-4">PEDIDO DE EXAMES</h4>

    <p>
        <strong>Paciente:</strong> <?= htmlspecialchars($pedido['paciente']) ?><br>
        <strong>Médico:</strong> <?= htmlspecialchars($pedido['medico']) ?><br>
        <strong>Data:</strong> <?= date('d/m/Y', strtotime($pedido['data_pedido'])) ?>
    </p>

    <h5 class="mt-4">Exames Solicitados</h5>

    <ul>
        <?php foreach ($exames as $e): ?>
            <li><?= htmlspecialchars($e['tipo_exame']) ?></li>
        <?php endforeach; ?>
    </ul>

    <?php if (!empty($pedido['observacoes'])): ?>
        <h5 class="mt-4">Observações</h5>
        <p><?= nl2br(htmlspecialchars($pedido['observacoes'])) ?></p>
    <?php endif; ?>

    <hr class="mt-5">

    <p class="text-center mt-5">
        _________________________________________<br>
        Assinatura do Médico
    </p>

    <div class="text-center no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer"></i> Imprimir
        </button>

        <button type="button" class="btn btn-secondary" onclick="voltarPagina()">
    <i class="bi bi-arrow-left"></i> Voltar
</button>
    </div>

</div>

<script>
function voltarPagina() {
    if (document.referrer !== "") {
        history.back();
    } else {
        window.location.href = "home";
    }
}

</body>
</html>
