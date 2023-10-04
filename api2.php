<?php

// URLs que você deseja acessar
$urls = array(
    'http://localhost:81/IFSP/IFSP-Academic/pubmed.php',
    'http://localhost:81/IFSP/IFSP-Academic/scielo.php',
    'http://localhost:81/IFSP/IFSP-Academic/bdtd.php',
);
$html = array();
$total = 0;

// Inicialize o cURL multi handler
$mh = curl_multi_init();

// Inicialize um array para armazenar os handles de cURL individuais
$handles = array();

// Crie e adicione os handles de cURL individuais ao array
foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_multi_add_handle($mh, $ch);
    $handles[] = $ch;
}

// Execute as requisições em paralelo
do {
    curl_multi_exec($mh, $running);
} while ($running > 0);

// Coleta os resultados
$responses = array();

foreach ($handles as $ch) {
    $response = curl_multi_getcontent($ch);
    $responses[] = $response;
    curl_multi_remove_handle($mh, $ch);
    curl_close($ch);
}

// Feche o cURL multi handler
curl_multi_close($mh);

// Agora, $responses conterá as respostas das requisições para as URLs

// Exemplo de como você pode imprimir as respostas
foreach ($responses as $index => $response) {
    $responseArray = json_decode($response, true);
    $totalArtigos = $responseArray['totalArtigos'];
    $Artigos = $responseArray['html'];
    $html = array_merge($html, $Artigos);
    $total = $total + $totalArtigos[0];
}
$retorno = array(
    'html' => $html,
    'total' => $total
);

var_dump($retorno);
exit();


?>