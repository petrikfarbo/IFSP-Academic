<?php
error_reporting(E_ERROR | E_PARSE);
class SciELO {
    private $retorno = array();
  
    private $search;
    private $retmax;
    private $retstart;

    function __construct($search, $retmax, $retstart){
        $this->search = $search;
        $this->retmax = $retmax;
        $this->retstart = $retstart+1;
    }

    function getStr($string, $start, $end, $index) {
        $str = explode($start, $string);
        $str = explode($end, $str[$index + 1]);
        return $str[0];
    }

    function getArticles() {
        //** RECEBER OS ID's DOS ARTIGOS SCIELO **
        $ano4 = date('Y', strtotime('-4 year')); //2019
        $ano3 = date('Y', strtotime('-3 year')); //2020
        $ano2 = date('Y', strtotime('-2 year')); //2021
        $ano1 = date('Y', strtotime('-1 year')); //2022
        $anoAtual = date('Y'); //2023

        $params = array(
            'count' => $this->retmax, //Maximo de artigos retornado a pagina
            'q' => $this->search, //Termo que vai ser pesquisado
            'filter[year_cluster][]' => $anoAtual.'&filter[year_cluster][]='.$ano1.'&filter[year_cluster][]='.$ano2.'&filter[year_cluster][]='.$ano3.'&filter[year_cluster][]='.$ano4,
            'from' => $this->retstart //start da pesquisa apartir do 1
        );

        $query_string = http_build_query($params); //Query dos parametros
        $search_url = 'https://search.scielo.org/?' . $query_string; // URL montada com os parametros

    
        $ch = curl_init(); //Inicializar cURL
        curl_setopt($ch, CURLOPT_URL, $search_url); //seta a URL no cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //autoriza o retorno da pagina

        $html = curl_exec($ch); //Executa o cURL
        curl_close($ch); //Fechar a conexão cURL

    
        $dom = new DOMDocument(); //Criar um novo objeto DOMDocument
        @$dom->loadHTML($html); //Carregar o HTML retornado pela solicitação cURL e o '@' para n retornar os erros do HTML da SCIELO

        $resultArea = $dom->getElementById("ResultArea"); //Encontrar o elemento com o ID "ResultArea"
        $totalArtigos = $dom->getElementById("TotalHits"); //Encontrar o elemento com o ID "TotalHits"

        // Verificar se o elemento foi encontrado
        if ($resultArea) {
            // Converter o elemento em HTML
            $resultHtml = $dom->saveHTML($resultArea); //HTML do resultado das pesquisas
            $totalArtigos = $dom->saveHTML($totalArtigos); //Total dos dados


            $totalArtigos = $this->getStr($totalArtigos, 'TotalHits">', '</', 0); //Total de artigos encontrados
            $totalArtigos = str_replace(' ', '', $totalArtigos);
            if($totalArtigos < $this->retstart){//compara o total com o start para saber se ainda possui artigos
                $this->retorno = array(
                    'html' => $this->retorno,
                    'totalArtigos' => $totalArtigos
                );
                return $this->retorno;
                exit();
            }

            if($totalArtigos >= 4){ //Se a quantidade total de artigos for superior a 4
                for ($i = 0; $i < 4; $i++) { //loop para armazenar os dados dos artigos em um array
                    $title = $this->getStr($resultHtml, 'st_title="', '"', $i); //Recebe o titulo do artigo
                    //$data = $this->getStr($resultHtml, '<span style="margin: 0">', '</', ($i*2)+1); //Recebe a data do artigo
                    $link = $this->getStr($resultHtml, 'st_url="http://', '"', $i); //Recebe o link do artigo

                    $articlesData = $this->getArticlesData($link); //Recebe os dados do artigo em um array

                    array_push($this->retorno, 
                        '<div class="flex flex-1 flex-col mr-5">
                            <a class="link-article" href="https://'.$link.'&lang=pt" target="_blank">
                                <p class="txt-00a0033 mb-2">'.$title.'</p>
                                <p class="mb-2 text-sky">'.$articlesData['authorlist'].'</p>
                                <p class="mb-2"><b>Resumo: </b>'.$articlesData['texto'].'...</p>
                                <p class="mb-2"><b>Keywords: </b>'.$articlesData['keywords'].'</p>
                                <p class="mb-2"><b>Publicado: </b>'.$articlesData['publicado'].'</p>
                                <p>'.$articlesData['data'].'</p>
                            </a>
                        </div>
                        <div class="flex flex-col justify-center items-center">
                            <a href="https://pubmed.ncbi.nlm.nih.gov/" target="_blank"><img class="h-10" src="assets/img/scielo.png"></a>
                        </div>'
                    );//Adiciona no array já formatado com HTML
                }
            }else{
                for ($i = 0; $i < $totalArtigos; $i++) { //loop para armazenar os dados dos artigos em um array
                    $title = $this->getStr($resultHtml, 'st_title="', '"', $i); //Recebe o titulo do artigo
                    //$data = $this->getStr($resultHtml, '<span style="margin: 0">', ',', ($i*2)+1); //Recebe a data do artigo
                    $link = $this->getStr($resultHtml, 'st_url="http://', '"', $i); //Recebe o link do artigo
    
                    $articlesData = $this->getArticlesData($link); //Recebe os dados do artigo em um array

                    array_push($this->retorno, 
                        '<div class="flex flex-1 flex-col mr-5">
                            <a class="link-article" href="https://'.$link.'&lang=pt" target="_blank">
                                <p class="txt-00a0033 mb-2">'.$title.'</p>
                                <p class="mb-2 text-sky">'.$articlesData['authorlist'].'</p>
                                <p class="mb-2"><b>Resumo: </b>'.$articlesData['texto'].'...</p>
                                <p class="mb-2"><b>Keywords: </b>'.$articlesData['keywords'].'</p>
                                <p class="mb-2"><b>Publicado: </b>'.$articlesData['publicado'].'</p>
                                <p>'.$articlesData['data'].'</p>
                            </a>
                        </div>
                        <div class="flex flex-col justify-center items-center">
                            <a href="https://pubmed.ncbi.nlm.nih.gov/" target="_blank"><img class="h-10" src="assets/img/scielo.png"></a>
                        </div>'
                    );//Adiciona no array já formatado com HTML
                }
            }

            
            $this->retorno = array(
                'html' => $this->retorno,
                'totalArtigos' => $totalArtigos
            );
            return $this->retorno;
        }
    } 

    function getArticlesData($link){
        $ch = curl_init(); //Inicializar cURL
        curl_setopt($ch, CURLOPT_URL, str_replace('amp;', '', $link)); //seta a URL no cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //autoriza o retorno da pagina
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //autoriza redirecionamento 

        $html = curl_exec($ch); //Executa o cURL
        curl_close($ch); //Fechar a conexão cURL

        $authorlist = ""; //Variavel para armazenar os autores
        $keywords = ""; //Variavel para armazenar as palavras chaves
        $texto = ""; //Variavel para armazenar o texto

        for ($i=0; $i < substr_count($html, 'citation_author" content="'); $i++) {  //loop para armazenar os autores
            $authorlist .= $this->getStr($html, 'citation_author" content="', '"', $i).';';
        }
        
        for ($i=0; $i < substr_count($html, 'citation_keywords" content="'); $i++) {  //loop para armazenar as palavras chaves
            $keywords .= $this->getStr($html, 'citation_author" content="', '"', $i).';';
        }
        
        
        $texto = substr($this->getStr($html, 'citation_abstract" content="', '"', 0), 0, 500); //Recebe o texto do artigo
        $data = $this->getStr($html, 'citation_publication_date" content="', '"', 0); //Recebe a data do artigo
        $publicado = $this->getStr($html, 'citation_publisher" content="', '"', 0); //Recebe o local de publicação do artigo
        
        
        if($keywords == ""){ //Verifica se a variavel $keywords é vazia, se for, não adicione o artigo no array
            $keywords = "Não obtido";
        }
        if($texto == ""){ //Verifica se a variavel $texto é vazia, se for, não adicione o artigo no array
            $texto = "Não obtido"; 
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

//$teste = new SciELO('Cancer', 4, 0);
//print_r($teste->getArticles());
?>
