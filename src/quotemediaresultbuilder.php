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

    public function hasError() {
        return $this->error != QuoteMediaError::GOOD;
    }

    public function setError($error) {
        $this->error = $error;
    }
    public function getError(){
      return $this->error;
    }
    public function setResult($result) {
        $this->result = $result;
    }

    public function getResult() {
        return $this->result;
    }

    public function setXml($xml) {
        $this->xml = $xml;
    }

    public function getXml() {
        return $this->xml;
    }
    public abstract function build();
}

?>