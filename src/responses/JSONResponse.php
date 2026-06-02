<?php

require_once "../src/Response.php";

class JSONResponse extends Response {
    protected array $data;

    public function __construct(int $statusCode, array $data) {
        parent::__construct($statusCode);
        $this->data = $data; 
    }

    public function render(): void {
        parent::render();

        header('Content-Type: application/json');
        echo json_encode($this->data);
    }
}

?>