// Quando a página é carregada completamente
$(window).on('load', function() {
    // Esconde a tela de carregamento
    $('#loading').fadeOut(500, function() {
        // Mostra o conteúdo do site
        $('#content').fadeIn(1000);
    });
});



// Path: IFSP-Academic/assets/js/script.js
$(document).ready(function(){
    //define as variaveis
    let search = "";
    let searchAnterior = "";
    let linkArray = [];
    let retstart = 4;
    let total = 0;
    


    //função para iniciar a pesquisa ao clicar no botão de pesquisa
    $('.search-btn').click(realizarPesquisa);

    //função para iniciar a pesquisa ao apertar a tecla enter
    $("#search").keypress(function(event) {
        if (event.which === 13) { // 13 é o código da tecla "Enter"
            realizarPesquisa()
        }
    });



    // Função para realizar a pesquisa
    function realizarPesquisa() {
        //seta a pesquisa para a variavel
        search = $("#search").val();

        //verifica se a pesquisa não está vazia
        if(search != ""){
            //esconde o icone de pesquisa e mostra o loading sinalizando que a pesquisa está sendo realizada
            $('.search-icon').addClass('hidden');
            $('.loading-search').removeClass('hidden');

            if(search !== searchAnterior){
                //limpar a tela das pesquisas anteriores
                $(".search-result").empty();
                $(".result-total").empty();
                
                //seta os toggles para true por se tratar de uma nova pesquisa
                $('#toggle1').prop('checked', true);
                $('#toggle2').prop('checked', true);
                $('#toggle3').prop('checked', true);
                
                //carrega o loading na tela de pesquisa 
                $(".search-result").append('<div class="flex items-center justify-center text-center txt-00a0033 m-5"><svg class="animate-spin w-6 h-6 mr-3" viewBox="3 3 18 18"><path class="opacity-20" d="M12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5ZM3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12Z"></path><path fill="#00a003aa" d="M16.9497 7.05015C14.2161 4.31648 9.78392 4.31648 7.05025 7.05015C6.65973 7.44067 6.02656 7.44067 5.63604 7.05015C5.24551 6.65962 5.24551 6.02646 5.63604 5.63593C9.15076 2.12121 14.8492 2.12121 18.364 5.63593C18.7545 6.02646 18.7545 6.65962 18.364 7.05015C17.9734 7.44067 17.3403 7.44067 16.9497 7.05015Z"></path></svg>Carregando...');

                //faz a requisição ajax para a api passando apenas o parametro de pesquisa
                $.ajax({
                    type: "POST",
                    url: "api.php",
                    dataType: 'json',
                    data: {
                        search: search
                    },
                    success: function(response) {
                        //limpa a tela de resultados anteriores para remover o loading
                        $(".search-result").empty();
                        
                        //seta os novos valores das variaveis
                        retstart = 4;
                        total = 0;
                        linkArray = [];      
                        total = total + response['total'];

                        //verifica se o total de resultados é maior que o tamanho do array de links para habilitar ou não o botão de carregar mais resultados
                        if(response['total'] == 0){
                            $('.mais-btn').prop("disabled",true);
                            $('.mais-btn').addClass('hidden');
                        }

                        //adiciona os resultados da pesquisa na tela e trata os links repetidos 
                        $.each(response['html'], function(index, element) {
                            //recebe o link do artigo
                            let $articleLink = $(element).find('.link-article').attr('href');
                            
                            //verifica se o link já está no array
                            if (!linkArray.includes($articleLink)) {
                                // Se não estiver no array, adicione-o
                                linkArray.push($articleLink);
                                
                                //cria a div que vai receber as informações do artigo já formatado com as classes do tailwind
                                let divInfo;
                                if($articleLink.includes('pubmed')){
                                    divInfo = $('<div class="flex flex-1 flex-row border-b-2 p-4 hover:bg-gray-100 pubmed-result">');
                                }else if($articleLink.includes('scielo')){
                                    divInfo = $('<div class="flex flex-1 flex-row border-b-2 p-4 hover:bg-gray-100 scielo-result">');
                                }else if($articleLink.includes('bdtd')){
                                    divInfo = $('<div class="flex flex-1 flex-row border-b-2 p-4 hover:bg-gray-100 bdtd-result">');
                                }
                                
                                //adiciona as informações do artigo na div
                                divInfo.html(element);

                                //adiciona a divInfo na tela
                                $(".search-result").append(divInfo);
                            }else{
                                //se o link já estiver no array, diminui o total de resultados
                                total--;
                                //console.log(!linkArray.includes($articleLink)); //debug
                                //console.log(linkArray); //debug
                            }
                        });
                        //adiciona o total de resultados na tela e o total de links repetidos
                        $('.result-total').append(linkArray.length+'/'+total+' Resultados Encontrados. | '+(response['total']-total)+' Links repetidos.');
    
                        //adiciona o botão de carregar mais resultados no final da tela
                        $(".search-result").append('<div class="flex flex-1 flex-col mb-4 mt-4 items-center justify-center text-center txt-00a0033 carregar-mais"><button class="mais-btn text-white px-4 py-2 rounded-lg">Ver Mais Resultados</button></div>');
                        $(".carregar-mais").append('<div class="flex flex-1 flex-row mb-4 items-center justify-center text-center txt-00a0033"> Petrik Farbo - IFSP Campus São João da Boa Vista<br/>2023');
                        
    
                        //funcao para carregar mais resultados ao clicar no botão caso o total de resultados seja maior que o tamanho do array de links
                        $('.mais-btn').click(function(){
                            //se for, desabilita o botão e adiciona o loading
                            if(linkArray.length < response['total'] - (response['total']-total)){
                                $('.mais-btn').prop("disabled",true);
                                $('.mais-btn').empty();
                                $('.mais-btn').append('<svg class="animate-spin h-5 w-5 mr-3 inline-block" viewBox="3 3 18 18"><path class="opacity-20" d="M12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5ZM3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12Z"></path><path fill="#ffffff" d="M16.9497 7.05015C14.2161 4.31648 9.78392 4.31648 7.05025 7.05015C6.65973 7.44067 6.02656 7.44067 5.63604 7.05015C5.24551 6.65962 5.24551 6.02646 5.63604 5.63593C9.15076 2.12121 14.8492 2.12121 18.364 5.63593C18.7545 6.02646 18.7545 6.65962 18.364 7.05015C17.9734 7.44067 17.3403 7.44067 16.9497 7.05015Z"></path></svg>Carregando...');
                                carregarMais(search);
                            }else{
                                //se não for, esconde o botão de carregar mais resultados
                                $('.mais-btn').addClass('hidden');
                            }
                        });
    
                        //apos carregar os resultados, esconde o loading e mostra o icone de pesquisa novamente. 
                        $('.loading-search').addClass('hidden');
                        $('.search-icon').removeClass('hidden');                    
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro na requisição (POST):", error);
                    }
                });

                //seta a pesquisa anterior para a atual
                searchAnterior = search;
            }else{
                $('.search-icon').removeClass('hidden');
                $('.loading-search').addClass('hidden');
                console.log("AEPA!");
            }
        }

        // Animations para a tela de pesquisa
        $('.main').addClass('ease-in duration-1000 h-64');
        $('.main').removeClass('h-screen');
        $('.search-body').removeClass('w-4/5 md:w-3/5 lg:w-2/5 xl:w-2/5 mb-36');
        $('.search-body').addClass('delay-700 ease-in duration-700 w-11/12 mb-3');

        //função para ajustar o delay da animação
        setTimeout(function() {
            setTimeout(function() {
                $('.search-result').removeClass('hidden');
                $('.result-toggles').removeClass('hidden');
            }, 800);

            $('.main').removeClass('justify-center');
        }, 2000);
    }



    // Função para carregar mais resultados
    function carregarMais(search) {
        //faz a requisição ajax para a api passando o parametro de pesquisa e o retstart
        $.ajax({
            type: "POST",
            url: "api.php",
            dataType: 'json',
            data: {
                search: search,
                retstart: retstart
            },
            success: function(response) {
                //adiciona os resultados da pesquisa na tela e trata os links repetidos
                $.each(response['html'], function(index, element) {
                    //recebe o link do artigo
                    let $articleLink = $(element).find('.link-article').attr('href');

                    //verifica se o link já está no array
                    if (!linkArray.includes($articleLink)) {
                        // Se não estiver no array, adicione-o
                        linkArray.push($articleLink);

                        //cria a div que vai receber as informações do artigo já formatado com as classes do tailwind
                        let divInfo;
                        if($articleLink.includes('pubmed')){
                            divInfo = $('<div class="flex flex-1 flex-row border-b-2 p-4 hover:bg-gray-100 pubmed-result pubmed-result">');
                        }else if($articleLink.includes('scielo')){
                            divInfo = $('<div class="flex flex-1 flex-row border-b-2 p-4 hover:bg-gray-100 pubmed-resultscielo-result">');
                        }else if($articleLink.includes('bdtd')){
                            divInfo = $('<div class="flex flex-1 flex-row border-b-2 p-4 hover:bg-gray-100 pubmed-result bdtd-result">');
                        }

                        //adiciona as informações do artigo na div
                        divInfo.html(element);

                        //adiciona a divInfo na tela
                        $(".carregar-mais").before(divInfo);

                        //verifica o estado dos toggles para exibir ou não o resultado
                        vrfToggles($('#toggle1'));
                        vrfToggles($('#toggle2'));
                        vrfToggles($('#toggle3'));
                    }else{
                        //se o link já estiver no array, diminui o total de resultados
                        total--;
                        //console.log($articleLink);
                        //console.log(linkArray);
                    }
                });
                //incrementa o retstart para a proxima pesquisa
                retstart = retstart + 4;

                //adiciona o total de resultados na tela e o total de links repetidos atualizado
                $(".result-total").empty();
                $('.result-total').append(linkArray.length+'/'+total+' Resultados Encontrados. | '+(response['total']-total)+' Links repetidos.');

                //habilita o botão de carregar mais resultados
                $('.mais-btn').prop("disabled",false);
                $('.mais-btn').empty();
                $('.mais-btn').append('Ver Mais Resultados');
            },
            error: function(xhr, status, error) {
                console.error("Erro na requisição (POST):", error);
            }
        });
    }

    // Função para verificar o estado dos toggles
    function vrfToggles($toggle) {
        if ($toggle.is(':checked')) {
            $('.'+$toggle.attr('result')).removeClass('hidden');
        } else {
            $('.'+$toggle.attr('result')).addClass('hidden');
        }
    }

    // verifica o estado dos toggles ao clicar neles
    $('.relative').click(function () {
        vrfToggles($('#toggle1'));
        vrfToggles($('#toggle2'));
        vrfToggles($('#toggle3'));
    });
});