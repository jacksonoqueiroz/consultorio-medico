<?php
include_once 'conexao/conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$stmt = $conn->prepare("
SELECT 
    pr.*,
    p.nome AS paciente,
    m.nome AS medico
FROM prescricoes pr
JOIN pacientes p ON p.id = pr.paciente_id
JOIN medicos m ON m.id = pr.medico_id
WHERE pr.id = :id
");
$stmt->execute([':id' => $id]);
$receita = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$receita) exit;

// ========================
// HEAD + MENU
// ========================
include_once 'include/head.php';
?>


<title>Receita Médica</title>

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

    .logo-clinica {
    height: 60px !important;
    width: 60px !important;    
}

    .logo-clinica {
        height: 70px !important;
    }
}
</style>

<div class="container mt-4">

<div class="d-flex align-items-center">
        <div class="me-3">
            <img src="assets/images/logo2.png" alt="Clínica Médica" style="width: 15%;">
        </div>

        <div style="margin-left: -390px; margin-top: 30px;">
            <h5 class="mb-0 fw-bold">Clínica Médica Saúde Total</h5>
            <small>
                CNPJ: 00.000.000/0001-00 <br>
                Tel: (00) 0000-0000
            </small>
        </div>

     </div>
    <hr>

<h3 class="text-center">RECEITA MÉDICA</h3>
<hr>

<p>
<strong>Paciente:</strong> <?= $receita['paciente'] ?><br>
<strong>Médico:</strong> <?= $receita['medico'] ?><br>
<strong>Data:</strong> <?= date('d/m/Y', strtotime($receita['data_prescricao'])) ?>
</p>

<h5>Prescrição</h5>
<p><?= nl2br($receita['prescricao']) ?></p>

<?php if ($receita['orientacoes']): ?>
<h5>Orientações</h5>
<p><?= nl2br($receita['orientacoes']) ?></p>
<?php endif; ?>

<hr>

<p class="text-center mt-5">
_________________________________________<br>
Assinatura do Médico
</p>

<div>
<button onclick="window.print()" class="no-print btn btn-primary">
    <i class="bi bi-printer"></i> Imprimir
</button>
<button class="btn btn-secondary no-print" onclick="history.back()">
    <i class="bi bi-arrow-left"></i> Voltar
</button>
</div>
</div>
</body>
</html>
