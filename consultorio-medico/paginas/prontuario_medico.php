<?php
session_start();
ob_start();

include_once 'conexao/conexao.php';

// ========================
// VALIDAR LOGIN DO MÉDICO
// ========================
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$medico_id   = $_SESSION['id'];
$consulta_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$consulta_id) {
    header("Location: home.php");
    exit;
}

// ========================
// BUSCAR CONSULTA
// ========================
$sql = "
SELECT 
    c.id,
    c.data,
    c.horario,
    c.status,
    p.id   AS paciente_id,
    p.nome AS paciente
FROM consultas c
INNER JOIN pacientes p ON p.id = c.paciente_id
WHERE c.id = :id
AND c.medico_id = :medico
";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':id'     => $consulta_id,
    ':medico' => $medico_id
]);

$consulta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$consulta) {
    header("Location: home");
    exit;
}

// ========================
// MARCAR EM ATENDIMENTO
// ========================
if ($consulta['status'] !== 'Em Atendimento') {
    $conn->prepare("
        UPDATE consultas 
        SET status = 'Em Atendimento'
        WHERE id = :id
    ")->execute([':id' => $consulta_id]);
}

// ========================
// SALVAR PRONTUÁRIO
// ========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sql = "
    INSERT INTO prontuarios
    (consulta_id, medico_id, paciente_id, data_atendimento,
     queixa, anamnese, exame, diagnostico, conduta, observacoes)
    VALUES
    (:consulta, :medico, :paciente, CURDATE(),
     :queixa, :anamnese, :exame, :diagnostico, :conduta, :obs)
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':consulta'   => $consulta_id,
        ':medico'     => $medico_id,
        ':paciente'   => $consulta['paciente_id'],
        ':queixa'     => $_POST['queixa'],
        ':anamnese'   => $_POST['anamnese'],
        ':exame'      => $_POST['exame'],
        ':diagnostico'=> $_POST['diagnostico'],
        ':conduta'    => $_POST['conduta'],
        ':obs'        => $_POST['observacoes']
    ]);

    // FINALIZAR CONSULTA
    $conn->prepare("
        UPDATE consultas 
        SET status = 'Finalizada'
        WHERE id = :id
    ")->execute([':id' => $consulta_id]);

    $_SESSION['msg'] = 'Atendimento finalizado com sucesso!';
    header("Location: home");
    exit;
}

// ========================
// HEAD + MENU
// ========================
include_once 'include/head.php';
?>

<title>Prontuário Médico</title>
</head>

<?php include_once 'include/menu.php'; ?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">
        <i class="bi bi-person-badge"></i>
        <?= $consulta['paciente'] ?>
    </h5>

    <a href="prontuarios_paciente?paciente=<?= $consulta['paciente_id'] ?>"
       class="btn btn-outline-info btn-sm">
       <i class="bi bi-folder2-open"></i> Histórico
    </a>


</div>


    <h4>
        <i class="bi bi-clipboard2-pulse"></i>
        Prontuário Médico
    </h4>

    <div class="card shadow-sm mt-3">
        <div class="card-body">

            <p>
                <strong>Paciente:</strong> <?= $consulta['paciente'] ?><br>
                <strong>Data:</strong> <?= date('d/m/Y', strtotime($consulta['data'])) ?><br>
                <strong>Horário:</strong> <?= substr($consulta['horario'],0,5) ?>
            </p>

            <form method="post">

                <div class="mb-3">
                    <label class="form-label">Queixa Principal</label>
                    <textarea name="queixa" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Anamnese</label>
                    <textarea name="anamnese" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Exame Físico</label>
                    <textarea name="exame" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Diagnóstico</label>
                    <textarea name="diagnostico" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Conduta</label>
                    <textarea name="conduta" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Observações</label>
                    <textarea name="observacoes" class="form-control"></textarea>
                </div>

                <button class="btn btn-success">
                    <i class="bi bi-check-circle"></i>
                    Finalizar Atendimento
                </button>

                <a href="home" class="btn btn-secondary">
                    Voltar
                </a>

               <a href="pedido_exame?consulta=<?= $consulta['id'] ?>&paciente=<?= $consulta['paciente_id'] ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-clipboard2-pulse"></i> Solicitar Exames
                </a>

                <a href="prescricao?consulta=<?= $consulta['id'] ?>" class="btn btn-outline-success btn-sm">
                   <i class="bi bi-file-earmark-text"></i> Prescrição Médica
                </a>

                <a href="historico_exames?paciente=<?= $consulta['paciente_id'] ?>" class="btn btn-outline-primary btn-sm">
               <i class="bi bi-clipboard2-pulse"></i> Histórico de Exames
            </a>




            </form>

        </div>
    </div>

</div>

<?php include_once 'include/footer.php'; ?>
