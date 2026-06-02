<html>
    <head>
        <title>URL Shortener</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="js/index.js"></script>
    </head>
    <body class="bg-slate-600 flex flex-row items-start justify-center">
        <div class="mt-14 text-center min-w-[48rem]">
            <form id="form" method="post">
                <legend class="text-3xl font-bold">URL Shortener</legend>
                <input id="timezone" type="hidden" name="timezone">
                <div class="flex flex-col gap-4 pt-10">                    
                    <input name="url" type="text" class="border rounded-lg p-3 basis-1/2">
                    <div class="flex flex-row gap-2 items-center">
                        <span class="text-base mr-2">Expiry: </span>
                        <input name="expiry" type="datetime-local" class="border rounded-lg p-2 flex-1">
                        <button id="submit_button" type="button" class="bg-blue-300 text-white p-2 rounded-lg basis-1/4">Shorten URL</button>
                    </div>
                    <div id="response_holder"></div>
                </div>
            </form>
        </div>
    </body>
</html>