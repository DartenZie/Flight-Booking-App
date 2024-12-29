<?php

class ValidationException extends Exception {
    public function __construct(string $message, int $code = 400, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
