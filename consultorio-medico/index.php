<?php

//Incluir o arquivo de configuração
include_once './config/config.php';

//Receber da URL do .htaccess. Senão existir o nome da página, carregar a página inicial (login).

$url = (!empty(filter_input(INPUT_GET, 'url', FILTER_DEFAULT)) ? filter_input(INPUT_GET, 'url', FILTER_DEFAULT) : 'login');

//------ESSE CÓDIGO ACIMA CASO NÃO FOR SISTEMA USAR HOME NO LUGAR DO LOGIN PARA INICIAR------

// echo "<pre>";
// var_dump($url);
// echo "</pre>";

//Converter a URL de uma string para um array.
$url = array_filter(explode('/', $url));

// echo "<pre>";
// var_dump($url);
// echo "</pre>";

//Criar o caminho da página com o nome que está na primeira posição do array criado acima e atribuir a extensão .php.

$arquivo = 'paginas/' . $url[0] . '.php';

// echo "<pre>";
// var_dump($arquivo);
// echo "</pre>";

if (is_file($arquivo)) {
	include $arquivo;
	
}else{
	header("Location: " . URL . "login");
	$_SESSION['msg'] = '<div class="alert alert-danger" role="alert">Página NÃO encontrada!</div>';
}
