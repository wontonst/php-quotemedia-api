<?php

class QuoteMediaStocksResultBuilder extends QuoteMediaResultBuilder {

    private $missing;

    public function __construct() {
        $this->missing = array();
    }

    public function addMissing($missing) {
        $this->missing[] = $missing;
    }

    public function buildResult() {
        return new QuoteMediaStocksResult($this->result, empty($this->missing) ? NULL : $this->missing);
    }

}

?>