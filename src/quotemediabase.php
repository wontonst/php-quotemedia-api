<?php

class QuoteMediaBase {

    protected $error;
    protected $error_info;

    public function __construct() {
        $this->error = QuoteMediaError::GOOD;
        $this->error_info = array();
    }

    public function getErrorID() {
        return $this->error;
    }

    public function getError() {
        return QuoteMediaError::IDtoError($this->error);
    }

    public function getErrorInfo() {
        return $this->error_info;
    }

}

?>