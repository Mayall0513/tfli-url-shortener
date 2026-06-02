<?php

require_once "../src/Response.php";

class RedirectResponse extends Response {
    protected string $redirectUrl;

    public function __construct(string $redirectUrl) {
        parent::__construct(302);
        $this->redirectUrl = $redirectUrl; 
    }

    public function render(): void {
        parent::render();
        header("Location: " . $this->redirectUrl);
    }
}

?>