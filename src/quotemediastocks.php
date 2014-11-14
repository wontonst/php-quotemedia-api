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
        if (!$this->verifySymbolArray($input)) {
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
     * @param array $symbols list of tickers
     * @param int $function_id function id as specified in QuoteMediaConst
     * @param int $max_symbols maximum number of tickers in array
     * @param int $max_symbols_error error code if the tickers in array exceeds $max_symbols
     * @return SimplXMLElement xml file root
     */
    private function getSubrtn(&$symbols, $function_id, $max_symbols, $max_symbols_error) {
        if (!$this->verifyInput($symbols, $max_symbols, $max_symbols_error)) {
            return false;
        }
        $cleaned = $this->cleanSymbolArray($symbols);
        $xml = $this->api->callStock($function_id, $cleaned);
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
        if (!$xml) {
            return false;
        }
        $json = QuoteMediaApi::xml2json($xml);
        return $this->flattenResults($json['quote'], 'flattenQuote', $use_assoc);
    }

    /**
     * Perform a getQuotes call to retrieve basic company information. The max size of the $array is QuoteMediaConst::GET_PROFILES_MAX_SYMBOLS
     * @param type $array array of ticker strings
     */
    public function getProfiles($array, $use_assoc = false) {
        $xml = $this->getSubrtn($array, QuoteMediaConst::GET_PROFILES, QuoteMediaConst::GET_PROFILES_MAX_SYMBOLS, QuoteMediaError::GET_PROFILES_EXCEED_MAX_SYMBOLS);
        if (!$xml) {
            return false;
        }
        $json = QuoteMediaApi::xml2json($xml);
        return $this->flattenResults($json['company'], 'flattenProfile', $use_assoc);
    }

    /**
     * Perform a getQuotes call to retrieve company fundamental information. The max size of the $array is QuoteMediaConst::GET_FUNDAMENTALS_MAX_SYMBOLS
     * @param type $array array of ticker strings
     */
    public function getFundamentals($array, $use_assoc = false) {
        $xml = $this->getSubrtn($array, QuoteMediaConst::GET_FUNDAMENTALS, QuoteMediaConst::GET_FUNDAMENTALS_MAX_SYMBOLS, QuoteMediaError::GET_FUNDAMENTALS_EXCEED_MAX_SYMBOLS);
        if (!$xml) {
            return false;
        }
        $json = QuoteMediaApi::xml2json($xml);
        return $this->flattenResults($json['company'], 'flattenFundamental', $use_assoc);
    }

    public function getKeyRatios(&$array, $use_assoc = false) {
        $xml = $this->getSubrtn($array, QuoteMediaConst::GET_KEY_RATIOS, QuoteMediaConst::GET_KEY_RATIOS_MAX_SYMBOLS, QuoteMediaError::GET_KEY_RATIOS_EXCEED_MAX_SYMBOLS);
        if (!$xml) {
            return false;
        }
        $json = QuoteMediaApi::xml2json($xml);
        return $this->flattenResults($json['company'], 'flattenKeyRatios', $use_assoc);
    }

    /**
     * Take a list of companies and flatten them.
     * @param array $json deserialized array built from XML->json
     * @param string $build_function_name
     * @param bool $use_assoc
     * @return array flattened array
     */
    private function flattenResults(&$json, $build_function_name, $use_assoc) {
        if (array_key_exists('symbolinfo', $json) || array_key_exists('key', $json)) {//there is only one company
            $result = $this->$build_function_name($json);
            return array($use_assoc ? $result['symbol'] : 0 => $result);
        }
        $result = array();
        foreach ($json as &$company) {
            $line = $this->$build_function_name($company);
            if (!$use_assoc) {//simple array
                $result[] = $line;
                continue;
            }//else
            $result[$line['symbol']] = $line;
        }
        return $result;
    }

    /**
     * Flatten the resulting array from a getQuotes call for a single company.
     * @param array $company all relevant data for a single company taken straight from raw response->json->array
     * @return array flattened array
     */
    private function flattenQuote(&$company) {
        //var_dump($company);
        $add = $company['key'];
        $add = array_merge($add, $company['equityinfo'], $company['pricedata']);
        if (isset($company['fundamental']['dividend'])) {
            $rekey = array(
                'date' => 'dividenddate',
                'amount' => 'dividendamount',
                'yield' => 'dividendyield',
                'latestamount' => 'dividendlastamount',
                'frequency' => 'dividendfrequency',
                'paydate' => 'dividendpaydate');
            foreach ($rekey as $k => $v) {
                $dividend[$v] = $company['fundamental']['dividend'][$k];
            }
            unset($company['fundamental']['dividend']);
            $add = array_merge($add, $dividend);
        }
        $add = array_merge($add, $company['fundamental']);
        //var_dump($add);
        return $add;
    }

    /**
     * Flatten the resulting array from a getProfiles call for a single company.
     * @param array $company all relevant data for a single company taken straight from raw response->json->array
     * @return array flattened array
     */
    private function flattenProfile(&$company) {
        //var_dump($company);
        $add = $company['symbolinfo']['key'];
        $add = array_merge($add, $company['symbolinfo']['equityinfo'], $company['profile']['info']['address']);
        unset($company['profile']['info']['address']);
        $add = array_merge($add, $company['profile']['info']);
        unset($company['profile']['info']);
        $add = array_merge($add, $company['profile']['details'], $company['profile']['classification']);
        unset($company['profile']['details']);
        unset($company['profile']['classification']);
        $add = array_merge($add, $company['profile']);
        return $add;
    }

    /**
     * Flatten the resulting array from a getFundamentals call for a single company.
     * @param array $company all relevant data for a single company taken straight from raw response->json->array
     * @return array flattened array
     */
    private function flattenFundamental(&$company) {
        $add = $company['symbolinfo']['key'];
        $add = array_merge($add, $company['symbolinfo']['equityinfo'], $company['statistical'], $company['fundamental']['shortinterest']);
        if (isset($company['fundamental']['dividend'])) {
            $rekey = array(
                'date' => 'dividenddate',
                'amount' => 'dividendamount',
                'yield' => 'dividendyield',
            );
            foreach ($rekey as $k => $v) {
                $dividend[$v] = $company['fundamental']['dividend'][$k];
            }
            unset($company['fundamental']['divident']);
            $add = array_merge($add, $dividend);
        }
        unset($company['fundamental']['shortinterest']);
        $add = array_merge($add, $company['fundamental']);
        return $add;
    }

    private function flattenKeyRatios(&$company) {
        $add = $company['symbolinfo']['key'];
        $add = array_merge($add, $company['symbolinfo']['equityinfo'], $company['keyratios']['incomestatements'], $company['keyratios']['financialstrength'], $company['keyratios']['managementeffectiveness'], $company['keyratios']['valuationmeasures'], $company['keyratios']['dividendssplits'], $company['keyratios']['profitability'], $company['keyratios']['assets']);
        return $add;
    }

}

?>