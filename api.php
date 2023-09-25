<?php
require_once 'pubMed.php';
require_once 'scielo.php';
require_once 'bdtd.php';
//* PUBMED 
//$_POST['search'] = 'limpeza';
if(isset($_POST['search']) && !empty($_POST['search'])){

    if(isset($_POST['retstart'])){
        $retmax = 4;
        $retstart = $_POST['retstart'];
    }else{
        $retmax = 4;
        $retstart = 0;
    }
    $search = $_POST['search'];
    $html = array();



    $pubmed = new PubMed($search, $retmax, $retstart);
    $pubmedData = $pubmed->getArticles();
    if($pubmedData['totalArtigos'] > 0){
        $html = array_merge($html, $pubmedData['html']);
    }
    


    $bdtd = new Bdtd($search, $retmax, $retstart);
    $bdtdData = $bdtd->getArticles();
    if($bdtdData['totalArtigos'] > 0){
        $html = array_merge($html, $bdtdData['html']);
    }



    $scielo = new SciELO($search, $retmax, $retstart);
    $scieloData = $scielo->getArticles();
    if($scieloData['totalArtigos'] > 0){
        $html = array_merge($html, $scieloData['html']);
    }
    

    $retorno = array(
        'html' => $html,
        'total' => $pubmedData['totalArtigos'] + $scieloData['totalArtigos'] + $bdtdData['totalArtigos']
    );
    shuffle($retorno['html']);  
    echo json_encode($retorno);
  
}





?>