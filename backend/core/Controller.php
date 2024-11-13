<?php

class Controller {
    protected function jsonResponse($data, $statusCode = 200) {
        header('Content-type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}
