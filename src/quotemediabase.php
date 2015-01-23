<?php

class QuoteMediaBase {

    /**
     * Convert an array of tickers into a comma delimited string for use in web API
     * @param array $tickers array of ticker strings
     * @return string comma delimited ticker string
     */
    public static function csvify(&$tickers) {
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
     * @return array of stocks that match stock regex pattern
     */
    protected function removeMalformed($input, &$builder) {
        $malformed = array();
        $clean = array();
        foreach ($input as $v) {
            if (!is_string($v)) {
                $builder->setError(QuoteMediaError::SYMBOL_IS_NOT_STRING);
                //we'll be putting "array" instead of actual array
                //we'll be putting "object" instead of actual object
                $malformed[] = is_array($v) ? 'array' : (is_object($v) ? 'object' : $v); 
                continue;
            }
            $trimmed = trim($v);
            if (0 == preg_match('/^[a-zA-Z\-.]{1,10}(:[a-zA-Z\-.]{1,10})?$/', $trimmed)) {
                $builder->setError(QuoteMediaError::MALFORMED_SYMBOL);
                $malformed[] = $trimmed;
                continue;
            }
            $clean[] = $trimmed;
        }
        $builder->setMalformed($malformed);
        return $clean;
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