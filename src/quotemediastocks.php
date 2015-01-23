<?php

/**
 * Class for all stock related requests.
 */
class QuoteMediaStocks extends QuoteMediaBase {

    private $builder; ///< temp QuoteMediaStocksResultBuilder object to be used to generate result
    private $batcher; ///< instance of QuoteMediaStocksBatcher

    /**
     * Bottom of the call stack for grabbing data from QM. Sets errors for connection issues and XML validity issues.
     * @param QuoteMediaStocks(constant) $type type of call to make
     * @param array $tickers array of ticker strings
     * @param QuoteMediaStocksResultBuilder $builder 
     * @returns true if no error, else false
     */

    private function callStock($type, $tickers, &$builder) {
        $url = QuoteMediaStocksHelper::buildStockURL($type, $tickers, $this->getWebmasterId());
        // echo $url;
        return $this->callApi($url, $builder);
    }

    /**
     * Get the number of symbols described in XML.
     * @param int $function_id function id as specified in QuoteMediaConst
     * @param SimpleXMLElement $xml file returned from API
     * @returns int number of symbols in the XML
     */
    static public function getXmlSymbolCount($function_id, &$xml) {
        switch ($function_id) {//at this point $type is guaranteed to be correct due to $url
            case QuoteMediaConst::GET_QUOTES:
                $count = $xml['size'];
                break;
            case QuoteMediaConst::GET_PROFILES:
            case QuoteMediaConst::GET_FUNDAMENTALS:
                $count = $xml->symbolcount;
                break;
        }
    }

    public function __construct($webmaster_id) {
        parent::__construct($webmaster_id);
        $this->batcher = NULL;
    }

    public function getBatcher() {
        if ($this->batcher == NULL) {
            $this->batcher = new QuoteMediaStocksBatcher($this);
        }
        return $this->batcher;
    }

    /**
     * getQuote/getProfiles/getFundamentals all use this function to validate input and retrieve XML from API.
     * @param array $symbols list of tickers
     * @param int $function_id function id as specified in QuoteMediaConst
     * @param int $max_symbols maximum number of tickers in array
     * @param int $max_symbols_error error code if the tickers in array exceeds $max_symbols
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     * @return SimplXMLElement xml file root
     */
    private function getSubrtn(&$symbols, $function_id, $max_symbols, $max_symbols_error, $json_entry, $use_assoc) {
        $builder = new QuoteMediaStocksResultBuilder();
        $builder->setRawInput($symbols);
        if (!$builder->verifyRawInputIsArray()) {
            return $builder; //cannot proceed if input is not array
        }
        $verified = $this->removeMalformed($symbols, $builder); //malformed symbols are not in $verified
        $cleaned = $this->cleanSymbolArray($verified);
        $builder->setInput($cleaned);
        if (count($cleaned) > $max_symbols) {
            $builder->setError($max_symbols_error);
            return $builder; //cannot proceed if input size exceeds $max_symbols
        }
        $this->callStock($function_id, $cleaned, $builder);
        $builder->processXml($function_id, $json_entry, $use_assoc);
        return $builder;
    }

    /**
     * Perform a getQuotes call to retrieve basic stock quote information. The max size of the $array is QuoteMediaConst::GET_QUOTES_MAX_SYMBOLS
     * @param array $array array of ticker strings
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     * @return QuoteMediaStocksResult
     */
    public function getQuotes($array, $use_assoc = false) {
        $builder = $this->getSubrtn($array, QuoteMediaConst::GET_QUOTES, QuoteMediaConst::GET_QUOTES_MAX_SYMBOLS, QuoteMediaError::GET_QUOTES_EXCEED_MAX_SYMBOLS, 'quote', $use_assoc);
        return $builder->build();
    }

    /**
     * Perform a getQuotes call to retrieve basic company information. The max size of the $array is QuoteMediaConst::GET_PROFILES_MAX_SYMBOLS
     * @param type $array array of ticker strings
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     * @return QuoteMediaStocksResult
     */
    public function getProfiles($array, $use_assoc = false) {
        $builder = $this->getSubrtn($array, QuoteMediaConst::GET_PROFILES, QuoteMediaConst::GET_PROFILES_MAX_SYMBOLS, QuoteMediaError::GET_PROFILES_EXCEED_MAX_SYMBOLS, 'company', $use_assoc);
        return $builder->build();
    }

    /**
     * Perform a getQuotes call to retrieve company fundamental information. The max size of the $array is QuoteMediaConst::GET_FUNDAMENTALS_MAX_SYMBOLS
     * @param type $array array of ticker strings
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     * @return QuoteMediaStocksResult
     */
    public function getFundamentals($array, $use_assoc = false) {
        $builder = $this->getSubrtn($array, QuoteMediaConst::GET_FUNDAMENTALS, QuoteMediaConst::GET_FUNDAMENTALS_MAX_SYMBOLS, QuoteMediaError::GET_FUNDAMENTALS_EXCEED_MAX_SYMBOLS, 'company', $use_assoc);
        return $builder->build();
    }

    /**
     * Perform a getKeyRatios call to retrieve company financial ratio information. The max size of the $array is QuoteMediaConst::GET_KEYRATIOS_MAX_SYMBOLS
     * @param type $array array of ticker strings
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     * @return QuoteMediaStocksResult
     */
    public function getKeyRatios(&$array, $use_assoc = false) {
        $builder = $this->getSubrtn($array, QuoteMediaConst::GET_KEY_RATIOS, QuoteMediaConst::GET_KEY_RATIOS_MAX_SYMBOLS, QuoteMediaError::GET_KEY_RATIOS_EXCEED_MAX_SYMBOLS, 'company', $use_assoc);
        return $builder->build();
    }

}

?>