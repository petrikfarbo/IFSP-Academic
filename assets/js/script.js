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

    $('.search-btn').click(function(){
        realizarPesquisa();
    });

    $("#search").keypress(function(event) {
        if (event.which === 13) { // 13 é o código da tecla "Enter"
          realizarPesquisa();
        }
    });


    
    function realizarPesquisa() {
        var search = $("#search").val();
        if(search !== searchAnterior){
            $(".search-result").empty();

            $.ajax({
                type: "POST",
                url: "api.php",
                dataType: 'json',
                data: {
                    search: search
                },
                success: function(response) {
                    $.each(response, function(index, element) {
                        var $articleLink = $(element).find('.link-article').attr('href');

                        if (!linkArray.includes($articleLink)) {
                            // Se não estiver no array, adicione-o
                            linkArray.push($articleLink);
                            var divInfo = $('<div class="flex flex-1 flex-row mb-4 border-b-2">');

                            divInfo.html(element);
                            // Adicione a div à página (por exemplo, ao elemento com id "container")
                            $(".search-result").append(divInfo);
                        }else{
                            console.log(!linkArray.includes($articleLink));
                        }
                    });



                    $(".search-result").append('<div class="flex flex-1 flex-col mb-4 border-b-2 items-center justify-center text-center txt-00a0033 carregar-mais"><button class="mais-btn text-white px-4 py-2 rounded-lg">Ver Mais Resultados</button></div>');
                    $(".carregar-mais").append('<div class="flex flex-1 flex-row mb-4 items-center justify-center text-center txt-00a0033"> Petrik Farbo - IFSP Campus São João da Boa Vista<br/>2023');
                    
                    $('.mais-btn').click(function(){
                        carregarMais(search);
                    });
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
                $.each(response, function(index, element) {
                    var $articleLink = $(element).find('.link-article').attr('href');

                    if (!linkArray.includes($articleLink)) {
                        // Se não estiver no array, adicione-o
                        linkArray.push($articleLink);
                        var divInfo = $('<div class="flex flex-1 flex-row mb-4 border-b-2">');

                        divInfo.html(element);
                        // Adicione a div à página (por exemplo, ao elemento com id "container")
                        $(".carregar-mais").before(divInfo);
                    }else{
                        console.log(!linkArray.includes($articleLink));
                    }
                });
                retstart = retstart + 4;
            },
            error: function(xhr, status, error) {
                console.error("Erro na requisição (POST):", error);
            }
        });
    }
});

                