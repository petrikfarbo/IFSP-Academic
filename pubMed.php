<?php
error_reporting(E_ERROR | E_PARSE);
class PubMed {
    private $retorno = array();
  
    private $search;
    private $retmax;
    private $retstart;
    private $total;

    function __construct($search, $retmax, $retstart){
        $this->search = $search;
        $this->retmax = $retmax;
        $this->retstart = $retstart;
        $this->total = 0;
    }

    function getArticles(){
        //** RECEBER OS ID's DOS ARTIGOS PUBMED **
        $params = array(
            'db' => 'pubmed', //Banco de dados da consulta
            'term' => $this->search, //Termo que vai ser pesquisado
            'datetype' => 'edat', //Filtro -> Formato de data 
            'mindate' => date('Y/m/d', strtotime('-5 year')), // Filtro -> 5 Anos menos que a data atual
            'maxdate' => date('Y/m/d'), //Filtro -> Data atual
            'retmax' => $this->retmax, //Maximo de retorno apartir do start
            'retstart' => $this->retstart, //start da pesquisa apartir do 0
            'api_key' => '2d414b9f14c460cd43012677550fad876b08' //Chave API da pubmed
        );

        $query_string = http_build_query($params); //Query dos parametros
        $search_url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?' . $query_string; // URL montada com os parametros

        $xml = simplexml_load_file($search_url); //Carrega o XML da pagina

        if($xml->Count > 0){ //Verifica se a pesquisa possui resultados
            $this->total = $xml->Count; //Recebe a quantidade de artigos que a busca possui
            $article_ids = array();  //Array para armazenar os ids dos artigos

            foreach ($xml->IdList->Id as $id) { //loop para armazenar os ids dos artigos em um array
                $article_ids[] = (string)$id; //Adiciona os ids no array
            }

            return $this->getArticlesData($article_ids); //Retorna os dados dos artigos    
            exit();
        }else{
            $this->retorno = array(     
                'html' => $this->retorno,
                'totalArtigos' => $this->total
            );
            
            return $this->retorno; //Retorna o array com os dados
            exit();
        }
        //** RECEBER OS ID's DOS ARTIGOS PUBMED **
    }

    function getArticlesData($article_ids){
        //** CONSULTAR OS ARTIGOS APARTIR DO ID **
        $efetch_params = array(
            'db' => 'pubmed', //Banco de dados da consulta
            'id' => implode(",", $article_ids), //Ids dos artigos coletados
            'retmode' => 'xml', //especificando o tipo de retorno da pagina
            'api_key' => '2d414b9f14c460cd43012677550fad876b08' //Chave API da pubmed
        );
        
        $efetch_query_string = http_build_query($efetch_params); //Query dos parametros
        $efetch_url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?' . $efetch_query_string; // URL montada com os parametros
        $efetch_xml = simplexml_load_file($efetch_url); //Carrega o XML da pagina

        foreach ($efetch_xml->PubmedArticle as $article) { //loop para armazenar os dados dos artigos em um array
            $authorlist = ""; //Variavel para armazenar os autores
            $keywords = ""; //Variavel para armazenar as keywords
            $texto = substr((string)$article->MedlineCitation->Article->Abstract->AbstractText, 0, 500); //Recebe o texto do artigo

            foreach($article->MedlineCitation->Article->AuthorList->Author as $authorInfo){ //loop para armazenar os autores
                $lastname = (string)$authorInfo->LastName; 
                $forename = (string)$authorInfo->ForeName;
                $authorlist .= $lastname.' '.$forename.', '; //Adiciona os autores na variavel
            }
            $authorlist = $authorlist.'PMID: '.(string)$article->MedlineCitation->PMID; //Adiciona o PMID no final da variavel

            foreach ($article->MedlineCitation->KeywordList->Keyword as $keyword) { //loop para armazenar as keywords
                $keywords .= (string)$keyword.'; '; //Adiciona as keywords na variavel
            }

            //faça uma verificação se o as variaveis $keywords e $texto são vazias, se forem, não adicione o artigo no array
            if($keywords == ""){
                $keywords = "Não obtido";
            }
            if($texto == ""){
                $texto = "Não obtido";
            }
            

            

            $title = (string)$article->MedlineCitation->Article->ArticleTitle; //Recebe o titulo do artigo
            $data = $article->MedlineCitation->DateRevised->Day.'-'.$article->MedlineCitation->DateRevised->Month.'-'.$article->MedlineCitation->DateRevised->Year; //Recebe a data do artigo
            array_push($this->retorno, 
            '<div class="flex flex-1 flex-col mr-5">
                <a class="link-article" href="https://pubmed.ncbi.nlm.nih.gov/'.(string)$article->MedlineCitation->PMID.'" target="_blank">
                    <p class="txt-00a0033 mb-2">'.$title.'</p>
                    <p class="mb-2 text-sky">'.$authorlist.'</p>
                    <p class="mb-2"><b>Resumo: </b>'.$texto.'...</p>
                    <p class="mb-2"><b>Keywords: </b>'.$keywords.'</p>
                    <p>'.$data.'</p>
                </a>
            </div>
            <div class="flex flex-col justify-center items-center">
                <a href="https://pubmed.ncbi.nlm.nih.gov/" target="_blank"><img class="h-10" src="assets/img/pubmed.png"></a>
            </div>');//Adiciona no array já formatado com HTML
            
        }
        //** CONSULTAR OS ARTIGOS APARTIR DO ID **

        $this->retorno = array(
            'html' => $this->retorno,
            'totalArtigos' => $this->total
        );
        return $this->retorno;
    }

}

//$teste = new PubMed('Cancer', 4, 0);
//var_dump($teste->getArticles());
?>
