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
                            <svg class="animate-spin h-5 w-5 loading-search hidden" viewBox="3 3 18 18">
                                <path class="opacity-20" d="M12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5ZM3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12Z"></path>
                                <path fill="#ffffff" d="M16.9497 7.05015C14.2161 4.31648 9.78392 4.31648 7.05025 7.05015C6.65973 7.44067 6.02656 7.44067 5.63604 7.05015C5.24551 6.65962 5.24551 6.02646 5.63604 5.63593C9.15076 2.12121 14.8492 2.12121 18.364 5.63593C18.7545 6.02646 18.7545 6.65962 18.364 7.05015C17.9734 7.44067 17.3403 7.44067 16.9497 7.05015Z"></path>
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
                <div class="bg-white rounded-lg shadow-md w-11/12 flex flex-col mb-3 search-result delay-500 ease-in duration-800 hidden"></div>
            </div>
        </div>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="assets/js/script.js"></script>
    </body>
</html>