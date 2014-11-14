<?php

class QuoteMediaBase {

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
            if (0 == preg_match('/^[a-zA-Z\-]{1,10}$/', trim($v))) {
                $this->error = QuoteMediaError::MALFORMED_SYMBOL;
                $this->error_info = trim($v);
                return false;
            }
        }
        return true;
    }

}

?>