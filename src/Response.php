<?php

abstract class Response {
    protected int $statusCode;

    public function __construct(int $statusCode) {
        $this->statusCode = $statusCode;
    }

    public function render(): void {
        http_response_code($this->statusCode);
    }
};

?>