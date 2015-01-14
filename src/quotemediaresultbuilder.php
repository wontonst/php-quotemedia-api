<?php

/**
 * Class that stores output of a call to the library. Contains error information and call result. This class should not be instantiated by the user.
 */
abstract class QuoteMediaResultBuilder {

    protected $error;
    protected $errorhistory;
    protected $result;
    protected $raw_input;
    protected $input;

    public function __construct() {
        $this->error = QuoteMediaError::GOOD;
        $this->errorhistory = array();
    }

    public function hasError() {
        return $this->error != QuoteMediaError::GOOD;
    }

    public function setError($error) {
        if ($this->error != $error) {
            $this->errorhistory[] = $this->error;
            $this->error = $error;
        }
    }

    public function getError() {
        return $this->error;
    }

    public function setRawInput($input) {
        $this->raw_input = $input;
    }

    public function getRawInput() {
        return $this->raw_input;
    }

    public function setInput($input) {
        $this->input = $input;
    }

    public function getInput() {
        return $this->input;
    }

    public function setErrorIDHistory($errorhistory) {
        $this->errorhistory = $errorhistory;
    }

    public function getErrorIDHistory() {
        return $this->errorhistory;
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

    /**
     * Check that input array is of type array, if not set the appropriate error.
     * @return true if is array, else false.
     */
    public function verifyRawInputIsArray() {
        if (!is_array($this->getRawInput())) {
            $this->setError(QuoteMediaError::INPUT_IS_NOT_ARRAY);
            return false;
        }
        return true;
    }

    public abstract function build();
}

?>