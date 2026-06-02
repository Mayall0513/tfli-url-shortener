<?php

require_once "../src/responses/JSONResponse.php";

class URLModel {
    private PDO $database;

    public function __construct(PDO $database) {
        $this->database = $database;
    }

    public function validateUrl(string $url): bool {
        /**
         * URL must be 4096 or fewer characters
         */
        return 4096 >= strlen($url);
    }

    public function validateExpiryDateTime(?DateTime $expiryDateTime): bool {
        /**
         * DateTime must be null or in the future
         */
        return null === $expiryDateTime || new DateTime() < $expiryDateTime;
    }

    public function insertOrSelectByUrl(string $url, ?DateTime $expiry): string {
        try {
            /**
             * Insert with the provided url, if we hit the unique constraint, grab the associated shortened url.
             * If we do insert a new url, grab its id and encode it to get the shortened url. Update the new record with this encoded shortened url to finish.
             */

            /**
             * Transaction prevents a second, more harmless race condition where two processes could insert the same shortened url
             */

            $this->database->beginTransaction();
 
            $insertStatement = $this->database->prepare("
                INSERT INTO urls (url, expiry) 
                VALUES (:url, :expiry)
                ON CONFLICT(url)
                DO UPDATE SET expiry = excluded.expiry
                RETURNING id, shortened_url;
            ");

            $insertStatement->execute([
                'url' => $url,
                'expiry' => null === $expiry ? null : $expiry->getTimestamp()
            ]);

            $urlData = $insertStatement->fetch(PDO::FETCH_ASSOC);
            $insertStatement->closeCursor();

            if (null === $urlData['shortened_url']) {
                $shortenedUrl = base64_encode($urlData['id']);

                $updateStatement = $this->database->prepare('UPDATE `urls` SET `shortened_url` = :shortened_url WHERE `id` = :id;');
                $updateStatement->execute([
                    'id' => $urlData['id'],
                    'shortened_url' => $shortenedUrl
                ]);
            }
            else {
                $shortenedUrl = $urlData['shortened_url'];
            }

            $this->database->commit();
            return $shortenedUrl;
        }
        catch (Throwable $exception) {
            if ($this->database->inTransaction()) {
                $this->database->rollBack();
            }

            throw $exception;
        }
    }

    public function selectByShortenedUrl(string $shortenedUrl): ?string {
        $data = [
            'shortened_url' => $shortenedUrl
        ];

        $statement = $this->database->prepare("SELECT url FROM urls WHERE shortened_url = :shortened_url AND (expiry IS NULL OR expiry > unixepoch());");
        $statement->execute($data);
        $url = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $url) {
            return null;
        }
        
        return $url['url'];
    }
}

?>