<?php
error_reporting(E_ERROR | E_PARSE);
class Bdtd {
    private $retorno = array();
  
    private $search;
    private $retmax;
    private $retstart;
    private $total;

    function __construct($search, $retmax, $retstart){
        $this->search = $search;
        $this->retmax = $retmax;
        $this->retstart = ($retstart/4)+1; //Divide o retstart por 4 e soma 1 para que o start seja apartir de 1 pois o BDTD começa a partir de 1,2,3,4...
        $this->total = 0;
    }

    //Função para pegar uma string entre duas strings
    function getStr($string, $start, $end, $index) {
        $str = explode($start, $string);
        $str = explode($end, $str[$index + 1]);
        return $str[0];
    }

    function getArticles(){
        //** RECEBER OS ID's DOS ARTIGOS PUBMED **
        $params = array(
            'sort' => 'year',
            'filter[]' => 'publishDate:"['.date('Y', strtotime('-4 year')).'+TO+'.date('Y').']"', //Banco de dados da consulta
            'lookfor' => urlencode($this->search), //Termo que vai ser pesquisado
            'type' => 'AllFields', //Filtro -> Formato de data 
            'page' => $this->retstart, //Pagina da pesquisa
            'limit' => $this->retmax, //Maximo de retorno apartir do start
        );

        $query_string = http_build_query($params); //Query dos parametros
        $search_url = urldecode('https://www.bdtd.ibict.br/vufind/api/v1/search?' . $query_string); // URL montada com os parametros
        
        $jsonData = file_get_contents($search_url); //Carrega o JSON da pagina
        $data = json_decode($jsonData); //Decodifica o JSON

        if($data->resultCount > 0){ //Verifica se a pesquisa possui resultados
            $this->total = $data->resultCount; //Recebe a quantidade de artigos que a busca possui
            //$article_ids = array(); 
            
            foreach ($data->records as $obj) { //loop para armazenar os ids dos artigos em um array
                $articlesData = $this->getArticlesData('https://bdtd.ibict.br/vufind/Record/'.$obj->id); //Recebe os dados do artigo em um array

                $title = $obj->title; //Recebe o titulo do artigo
                //$data = $obj->publicationDates[0]; //Recebe a data do artigo
                array_push($this->retorno, 
                '<div class="flex flex-1 flex-col">
                    <a class="link-article" href="https://bdtd.ibict.br/vufind/Record/'.$obj->id.'" target="_blank">
                        <p class="txt-00a0033 mb-2">'.$title.'</p>
                        <p class="mb-2 text-sky">'.$articlesData['authorlist'].'</p>
                        <p class="mb-2"><b>Resumo: </b>'.$articlesData['texto'].'...</p>
                        <p class="mb-2"><b>Keywords: </b>'.$articlesData['keywords'].'</p>
                        <p class="mb-2"><b>Publicado: </b>'.$articlesData['publicado'].'</p>
                        <p>'.$articlesData['data'].'</p>
                    </a>
                </div>
                <div class"flex flex-col items-center justify-center">
                    <a href="https://bdtd.ibict.br/vufind/" target="_blank"><img class="h-10" src="assets/img/bdtd.png"></a>
                </div>'); //Adiciona no array já formatado com HTML
            }
            
            //retorna um array com os dados dos artigos
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
    
    //Função para pegar os dados dos artigos
    function getArticlesData($link){
        $html = file_get_contents($link); //Carrega o HTML da pagina

        $html = $this->getStr($html, 'class="col-sm-12">', '<div class="record-tabs', 0); //Recebe o HTML do artigo
        
        $authorlist = ""; //Variavel para armazenar os autores
        $keywords = ""; //Variavel para armazenar as palavras chaves
        $texto = ""; //Variavel para armazenar o texto
        $publicado = ""; //Variavel para armazenar a universidade

        for ($i=0; $i < substr_count($html, 'nofollow">'); $i++) {  //loop para armazenar as palavras chaves
            $keywords .= ucfirst(strtolower($this->getStr($html, 'nofollow">', '</a', $i).'; ')); //Recebe as palavras chaves e formatas a primeira letra para maiuscula
        }

        $authorlist = urldecode($this->getStr($html, '?author=', '">', 0)); //Recebe o HTML do artigo
        $authorlist = str_replace('+', ' ', $authorlist); //subistitui o + por espaço

        $texto = substr($this->getStr($html, '<p>', '</', 0), 0, 500); //Recebe o texto do artigo
        $data = $this->getStr($html, 'Data de Defesa:</th><td>', '</', 0); //Recebe a data do artigo
        $publicado = strip_tags($this->getStr($html, 'Organization">', '</', 0)); //Recebe quem publicou o artigo
        $publicado = trim($publicado); //Remove os espaços em branco do inicio e do fim da string
        
        
        if($keywords == ""){ //Verifica se a variavel $keywords é vazia, se for, não adicione o artigo no array
            $keywords = "Não obtido";
        }
        if($texto == ""){ //Verifica se a variavel $texto é vazia, se for, não adicione o artigo no array
            $texto = "Não obtido"; 
        }
        if($publicado == ""){ //Verifica se a variavel $publicado é vazia, se for, não adicione o artigo no array
            $publicado = "Não obtido"; 
        }

        //Retorna um array com os dados do artigo
        return array( 
            'authorlist' => $authorlist,
            'keywords' => $keywords,
            'texto' => $texto,
            'data' => $data,
            'publicado' => $publicado
        );
    }

}

//$teste = new Bdtd('Web Scraping', 4, 0);
//var_dump($teste->getArticles());
?>
