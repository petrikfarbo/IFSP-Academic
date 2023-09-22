<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>IFSP Academic  - Petrik Farbo</title>
        <link rel="icon" href="assets/img/favicon.png">
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <div class="bg-image bg-no-repeat bg-center w-screen h-screen bg-cover"></div>
        <div id="loading" class="w-screen h-screen flex items-center justify-center fixed top-0 left-0 bg-white z-50">
            <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-00a033"></div>
        </div>

        <div id="content" class="hidden">
            <div class="flex flex-col items-center justify-center h-screen main">
                <img src="assets/img/logo.png" alt="IFSP Logo" class="mb-3.5 ease-in duration-800 mt-8 logo">
                <div class="bg-white p-4 rounded-lg shadow-md w-4/5 md:w-3/5 lg:w-2/5 xl:w-2/5 flex mb-36 search-body ">
                    <div class="flex items-center border border-00a033 rounded-l-lg p-2 flex-grow search">
                        <input id="search" type="text" placeholder="Pesquisar..." class="w-full outline-none">
                    </div>
                    <div class="flex justify-end items-center" >
                        <button class="text-white px-4 py-2 rounded-r-lg search-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 search-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            <svg class="animate-spin h-5 w-5 loading-search hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M12 2a10 10 0 110 20 10 10 0 010-20zm0 18a8 8 0 100-16 8 8 0 000 16z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="result-toggles bg-white p-2 shadow-md w-11/12 flex flex-col mb-1 hidden delay-500 ease-in duration-800 ">
                    <div class="flex flex-row items-center toggles">
                        <div class="flex flex-col items-center flex-1">
                            <img class="h-5 mb-2" src="assets/img/pubmed.png">
                            <label for="toggle1" class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" id="toggle1" class="hidden" result="pubmed-result" checked>
                                    <div class="toggle__line w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
                                    <div class="toggle__dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition"></div>
                                </div>
                            </label>
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <img class="h-6 mb-1" src="assets/img/scielo.png">
                            <label for="toggle2" class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" id="toggle2" class="hidden" result="scielo-result" checked >
                                    <div class="toggle__line w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
                                    <div class="toggle__dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition"></div>
                                </div>
                            </label>
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <img class="h-5 mb-2" src="assets/img/bdtd.png">
                            <label for="toggle3" class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" id="toggle3" class="hidden" result="bdtd-result" checked>
                                    <div class="toggle__line w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
                                    <div class="toggle__dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition"></div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="flex result-total pt-2 items-center justify-center">
                        8/360 Resultados Encontraos. | 5 Links repetidos.
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md w-11/12 flex flex-col mb-3 search-result delay-500 ease-in duration-800 hidden"></div>
            </div>
        </div>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="assets/js/script.js"></script>
    </body>
</html>