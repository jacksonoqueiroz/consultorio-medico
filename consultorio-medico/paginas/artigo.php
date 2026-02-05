<?php

echo "Página de artigo.";

//RECEBER O VALOR ENVIADO PELO URL AMIGAVEL
echo "<pre>";
var_dump($url[1]);
echo "</pre>";


//RECEBER O VALOR ENVIADO NA VARIÁVEL DA URL
$situacao = filter_input(INPUT_GET, 'situacao', FILTER_SANITIZE_NUMBER_INT);

echo "<pre>";
var_dump($situacao);
echo "</pre>";