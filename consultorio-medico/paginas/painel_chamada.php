<?php
include_once 'conexao/conexao.php';

include_once 'include/head.php';    

// Busca o último paciente chamado
$chamado = $conn->query("
    SELECT 
        p.nome AS paciente,
        m.nome AS medico,
        c.horario
    FROM consultas c
    JOIN pacientes p ON p.id = c.paciente_id
    JOIN medicos m ON m.id = c.medico_id
    WHERE c.status = 'Chamado'
    ORDER BY c.id DESC
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC);

// Próximos pacientes
$proximos = $conn->query("
    SELECT 
        p.nome AS paciente,
        m.nome AS medico,
        c.horario
    FROM consultas c
    JOIN pacientes p ON p.id = c.paciente_id
    JOIN medicos m ON m.id = c.medico_id
    WHERE c.status = 'Check-in'
    ORDER BY c.horario ASC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>
<title>Paine de Chamada</title>

    <style>
       body {
            background: linear-gradient(135deg, #0d6efd, #084298);
            color: #fff;
        }

        .painel {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-painel {
            background: #fff;
            color: #333;
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            width: 80%;
            max-width: 900px;
            box-shadow: 0 20px 40px rgba(0,0,0,.3);
            animation: fadeIn 0.6s ease-in-out;
        }

        .paciente {
            font-size: 3.2rem;
            font-weight: bold;
            color: #0d6efd;
        }

        .medico {
            font-size: 1.6rem;
            margin-top: 10px;
        }

        .horario {
            font-size: 1.3rem;
            margin-top: 5px;
            color: #555;
        }

        .rodape {
            margin-top: 30px;
            font-size: 1rem;
            color: #777;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>

<div class="container text-center mt-5">

    <h1 class="mb-4">🔊 CHAMADA</h1>

    <?php if ($chamado): ?>
        <div class="chamado">
            <?= $chamado['paciente'] ?>
        </div>
        <div class="medico">
            <?= $chamado['medico'] ?> • <?= substr($chamado['horario'],0,5) ?>
        </div>
    <?php else: ?>
        <h3>Nenhum paciente sendo chamado</h3>
    <?php endif; ?>

    <hr class="my-5">

    <h4>Próximos</h4>

    <ul class="list-group list-group-flush text-dark mt-3">
        <?php foreach ($proximos as $p): ?>
            <li class="list-group-item">
                <?= $p['horario'] ?> — <?= $p['paciente'] ?> (<?= $p['medico'] ?>)
            </li>
        <?php endforeach; ?>
    </ul>

</div>

</body>
</html>
