<?php
if(isset($_POST['search']) && !empty($_POST['search'])){ //Verifica se é uma pesquisa valida
    $retorno = array();

    //** RECEBER OS ID's DOS ARTIGOS PUBMED **
    $base_url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi";
    $params = array(
        'db' => 'pubmed', //Banco de dados da consulta
        'term' => $_POST['search'], //Termo que vai ser pesquisado
        'datetype' => 'edat', //Filtro -> Formato de data 
        'mindate' => date('Y/m/d', strtotime('-5 year')), // Filtro -> 5 Anos menos que a data atual
        'maxdate' => date('Y/m/d'), //Filtro -> Data atual
        'retmax' => 4, //Maximo de retorno apartir do start
        'retstart' => 0, //start da pesquisa apartir do 0
        'api_key' => '2d414b9f14c460cd43012677550fad876b08' //Chave API da pubmed
    );
    
    $query_string = http_build_query($params); //Query dos parametros
    $search_url = $base_url . '?' . $query_string; // URL montada com os parametros

    $xml = simplexml_load_file($search_url); //Carrega o XML da pagina
    if($xml->Count > 0){ //Verifica se a pesquisa possui resultados
        $total = $xml->Count; //Recebe a quantidade de artigos que a busca possui
        
        $article_ids = array(); 
        foreach ($xml->IdList->Id as $id) { //loop para armazenar os ids dos artigos em um array
            $article_ids[] = (string)$id;
        }
    //** RECEBER OS ID's DOS ARTIGOS PUBMED **

    //** CONSULTAR OS ARTIGOS APARTIR DO ID **
        $efetch_base_url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi";
        $efetch_params = array(
            'db' => 'pubmed', //Banco de dados da consulta
            'id' => implode(",", $article_ids), //Ids dos artigos coletados
            'retmode' => 'xml', //especificando o tipo de retorno da pagina
            'api_key' => '2d414b9f14c460cd43012677550fad876b08' //Chave API da pubmed
        );
        $efetch_query_string = http_build_query($efetch_params); //Query dos parametros
        $efetch_url = $efetch_base_url . '?' . $efetch_query_string; // URL montada com os parametros

        $efetch_xml = simplexml_load_file($efetch_url); //Carrega o XML da pagina
        foreach ($efetch_xml->PubmedArticle as $article) { //loop para armazenar os dados dos artigos em um array
            $title = (string)$article->MedlineCitation->Article->ArticleTitle; //Recebe o titulo do artigo
            $data = $article->MedlineCitation->DateRevised->Day.'/'.$article->MedlineCitation->DateRevised->Month.'/'.$article->MedlineCitation->DateRevised->Year; //Recebe a data do artigo
            array_push($retorno, '<div class="flex flex-1 flex-col">
                                    <a href="https://pubmed.ncbi.nlm.nih.gov/'.(string)$article->MedlineCitation->PMID.'" target="_blank">
                                    <p>'.$title.'</p>
                                    <p>'.$data.'</p>
                                </div>
                                <div class"flex">
                                    <a href="https://pubmed.ncbi.nlm.nih.gov/" target="_blank"><img class="h-10" src="assets/img/pubmed.png"></a>
                                </div>'); //Adiciona no array já formatado com HTML
            
        }
        //** CONSULTAR OS ARTIGOS APARTIR DO ID **
    }else{
        array_push($retorno,'<div class="flex m-auto"> Sem Resultados para esta pesquisa. </div>');
    }

    $json_retorno = json_encode($retorno); //Codifica o array em json 
    echo $json_retorno; // imprime o retorno codificado para trabalhar com javascript.
}

?>