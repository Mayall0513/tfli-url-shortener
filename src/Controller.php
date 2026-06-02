<?php

require_once "../src/Response.php";

interface IGETController {
    public function get(): Response;
};

interface IPOSTController {
    public function post(): Response;
}

?>