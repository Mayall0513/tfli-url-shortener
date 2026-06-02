<?php

require_once "../src/Controller.php";
require_once "../src/responses/ViewResponse.php";

class IndexController implements IGETController {
    public function get(): Response {
        return new ViewResponse(200, "../src/views/IndexView.php");
    }
}

?>