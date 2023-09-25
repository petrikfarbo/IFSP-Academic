<?php
class Bdtd {
    private $retorno = array();
  
    private $search;
    private $retmax;
    private $retstart;
    private $total;

    function __construct($search, $retmax, $retstart){
        $this->search = $search;
        $this->retmax = $retmax;
        $this->retstart = $retstart+1;
        $this->total = 0;
    }

    function getArticles(){
        //** RECEBER OS ID's DOS ARTIGOS PUBMED **
        $params = array(
            'filter[]' => 'publishDate:"['.date('Y', strtotime('-4 year')).'+TO+'.date('Y').']"', //Banco de dados da consulta
            'lookfor' => urlencode($this->search), //Termo que vai ser pesquisado
            'type' => 'AllFields', //Filtro -> Formato de data 
            'page' => $this->retstart, //Pagina da pesquisa
            'limit' => $this->retmax, //Maximo de retorno apartir do start
        );

        $query_string = http_build_query($params); //Query dos parametros
        $search_url = urldecode('https://www.bdtd.ibict.br/vufind/api/v1/search?' . $query_string); // URL montada com os parametros
        
        $jsonData = file_get_contents($search_url);
        $data = json_decode($jsonData);

        if($data->resultCount > 0){ //Verifica se a pesquisa possui resultados
            $this->total = $data->resultCount; //Recebe a quantidade de artigos que a busca possui
            //$article_ids = array(); 
            
            foreach ($data->records as $obj) { //loop para armazenar os ids dos artigos em um array
                //$article_ids[] = $obj->id;
                $title = $obj->title; //Recebe o titulo do artigo
                $data = $obj->publicationDates[0]; //Recebe a data do artigo
                array_push($this->retorno, 
                '<div class="flex flex-1 flex-col">
                    <a class="link-article" href="https://bdtd.ibict.br/vufind/Record/'.$obj->id.'" target="_blank">
                        <p>'.$title.'</p>
                        <p>'.$data.'</p>
                    </a>
                </div>
                <div class"flex flex-col items-center justify-center">
                    <a href="https://bdtd.ibict.br/vufind/" target="_blank"><img class="h-10" src="assets/img/bdtd.png"></a>
                </div>'); //Adiciona no array já formatado com HTML
            }
            
            //return $this->getDataArticles($article_ids);    
            $this->retorno = array(
                'html' => $this->retorno,
                'totalArtigos' => $this->total
            );
            return $this->retorno;
            exit();
        }else{
            $this->retorno = array(
                'html' => $this->retorno,
                'totalArtigos' => $this->total
            );
            //array_push($this->retorno,'<div class="flex m-auto"> Sem Resultados para esta pesquisa. </div>');
            return $this->retorno;
            exit();
        }
        //** RECEBER OS ID's DOS ARTIGOS PUBMED **
    }

}

//$teste = new Bdtd('Limpeza', 4, 1);
//print_r($bdtdData = $teste->getArticles());
?>
