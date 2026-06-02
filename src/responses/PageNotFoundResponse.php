<?php

require_once "../src/Response.php";

class PageNotFoundResponse extends Response {
    public function __construct() {
        parent::__construct(404);
    }

    public function render(): void {
        parent::render();
        echo "Page not found";
    }
}

?>