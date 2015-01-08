<?php

/**
 * Class for all stock related requests.
 */
class QuoteMediaStocks extends QuoteMediaStocksBase {

    private $builder; ///< temp QuoteMediaStocksResultBuilder object to be used to generate result
    private $batcher; ///< instance of QuoteMediaStocksBatcher

    /**
     * Bottom of the call stack for grabbing data from QM. Sets errors for connection issues and XML validity issues.
     * @param QuoteMediaStocks(constant) $type type of call to make
     * @param array $tickers array of ticker strings
     * @param QuoteMediaStocksResultBuilder $builder 
     * @returns true if no error, else false
     */

    public function callStock($type, $tickers, &$builder) {
        $url = QuoteMediaStocksHelper::buildStockURL($type, $tickers, $this->getWebmasterId());
        // echo $url;
        $response = file_get_contents($url);
        if (!$response) {
            //error can't reach the url
            $builder->setError(QuoteMediaError::API_HTTP_REQUEST_ERROR);
            return false;
        }
        $builder->setXml(simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA));
        if (!$builder->getXml()) {
            //error parsing the XML
            $builder->setError(QuoteMediaError::API_XML_PARSE_ERROR);
            return false;
        }
        //if (QuoteMediaStocks::getXmlSymbolCount($type, $builder->getXml()) != count($tickers)) {
        // TODO: determine which ticker didn't get included and report it or retry
        //}
        return true;
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
            $this->batcher = new QuoteMediaBatcher($this);
        }
        return $this->batcher;
    }

    /**
     * Validate that input array is correct and set appropriate error if not.
     * @param array $input list of stock tickers
     * @param int $max maximum number of tickers in array
     * @param int $max_exceed_error error code if the tickers in array exceeds $max
     */
    private function verifyInput(&$input, $max_symbols, $max_exceed_error, &$builder) {
        $builder->setMalformed($this->verifySymbolArray($input, $builder));
        if (count($input) > $max_symbols) {
            $this->builder->setError($max_exceed_error);
        }
    }

    /**
     * getQuote/getProfiles/getFundamentals all use this function to validate input and retrieve XML from API.
     * @param array $symbols list of tickers
     * @param int $function_id function id as specified in QuoteMediaConst
     * @param int $max_symbols maximum number of tickers in array
     * @param int $max_symbols_error error code if the tickers in array exceeds $max_symbols
     * @return SimplXMLElement xml file root
     */
    private function getSubrtn(&$symbols, $function_id, $max_symbols, $max_symbols_error) {
        $builder = new QuoteMediaStocksResultBuilder();
        verifyInput($symbols, $max_symbols, $max_symbols_error, $builder);
        $cleaned = $this->cleanSymbolArray($symbols);
        $this->callStock($function_id, $cleaned, $builder);
        return $builder;
    }

    /**
     * Perform a getQuotes call to retrieve basic stock quote information. The max size of the $array is QuoteMediaConst::GET_QUOTES_MAX_SYMBOLS
     * @param array $array array of ticker strings
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     */
    public function getQuotes($array, $use_assoc = false) {
        $builder = $this->getSubrtn($array, QuoteMediaConst::GET_QUOTES, QuoteMediaConst::GET_QUOTES_MAX_SYMBOLS, QuoteMediaError::GET_QUOTES_EXCEED_MAX_SYMBOLS);
        return $builder->build('flattenQuote', $use_assoc);
    }

    /**
     * Perform a getQuotes call to retrieve basic company information. The max size of the $array is QuoteMediaConst::GET_PROFILES_MAX_SYMBOLS
     * @param type $array array of ticker strings
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     */
    public function getProfiles($array, $use_assoc = false) {
        $builder = $this->getSubrtn($array, QuoteMediaConst::GET_PROFILES, QuoteMediaConst::GET_PROFILES_MAX_SYMBOLS, QuoteMediaError::GET_PROFILES_EXCEED_MAX_SYMBOLS);
        return $builder->build('flattenProfile', $use_assoc);
    }

    /**
     * Perform a getQuotes call to retrieve company fundamental information. The max size of the $array is QuoteMediaConst::GET_FUNDAMENTALS_MAX_SYMBOLS
     * @param type $array array of ticker strings
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     */
    public function getFundamentals($array, $use_assoc = false) {
        $builder = $this->getSubrtn($array, QuoteMediaConst::GET_FUNDAMENTALS, QuoteMediaConst::GET_FUNDAMENTALS_MAX_SYMBOLS, QuoteMediaError::GET_FUNDAMENTALS_EXCEED_MAX_SYMBOLS);
        return $builder->build('flattenFundamental', $use_assoc);
    }

    /**
     * Perform a getKeyRatios call to retrieve company financial ratio information. The max size of the $array is QuoteMediaConst::GET_KEYRATIOS_MAX_SYMBOLS
     * @param type $array array of ticker strings
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     * @return type
     */
    public function getKeyRatios(&$array, $use_assoc = false) {
        $builder = $this->getSubrtn($array, QuoteMediaConst::GET_KEY_RATIOS, QuoteMediaConst::GET_KEY_RATIOS_MAX_SYMBOLS, QuoteMediaError::GET_KEY_RATIOS_EXCEED_MAX_SYMBOLS);
        return $builder->build('flattenKeyRatio', $use_assoc);
    }

}

?>