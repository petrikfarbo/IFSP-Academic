// Quando a página é carregada completamente
$(window).on('load', function() {
    // Esconde a tela de carregamento
    $('#loading').fadeOut(500, function() {
        // Mostra o conteúdo do site
        $('#content').fadeIn(1000);
    });
});



$(document).ready(function(){
    // Quando o botão de pesquisa é clicado
    var searchAnterior = "";
    var linkArray = [];
    var retstart = 4;
    var total = 0;

    $('.search-btn').click(function(){
        var search = $("#search").val();
        if(search != ''){
            $('.search-icon').addClass('hidden');
            $('.loading-search').removeClass('hidden');
            realizarPesquisa(search);
        }        
    });

    $("#search").keypress(function(event) {
        if (event.which === 13) { // 13 é o código da tecla "Enter"
            var search = $("#search").val();
            if(search != ''){
                $('.search-icon').addClass('hidden');
                $('.loading-search').removeClass('hidden');
                realizarPesquisa(search);
            }
        }
    });


    
    function realizarPesquisa(search) {
        if(search !== searchAnterior){
            $(".search-result").empty();
            $(".result-total").empty();
            

            $.ajax({
                type: "POST",
                url: "api.php",
                dataType: 'json',
                data: {
                    search: search
                },
                success: function(response) {
                    retstart = 4;
                    total = 0;
                    linkArray = [];      
                    total = total + response['total'];              
                    $.each(response['html'], function(index, element) {
                        var $articleLink = $(element).find('.link-article').attr('href');

                        if (!linkArray.includes($articleLink)) {
                            // Se não estiver no array, adicione-o
                            linkArray.push($articleLink);
                            var divInfo = $('<div class="flex flex-1 flex-row mb-4 border-b-2">');

                            divInfo.html(element);
                            // Adicione a div à página (por exemplo, ao elemento com id "container")
                            $(".search-result").append(divInfo);
                        }else{
                            total = total - 1;
                            console.log(!linkArray.includes($articleLink));
                        }
                    });
                    $('.result-total').append(linkArray.length+'/'+total+' Resultados Encontrados. | '+(response['total']-total)+' Links repetidos.');


                    $(".search-result").append('<div class="flex flex-1 flex-col mb-4 border-b-2 items-center justify-center text-center txt-00a0033 carregar-mais"><button class="mais-btn text-white px-4 py-2 rounded-lg">Ver Mais Resultados</button></div>');
                    $(".carregar-mais").append('<div class="flex flex-1 flex-row mb-4 items-center justify-center text-center txt-00a0033"> Petrik Farbo - IFSP Campus São João da Boa Vista<br/>2023');
                    


                    $('.mais-btn').click(function(){
                        if(linkArray.length < response['total']){
                            $('.mais-btn').prop("disabled",true);
                            $('.mais-btn').empty();
                            $('.mais-btn').append('<svg class="animate-spin h-5 w-5 mr-3 inline-block" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M12 2a10 10 0 110 20 10 10 0 010-20zm0 18a8 8 0 100-16 8 8 0 000 16z"/></svg>Carregando...');
                            carregarMais(search);
                        }else{
                            $('.mais-btn').addClass('hidden');
                        }
                    });



                    $('.loading-search').addClass('hidden');
                    $('.search-icon').removeClass('hidden');                    
                },
                error: function(xhr, status, error) {
                    console.error("Erro na requisição (POST):", error);
                }
            });



            searchAnterior = search;
        }else{
            console.log("AEPA!");
        }



        // Animação de subir
        $('.main').addClass('ease-in duration-1000 h-64');
        $('.main').removeClass('h-screen');
        $('.search-body').removeClass('w-4/5 md:w-3/5 lg:w-2/5 xl:w-2/5 mb-36');
        $('.search-body').addClass('delay-700 ease-in duration-700 w-11/12 mb-3');
        
        setTimeout(function() {
            setTimeout(function() {
                $('.search-result').removeClass('hidden');
                $('.result-total').removeClass('hidden');
            }, 800);

            $('.main').removeClass('justify-center');
        }, 2000);
    }



    function carregarMais(search) {
        $.ajax({
            type: "POST",
            url: "api.php",
            dataType: 'json',
            data: {
                search: search,
                retstart: retstart
            },
            success: function(response) {
                console.log(retstart);
                $.each(response['html'], function(index, element) {
                    var $articleLink = $(element).find('.link-article').attr('href');

                    if (!linkArray.includes($articleLink)) {
                        // Se não estiver no array, adicione-o
                        linkArray.push($articleLink);
                        var divInfo = $('<div class="flex flex-1 flex-row mb-4 border-b-2">');

                        divInfo.html(element);
                        // Adicione a div à página (por exemplo, ao elemento com id "container")
                        $(".carregar-mais").before(divInfo);
                    }else{
                        total = total - 1;
                        console.log(!linkArray.includes($articleLink));
                    }
                });
                retstart = retstart + 4;
                $(".result-total").empty();
                $('.result-total').append(linkArray.length+'/'+total+' Resultados Encontrados. | '+(response['total']-total)+' Links repetidos.');

                $('.mais-btn').prop("disabled",false);
                $('.mais-btn').empty();
                $('.mais-btn').append('Ver Mais Resultados');

                //fazer a condição para o botao carregar mais sumir quando total = tamanho do array
            },
            error: function(xhr, status, error) {
                console.error("Erro na requisição (POST):", error);
            }
        });
    }
});

                