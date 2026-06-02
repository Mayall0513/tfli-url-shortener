<?php

require_once "../src/Response.php";

class ViewResponse extends Response {
    protected string $templatePath;
    protected ?array $templateData;

    public function __construct(int $statusCode, string $templatePath, ?array $templateData = null) {
        parent::__construct($statusCode);

        $this->templatePath = $templatePath; 
        $this->templateData = $templateData;
    }

    public function render(): void {
        parent::render();

        if (null !== $this->templateData) {
            extract($this->templateData);
        }
        
        require $this->templatePath;
    }
}

?>