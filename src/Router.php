<?php

require_once "../src/Controller.php";
require_once "../src/controllers/URLShortenerController.php";
require_once "../src/controllers/URLRedirectController.php";
require_once "../src/controllers/IndexController.php";

class Router {
    private array $routes = [
        '/api/v1/url_shortener' => URLShortenerController::class,
        '/redirect' => URLRedirectController::class,
        '/' => IndexController::class
    ];

    public function route(string $method, string $url): Response {
        if (true === array_key_exists($url, $this->routes)) {
            $relevantControllerName = $this->routes[$url];
            $relevantController = new $relevantControllerName();

            // Check the relevant controller supports the requested method

            if ("GET" === $method && $relevantController instanceof IGETController) {
                return $relevantController->get();
            }
            
            if ("POST" === $method && $relevantController instanceof IPostController) {
                return $relevantController->post();
            }

            require_once "../src/responses/MethodNotAllowedResponse.php";
            return new MethodNotAllowedResponse();
        }

        require_once "../src/responses/PageNotFoundResponse.php";
        return new PageNotFoundResponse();
    }
};

?>