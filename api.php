<?php
require_once 'pubmed.php';
require_once 'scielo.php';
require_once 'bdtd.php';

//$_POST['search'] = 'Cancer';//debug
//verifica se o post foi enviado e se não está vazio
if(isset($_POST['search']) && !empty($_POST['search'])){
    //verifica se o post foi enviado e se não está vazio
    if(isset($_POST['retstart'])){
        $retmax = 4;
        $retstart = $_POST['retstart'];
    }else{
        $retmax = 4;
        $retstart = 0;
    }
    //recebe o termo de pesquisa
    $search = $_POST['search'];
    $html = array(); //Array para armazenar os artigos


    //Recebe os dados dos artigos da pubmed
    $pubmed = new PubMed($search, $retmax, $retstart);
    $pubmedData = $pubmed->getArticles();
    if($pubmedData['totalArtigos'] > 0){
        $html = array_merge($html, $pubmedData['html']);
    }
    

    //Recebe os dados dos artigos da bdtd
    $bdtd = new Bdtd($search, $retmax, $retstart);
    $bdtdData = $bdtd->getArticles();
    if($bdtdData['totalArtigos'] > 0){
        $html = array_merge($html, $bdtdData['html']);
    }


    //Recebe os dados dos artigos da scielo
    $scielo = new SciELO($search, $retmax, $retstart);
    $scieloData = $scielo->getArticles();
    if($scieloData['totalArtigos'] > 0){
        $html = array_merge($html, $scieloData['html']);
    }
    

    //cria um array com os dados dos artigos e o total de artigos encontrados
    $retorno = array(
        'html' => mb_convert_encoding($html, 'UTF-8', 'auto'), //converte o array para utf-8
        'total' => $pubmedData['totalArtigos'] + $scieloData['totalArtigos'] + $bdtdData['totalArtigos'] //soma o total de artigos encontrados
    );

    //embaralha o array
    shuffle($retorno['html']);  

    //retorna o array em json
    echo json_encode($retorno);
  
}

?>