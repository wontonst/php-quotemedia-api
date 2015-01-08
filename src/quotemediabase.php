<?php

class QuoteMediaBase {

    /**
     * Convert an array of tickers into a comma delimited string for use in web API
     * @param array $tickers array of ticker strings
     * @return string comma delimited ticker string
     */
    public static function stringifyTickers(&$tickers) {
        return implode(',', $tickers);
    }

    public static function xml2json(&$xml) {
        //may the programming Gods have mercy on my soul
        $ihavenodignity = json_encode($xml);
        return json_decode($ihavenodignity, TRUE);
    }

    public function __construct($webmaster_id) {
        $this->error = QuoteMediaError::GOOD;
        $this->error_info = array();
        $this->webmaster_id = $webmaster_id;
    }

    public function getWebmasterId() {
        return $this->webmaster_id;
    }

    /**
     * Check that the input array of symbols is well formed.
     * @param array $input array of tickers, presumably
     * @param QuoteMediaResultBuilder $builder
     * @return array of malformed symbols
     */
    protected function verifySymbolArray(&$input, &$builder) {
        $error = array();
        if (!is_array($input)) {
            $builder->setError(QuoteMediaError::INPUT_IS_NOT_ARRAY);
        }
        foreach ($input as $v) {
            if (!is_string($v)) {
                $builder->setError(QuoteMediaError::SYMBOL_IS_NOT_STRING);
                $error[] = $v;
            }
            if (0 == preg_match('/^[a-zA-Z\-.]{1,10}(:[a-zA-Z\-.]{1,10})?$/', trim($v))) {
                $builder->setError(QuoteMediaError::MALFORMED_SYMBOL);
                $error[] = trim($v);
            }
        }
        return $error;
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