<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>IFSP Academic  - Petrik Farbo</title>
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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md w-11/12 flex flex-col mb-3 search-result ease-in duration-800 hidden">
                    
                </div>
            </div>
        </div>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="assets/js/script.js"></script>
    </body>
</html>
