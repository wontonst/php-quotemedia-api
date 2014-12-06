<?php

class QuoteMediaStocksResultBuilder {

    private $result;
    private $missing;

    public function __construct() {
        $this->result = array();
        $this->missing = array();
    }

    public function addMissing($missing) {
        $this->missing[] = $missing;
    }

    public function setResult($result) {
        $this->result = $result;
    }

    public function buildResult() {
        return new QuoteMediaResult($this->result, empty($this->missing) ? NULL : $this->missing);
    }

}

?>