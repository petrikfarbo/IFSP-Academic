<?php
require_once 'pubMed.php';
require_once 'scielo.php';
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

    $pubmed = new PubMed($search, $retmax, $retstart);
    $pubmedData = $pubmed->getArticles();

    


    $scielo = new SciELO($search, $retmax, $retstart);
    $scieloData = $scielo->getArticles();





    $retorno = array_merge($pubmedData['html'], $scieloData['html']);
    shuffle($retorno);  
    echo json_encode($retorno);
    //$total = $pubmedData['totalArtigos'] + $scieloData['totalArtigos'];


}





?>