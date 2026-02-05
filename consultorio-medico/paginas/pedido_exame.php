<?php
session_start();
include_once 'conexao/conexao.php';

$medico_id   = $_SESSION['id'] ?? null;
$consulta_id = filter_input(INPUT_GET, 'consulta', FILTER_VALIDATE_INT);
$paciente_id = filter_input(INPUT_GET, 'paciente', FILTER_VALIDATE_INT);

if (!$medico_id || !$consulta_id || !$paciente_id) {
    header("Location: home");
    exit;
}

$sucesso = false;
$pedido_id = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $observacoes = trim($_POST['observacoes'] ?? '');
    $exames      = $_POST['exames'] ?? [];

    if (!empty($exames)) {

        try {
            $conn->beginTransaction();

            // 1️⃣ Insere o pedido
            $sqlPedido = "
                INSERT INTO pedidos_exames
                (consulta_id, paciente_id, medico_id, observacoes)
                VALUES (:consulta, :paciente, :medico, :obs)
            ";
            $stmtPedido = $conn->prepare($sqlPedido);
            $stmtPedido->execute([
                ':consulta' => $consulta_id,
                ':paciente' => $paciente_id,
                ':medico'   => $medico_id,
                ':obs'      => $observacoes
            ]);

            $pedido_id = $conn->lastInsertId();

            // 2️⃣ Insere os exames
            $sqlExame = "
                INSERT INTO exames
                (pedido_exame_id, paciente_id, tipo_exame)
                VALUES (:pedido, :paciente, :tipo)
            ";
            $stmtExame = $conn->prepare($sqlExame);

            foreach ($exames as $tipo) {
                $stmtExame->execute([
                    ':pedido'   => $pedido_id,
                    ':paciente' => $paciente_id,
                    ':tipo'     => trim($tipo)
                ]);
            }

            $conn->commit();
            $sucesso = true;

        } catch (Exception $e) {
            $conn->rollBack();
            echo "<div class='alert alert-danger'>Erro ao salvar pedido</div>";
        }
    }
}
?>

<?php include 'include/head.php'; ?>
<?php include 'include/menu.php'; ?>

<title>Pedido de Exames</title>

<div class="container mt-4">

    <h4 class="mb-3">
        🧪 Pedido de Exames
    </h4>

    <?php if ($sucesso): ?>
    <div class="alert alert-success">
        Pedido de exame realizado com sucesso!
    </div>

    <div class="d-flex gap-2">
        <a href="pedido_exame_imprimir?id=<?= $pedido_id ?>"
           target="_blank"
           class="btn btn-primary">
            <i class="bi bi-file-earmark-text"></i>
            Visualizar / Imprimir Pedido
        </a>

        <a href="home" class="btn btn-secondary">
            Voltar para Home
        </a>
    </div>

<?php else: ?>


    <form method="post">

        <div class="mb-3">
            <label class="form-label">Exames solicitados</label>

            <div id="listaExames">
                <input type="text" name="exames[]" class="form-control mb-2" placeholder="Digite o exame">
            </div>

            <button type="button" class="btn btn-sm btn-outline-secondary"
                    onclick="addExame()">
                + Adicionar exame
            </button>
        </div>

        <div class="mb-3">
            <label class="form-label">Observações</label>
            <textarea name="observacoes" class="form-control" rows="3"></textarea>
        </div>

        <button class="btn btn-success">
            Solicitar Exames
        </button>

        <a href="home" class="btn btn-secondary">
            Cancelar
        </a>

    </form>

    <?php endif; ?>

</div>

<script>
function addExame() {
    const div = document.createElement('div');
    div.innerHTML = `
        <input type="text" name="exames[]" 
               class="form-control mb-2"
               placeholder="Digite o exame">
    `;
    document.getElementById('listaExames').appendChild(div);
}
</script>

<?php include 'include/footer.php'; ?>
