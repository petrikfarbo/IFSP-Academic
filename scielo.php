<?php
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
        curl_setopt($ch, CURLOPT_URL, urldecode($search_url)); //seta a URL no cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //autoriza redirecionamento 

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

            for ($i = 0; $i < 4; $i++) { //loop para armazenar os dados dos artigos em um array
                $code = $this->getStr($resultHtml, 'title" id="title-', '">', $i); //Recebe o codigo do artigo
                $title = $this->getStr($resultHtml, 'title" id="title-'.$code.'">', '</', 0); //Recebe o titulo do artigo
                $data = $this->getStr($resultHtml, '<span style="margin: 0">', '</', $i*2).'/'.$this->getStr($resultHtml, '<span style="margin: 0">', '</', ($i*2)+1); //Recebe a data do artigo

                array_push($this->retorno, 
                    '<div class="flex flex-1 flex-col">
                        <a class="link-article" href="https://www.scielo.br/scielo.php?script=sci_arttext&pid='.$code.'&lang=pt" target="_blank">
                            <p>'.$title.'</p>
                            <p>'.$data.'</p>
                        </a>
                    </div>
                    <div class"flex flex-col items-center justify-center">
                        <a href="https://scielo.org/" target="_blank"><img class="h-10" src="assets/img/scielo.png"></a>
                    </div>'
                ); //Adiciona no array já formatado com HTML
            }
            $this->retorno = array(
                'html' => $this->retorno,
                'totalArtigos' => $totalArtigos
            );
            return $this->retorno;
            exit();
        } else {
            //echo 'Elemento com ID "ResultArea" não encontrado na página.';
        }
    }

}
?>
