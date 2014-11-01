<?php

/**
 * TODO: Custom keys for result
 */
class QuoteMediaStocks extends QuoteMediaBase {

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
        parent::__construct();
        $this->api = new QuoteMediaApi($webmaster_id);
    }

    /**
     * Validate that input array is correct and set appropriate error if not.
     * @param array $input list of stock tickers
     * @param int $max maximum number of tickers in array
     * @param int $max_exceed_error error code if the tickers in array exceeds $max
     * @return boolean
     */
    private function verifyInput(&$input, $max_symbols, $max_exceed_error) {
        if (!is_array($input)) {
            $this->error = QuoteMediaError::INPUT_IS_NOT_ARRAY;
            return false;
        }
        if (count($input) > $max_symbols) {
            $this->error = $max_exceed_error;
            return false;
        }
        return true;
    }

    /**
     * getQuote/getProfiles/getFundamentals all use this function to validate input and retrieve XML from API.
     * @param array $array list of tickers
     * @param int $function_id function id as specified in QuoteMediaConst
     * @param int $max_symbols maximum number of tickers in array
     * @param int $max_symbols_error error code if the tickers in array exceeds $max_symbols
     * @return SimplXMLElement xml file root
     */
    private function getSubrtn(&$array, $function_id, $max_symbols, $max_symbols_error) {
        if (!$this->verifyInput($array, $max_symbols, $max_symbols_error)) {
            return false;
        }
        $xml = $this->api->call($function_id, $array);
        if (!$xml) {
            return false;
        }
        return $xml;
    }

    /**
     * Perform a getQuotes call to retrieve basic stock quote information. The max size of the $array is QuoteMediaConst::GET_QUOTES_MAX_SYMBOLS
     * @param array $array array of ticker strings
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     */
    public function getQuotes($array, $use_assoc = false) {
        $xml = $this->getSubrtn($array, QuoteMediaConst::GET_QUOTES, QuoteMediaConst::GET_QUOTES_MAX_SYMBOLS, QuoteMediaError::GET_QUOTES_EXCEED_MAX_SYMBOLS);
        return $this->buildResult($xml, $xml['size'], QuoteMediaConst::GET_QUOTES, $use_assoc);
    }

    /**
     * Perform a getQuotes call to retrieve basic company information. The max size of the $array is QuoteMediaConst::GET_PROFILES_MAX_SYMBOLS
     * @param type $array array of ticker strings
     */
    public function getProfiles(&$array, $use_assoc = false) {
        $xml = $this->getSubrtn($array, QuoteMediaConst::GET_PROFILES, QuoteMediaConst::GET_PROFILES_MAX_SYMBOLS, QuoteMediaError::GET_PROFILES_EXCEED_MAX_SYMBOLS);
        $json = QuoteMediaApi::xml2json($xml);
        if ($xml['size'] == 1) {
            return $this->flattenProfile($json);
        }
        return $this->flattenResults($json, 'flattenProfile', $use_assoc);
    }

    /**
     * Perform a getQuotes call to retrieve company fundamental information. The max size of the $array is QuoteMediaConst::GET_FUNDAMENTALS_MAX_SYMBOLS
     * @param type $array array of ticker strings
     */
    public function getFundamentals(&$array, $use_assoc = false) {
        $xml = $this->getSubrtn($array, QuoteMediaConst::GET_FUNDAMENTALS, QuoteMediaConst::GET_FUNDAMENTALS_MAX_SYMBOLS, QuoteMediaErrors::GET_FUNDAMENTALS_EXEED_MAX_SYMBOLS);
        return $this->buildResult($xml, $xml->symbolcount, QuoteMediaConst::GET_FUNDAMENTALS, $use_assoc);
    }

    /**
     * Take a list of companies and flatten them.
     * @param type $json
     * @param type $build_function_name
     * @param type $use_assoc
     * @return type
     */
    private function flattenResults(&$json, $build_function_name, $use_assoc) {
        $result = array();
        foreach ($json['company'] as &$company) {
            $line = $this->$build_function_name($company);
            if (!$use_assoc) {//simple array
                $result[] = $line;
                continue;
            }//else
            $result[$line['symbol']] = $line;
        }
        return $result;
    }

    private function flattenQuote(&$company) {
        $add = $company['symbolinfo']['key'];
        $add = array_merge($add, $company['symbolinfo']['equityinfo']);
        return $add;
    }

    private function flattenProfile(&$company) {
        $add = $company['symbolinfo']['key'];
        $add = array_merge($add, $company['symbolinfo']['equityinfo']);
        return $add;
    }

    private function flattenFundamental(&$company) {
        $add = $company['symbolinfo']['key'];
        $add = array_merge($add, $company['symbolinfo']['equityinfo']);
        return $add;
    }

}

?>