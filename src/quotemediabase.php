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

    /**
     * Check that the input array of symbols is well formed.
     * @param array $input array of tickers, presumably
     * @return boolean whether or not the array is well formed
     */
    protected function verifySymbolArray(&$input) {
        if (!is_array($input)) {
            $this->error = QuoteMediaError::INPUT_IS_NOT_ARRAY;
            return false;
        }
        foreach ($input as $v) {
            if (!is_string($v)) {
                $this->error = QuoteMediaError::SYMBOL_IS_NOT_STRING;
                return false;
            }
            if (0 == preg_match('/^[a-zA-Z\-.]{1,10}(:[a-zA-Z\-.]{1,10})?$/', trim($v))) {
                $this->error = QuoteMediaError::MALFORMED_SYMBOL;
                $this->error_info = trim($v);
                return false;
            }
        }
        return true;
    }

    /**
     * QuoteMedia doesn't like tickers like"BRK-A" or CRD.B, instead it wants BRKA and CRDB. This function performs the transformation.
     * @param array $input array of tickers
     * @return array array of cleaned tickers
     */
    protected function cleanSymbolArray(&$input) {
        $res = array();
        foreach ($input as $symbol) {
            $res[] = str_replace('-', '', str_replace('.', '', $symbol));
        }
        return $res;
    }

}

?>