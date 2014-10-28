<?php

/**
 * TODO: Custom keys for result
 */
class QuoteMediaStocks extends QuoteMediaBase {

    public function __construct($webmaster_id) {
        parent::__construct();
        $this->data['webmaster_id'] = $webmaster_id;
    }

    /**
     * Perform a getQuotes call to retrieve basic stock quote information. The max size of the $array is QuoteMediaConst::GET_QUOTES_MAX_SYMBOLS
     * @param array $array array of ticker strings
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     */
    public function getQuotes(&$array, $use_assoc = false) {
        if (!$this->verifyInput($array, QuoteMediaConst::GET_QUOTES_MAX_SYMBOLS, QuoteMediaError::GET_QUOTES_EXCEED_MAX_SYMBOL)) {
            return false;
        }
        $xml = $this->callAPI(QuoteMediaConst::GET_QUOTES, $array);
        if (!$xml) {
            return false;
        }
        return $this->buildResult($xml, QuoteMediaConst::GET_QUOTES, $use_assoc);
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
        return $this->buildResult($xml, QuoteMediaConst::GET_PROFILES, $use_assoc);
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
        return $this->buildResult($xml, QuoteMediaConst::GET_FUNDAMENTALS, $use_assoc);
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
     * @param QuoteMediaStocks(constant) $type type of call to make
     * @param array $tickers array of ticker strings
     * @returns array returns raw xml data from API call
     */
    private function callAPI($type, &$tickers) {
        $url = $this->buildURL($type, $tickers);
//      echo $url;
        $response = file_get_contents($url);
        if (!$response) {
            //error can't reach the url
            $this->errorID = QuoteMediaError::API_HTTP_REQUEST_ERROR;
            return false;
        }
        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (!$xml) {
            //error parsing the XML
            $this->errorID = QuoteMediaError::API_XML_PARSE_ERROR;
            return false;
        }
        $count = 0;
        switch ($type) {//at this point $type is guaranteed to be correct due to $url
            case QuoteMediaConst::GET_QUOTES:
                $count = $xml->quote->count;
                break;
            case QuoteMediaConst::GET_PROFILES:
            case QuoteMediaConst::GET_FUNDAMENTALS:
                $count = $xml->company->count;
                break;
        }
        if ($count != count($tickers)) {
            // TODO: determine which ticker didn't get included and report it or retry
        }
        return $xml;
    }

    private function verifyInput(&$input, $max, $max_exceed_error) {
        if (!is_array($input)) {
            $this->error = QuoteMediaError::INPUT_IS_NOT_ARRAY;
            return false;
        }
        if (count($input) > $max) {
            $this->error = $max_exceed_error;
            return false;
        }
        return true;
    }

    /**
     * Converts XML to an array using buildQuote/buildFundamentals/buildProfiles
     * @param SimpleXMLElement $xml xml to convert to array
     * @param integer $buildFunctionId function id, ex. QuoteMediaaStocks::GET_QUOTES
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     */
    private function buildResult(&$xml, $buildFunctionId, $use_assoc) {
        //may the programming Gods have mercy on my soul
        $ihave = json_encode($xml);
        $nodignity = json_decode($ihave, TRUE);
        $funct = str_replace('get', 'build', QuoteMediaConst::functIdToStr($buildFunctionId));
        return $this->$funct($nodignity, $use_assoc);
    }

    private function buildQuotes(&$json, $use_assoc) {
        return null;
    }

    private function buildProfiles(&$json, $use_assoc) {
        if ($json['@attributes']['size'] == 1) {//XML for some reason doesn't make array size 1 instead dumping it in $json['company']
            return array($this->buildProfile($json['company']));
        }
        $result = array();
        foreach ($json['company'] as &$company) {
            $result[] = $this->buildProfile($company);
        }
        return $result;
    }

    private function buildProfile(&$company) {
        $add = array();
        $add = $company['symbolinfo']['key'];
        $add = array_merge($add, $company['symbolinfo']['equityinfo']);
        return $add;
    }

    private function buildFundamentals(&$json, $use_assoc) {
        
    }

    /**
     * @param array $s_tickers a one dimensional array of tickers
     * @returns string url to use
     */
    private function buildURL($type, &$tickers) {
        $url_middle = ''; //will be used to store profile, fundamentals, or quote
        switch ($type) {
            case QuoteMediaConst::GET_QUOTES:
                $url_middle = 'getQuotes.xml';
                break;
            case QuoteMediaConst::GET_PROFILES:
                $url_middle = 'getProfiles.xml';
                break;
            case QuoteMediaConst::GET_FUNDAMENTALS:
                $url_middle = 'getFundamentals.xml';
                break;
            default:
//TODO: error, invalid type (programmer error)
        }

        return QuoteMediaConst::URL_ROOT . $url_middle .
                '?webmasterId=' . $this->data['webmaster_id'] .
                '&symbols=' . $this->stringifyTickers($tickers);
    }

}

?>