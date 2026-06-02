<?php

require_once "../src/Controller.php";
require_once "../src/responses/JSONResponse.php";
require_once "../src/models/URLModel.php";
require_once "../src/SQLiteDatabase.php";

class URLShortenerController implements IPOSTController {
    public function post(): Response {
        if (!array_key_exists('url', $_POST) || "" === $_POST['url']) {
            return new JSONResponse(400, [ 'ok' => false, 'error' => "MISSING_URL" ]);
        }

        $url = filter_var($_POST['url'], FILTER_VALIDATE_URL);
        if (false === $url) {
            return new JSONResponse(400, [ 'ok' => false, 'error' => "INVALID_URL_PROVIDED" ]);
        }

        if (array_key_exists('expiry', $_POST) && "" !== $_POST['expiry']) {
            $timezone = null;

            if (array_key_exists('timezone', $_POST)) {
                $parsedTimezone = timezone_open($_POST['timezone']);

                if (false !== $parsedTimezone) {
                    $timezone = $parsedTimezone;
                }
            }

            $expiryDateTime = new DateTime($_POST['expiry'], $timezone);
            if (false === $expiryDateTime) {
                return new JSONResponse(400, [ 'ok' => false, 'error' => "INVALID_EXPIRY_PROVIDED" ]);
            }
        }
        else {
            $expiryDateTime = null;
        }

        $sqlite = SQLiteDatabase::getConnection();
        $urlModel = new URLModel($sqlite);

        if (!$urlModel->validateUrl($url)) {
            return new JSONResponse(400, [ 'ok' => false, 'error' => "INVALID_URL_TOO_LONG" ]);
        }

        if (!$urlModel->validateExpiryDateTime($expiryDateTime)) {
            return new JSONResponse(400, [ 'ok' => false, 'error' => "INVALID_EXPIRY_IN_PAST" ]);
        }

        try {
            $shortenedUrl = $urlModel->insertOrSelectByUrl($url, $expiryDateTime);

            // Base64 text can contain non-url friendly characters such as +, encode it to be safe
            return new JSONResponse(200, [ 'ok' => true, 'shortened_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/redirect?surl=' . urlencode($shortenedUrl) ]);
        }
        catch (Throwable $exception) {
            return new JSONResponse(500, [ 'ok' => false, 'error' => print_r($exception, true) ]);
        }
    }
}

?>