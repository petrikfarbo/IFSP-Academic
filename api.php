<?php
require_once 'pubMed.php';
//* PUBMED 
if(isset($_POST['search']) && !empty($_POST['search'])){
    if(isset($_POST['retmax']) && isset($_POST['retstart'])){
        $retmax = $_POST['retmax'];
        $retstart = $_POST['retstart'];
    }else{
        $retmax = 4;
        $retstart = 0;
    }
    $search = $_POST['search'];

    $pubmed = new PubMed($search, $retmax, $retstart);
    echo $pubmed->getArticles();

}





?>