<?php

require_once "../src/Controller.php";
require_once "../src/responses/JSONResponse.php";
require_once "../src/responses/RedirectResponse.php";
require_once "../src/responses/PageNotFoundResponse.php";
require_once "../src/models/URLModel.php";
require_once "../src/SQLiteDatabase.php";

class URLRedirectController implements IGETController {
    public function get(): Response {
        if (!array_key_exists('surl', $_GET) || "" === $_GET['surl']) {
            return new RedirectResponse('http://' . $_SERVER['HTTP_HOST']);
        }

        $shortened_url = $_GET['surl'];
        if (false === base64_decode($shortened_url, true)) {
            return JSONResponse(400, [ 'ok' => false, 'error' => "INVALID_SHORTENED_URL" ]);
        }

        $sqlite = SQLiteDatabase::getConnection();
        $urlModel = new URLModel($sqlite);

        try {
            $url = $urlModel->selectByShortenedUrl($shortened_url);
            if (null === $url) {
                return new PageNotFoundResponse();
            }

            return new RedirectResponse($url);
        }
        catch (Throwable $exception) {
            return new JSONResponse(500, [ 'ok' => false ]);
        }
    }
}

?>