<?php

require_once "../src/Response.php";

class MethodNotAllowedResponse extends Response {
    public function __construct() {
        parent::__construct(405);
    }

    public function render(): void {
        parent::render();
        echo "Method not allowed";
    }
}

?>