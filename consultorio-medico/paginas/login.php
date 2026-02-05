<?php
session_start();
ob_start();
include_once ('conexao/conexao.php');

include './include/head-login.php';


?>


<body class="text-center">
	<?php

	?>
	 <form method="POST" class="form-signin" action="">
	 	<?php
		$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

		//Exemplo criptografar senha
		//	echo password_hash(1234, PASSWORD_DEFAULT);
		
		if (!empty($dados['SendLogin'])) {
			// echo "<pre>";
			// var_dump($dados);
			// echo "</pre>";
			$query_usuario = "SELECT id, nome, crm, senha
					FROM medicos 
					WHERE crm =:crm
					LIMIT 1";

			$result_usuario = $conn->prepare($query_usuario);
			$result_usuario->bindParam(':crm', $dados['crm'], PDO::PARAM_STR);
			$result_usuario->execute();

			if (($result_usuario) AND ($result_usuario->rowCount() != 0)) {
				$row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
				// echo "<pre>";
				// var_dump($row_usuario);
				// echo "</pre>";
				if (password_verify($dados['senha'], $row_usuario['senha'])) {
					$_SESSION['id'] = $row_usuario['id'];
					$_SESSION['nome'] = $row_usuario['nome'];
					header("Location: " . URL . "home");
				}else{
					$_SESSION['msg'] = '<div class="alert alert-danger" role="alert">Usuário ou senha inválidos!</div>';
				}
			}else{
				$_SESSION['msg'] = '<div class="alert alert-danger" role="alert">Usuário ou senha inválidos!</div>';
			}
			
			
		}

		
	?>
	 	<img class="mb-4" src="assets/images/logo2.png" alt="" width="72" height="72">
  			<h1 class="h3 mb-3 font-weight-normal">Acesso Médico</h1>
			 	<?php
			 	if (isset($_SESSION['msg'])) {
					echo $_SESSION['msg'];
					unset($_SESSION['msg']);
				}
				?>
		  <label for="crm" class="sr-only">CRM</label>
			  <input name="crm" type="text" id="crm" class="form-control mb-4" placeholder="Digite o CRM" value="<?php if (isset($dados['crm'])) {
			  	echo $dados['crm'];
			  } ?>" required autofocus>
			  <label for="senha" class="sr-only">Senha</label>
			  <div class="senha">
			  	<input name="senha" type="password" id="senha" class="form-control mb-4" placeholder="Digite a Senha" required><!--<i class="bi bi-eye-fill" id="btn-senha" onclick="mostrarSenha()"></i>-->
			  </div>
			  <input name="SendLogin" type="submit" value="Acessar" class="btn btn-lg btn-primary btn-block">
			  <p><a href="#">Esqueci a senha</a></p>
	</form>


	<?php  
		include_once './include/footer.php';
	?>
</body>
</html>

