<?php

/**
 * Class that stores output of a call to the library. Contains error information and call result. This class should not be instantiated by the user.
 */
abstract class QuoteMediaResultBuilder {

    protected $error;
    protected $result;

    public function __construct() {
        $this->error = QuoteMediaError::GOOD;
    }

    public function setError($error) {
        $this->error = $error;
    }

    public function setResult($result) {
        $this->result = $result;
    }

    public abstract function buildResult();
}

?>