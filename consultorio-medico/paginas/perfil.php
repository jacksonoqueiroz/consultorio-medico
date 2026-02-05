<?php

//ABRIR ESSA PÁGINA QUANDO LOGADO
if ((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))) {
	$_SESSION['msg'] = '<div class="alert alert-danger" role="alert">É necessário realizar o Login, para acessar o sistema!</div>';	
	header("Location: login.php");
}


//Inclui o head
include_once './include/head.php';
?>
<title>Perfil</title>
</head>
<?php

//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";
?>
<div class="container">
	<h2>Página Perfil</h2>
</div>

	
<?php
//Inclui o footer
include_once './include/footer.php';
?>