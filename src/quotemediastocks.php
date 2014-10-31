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
        $xml = $this->getSubrtn($array, QuoteMediaConst::GET_QUOTES, QuoteMediaConst::GET_QUOTES_MAX_SYMBOLS, QuoteMediaError::GET_QUOTES_EXCED_MAX_SYMBOL);
        return $this->buildResult($xml, $xml['size'], QuoteMediaConst::GET_QUOTES, $use_assoc);
    }

    /**
     * Perform a getQuotes call to retrieve basic company information. The max size of the $array is QuoteMediaConst::GET_PROFILES_MAX_SYMBOLS
     * @param type $array array of ticker strings
     */
    public function getProfiles(&$array, $use_assoc = false) {
        if (!$this->verifyInput($array, QuoteMediaConst::GET_PROFILES_MAX_SYMBOLS, QuoteMediaError::GET_PROFILES_EXCEED_MAX_SYMBOL)) {
            return false;
        }
        $xml = $this->callAPI(QuoteMediaConst::GET_PROFILES, $array);
        if (!$xml) {
            return false;
        }
        return $this->buildResult($xml, $xml['size'], QuoteMediaConst::GET_PROFILES, $use_assoc);
    }

    /**
     * Perform a getQuotes call to retrieve company fundamental information. The max size of the $array is QuoteMediaConst::GET_FUNDAMENTALS_MAX_SYMBOLS
     * @param type $array array of ticker strings
     */
    public function getFundamentals(&$array, $use_assoc = false) {
        if (!$this->verifyInput($array, QuoteMediaConst::GET_FUNDAMENTALS_MAX_SYMBOLS, QuoteMediaError::GET_FUNDAMENTALS_EXCEED_MAX_SYMBOL)) {
            return false;
        }
        $xml = $this->callAPI(QuoteMediaConst::GET_FUNDAMENTALS, $array);
        if (!$xml) {
            return false;
        }
        return $this->buildResult($xml, $xml->symbolcount, QuoteMediaConst::GET_FUNDAMENTALS, $use_assoc);
    }

    /**
     * Convert an array of tickers into a comma delimited string for use in web API
     * @param array $tickers array of ticker strings
     * @return type
     */
    public function stringifyTickers(&$tickers) {
        return implode(',', $tickers);
    }

    /**
     * Converts XML to an array using buildQuote/buildFundamentals/buildProfiles
     * @param SimpleXMLElement $xml xml to convert to array
     * @param integer $buildFunctionId function id, ex. QuoteMediaaStocks::GET_QUOTES
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     */
    private function buildResult(&$xml, $size, $buildFunctionId, $use_assoc) {
        //may the programming Gods have mercy on my soul
        $ihave = json_encode($xml);
        $nodignity = json_decode($ihave, TRUE);
        $funct = str_replace('get', 'build', QuoteMediaConst::functIdToStr($buildFunctionId)); //ex. buildQuotes
//echo 'nodignity:';var_dump(array_keys($nodignity));var_dump($nodignity);
        if ($size == 1) {//XML for some reason doesn't make array size 1 instead dumping it in $json['company']
            $funct = substr($funct, 0, -1); //trim off the "s", ie buildQuotes->buildQuote to build a single entry
            $res = $this->$funct($nodignity['company']);
            if ($use_assoc) {
                return array($res['symbol'] => $res);
            }
            return array($res);
        }
        return $this->$funct($nodignity['company'], $use_assoc);
    }

    private function buildSubrtn(&$json, $build_function_name, $use_assoc) {
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

    private function buildQuotes(&$json, $use_assoc) {
        return $this->buildSubrtn($json, 'buildQuote', $use_assoc);
    }

    private function buildQuote(&$company) {
        $add = array();
        $add = $company['symbolinfo']['key'];
        $add = array_merge($add, $company['symbolinfo']['equityinfo']);
        return $add;
    }

    private function buildProfiles(&$json, $use_assoc) {
        return $this->buildSubrtn($json, 'buildProfile', $use_assoc);
    }

    private function buildProfile(&$company) {
        $add = array();
        $add = $company['symbolinfo']['key'];
        $add = array_merge($add, $company['symbolinfo']['equityinfo']);
        return $add;
    }

    private function buildFundamentals(&$json, $use_assoc) {
        return $this->buildSubrtn($json, 'buildFundamental', $use_assoc);
    }

    private function buildFundamental(&$company) {
        $add = array();
        $add = $company['symbolinfo']['key'];
        $add = array_merge($add, $company['symbolinfo']['equityinfo']);
        return $add;
    }

}

?>