<?php

class QuoteMediaStocksResultBuilder extends QuoteMediaResultBuilder {

    private $missing; ///<tickers requested that QM never responded to 
    private $malformed; ///<malformed tickers

    public function __construct() {
        parent::__construct();
        $this->missing = array();
        $this->malformed = array();
    }

    public function addMissing($missing) {
        $this->missing[] = $missing;
    }

    public function setMissing($missingarray) {
        $this->missing = $missingarray;
    }

    public function getMissing() {
        return $this->missing;
    }

    public function addMalformed($malformed) {
        $this->malformed[] = $malformed();
    }

    public function setMalformed($malformedarray) {
        $this->malformed = $malformedarray;
    }

    public function getMalformed() {
        return $this->malformed;
    }

    /**
     * Convert the raw XML into user specified result
     * @param type $function_id id of the function, ie QuoteMediaConst::GET_QUOTES
     * @param type $json_entry generally speaking, its quotes for getQuotes and company for all else
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     */
    public function processXml($function_id, $json_entry, $use_assoc) {
        if (!$this->getXml()) {
            $this->setResult(NULL);
            return;
        }
        $json = QuoteMediaBase::xml2json($this->getXml());
//        print_r($json);
        $this->setResult($this->flattenResults($json[$json_entry], $function_id, $use_assoc));
        $this->setMissing($use_assoc ? $this->calcMissingAssoc() : $this->calcMissingArray());
        if (!empty($this->getMissing())) {
            $this->setError(QuoteMediaError::SYMBOL_DOES_NOT_EXIST);
            print_r($this);
        }
    }

    /**
     * Find the symbols that the API did not return. This version is for $use_assoc=true.
     * @return array missing symbols
     */
    private function calcMissingAssoc() {
        $missing = array();
        $result_symbols = array_keys($this->getResult());
//        var_dump($result_symbols);
        foreach ($this->getInput() as $input) {
            if (!in_array($input, $result_symbols)) {
                $missing[] = $input;
            }
        }
        return $missing;
    }

    /**
     * Find the symbols that the API did not return. This version is for $use_assoc=false.
     * @return array missing symbols
     */
    private function calcMissingArray() {
        $missing = array();
        $results = $this->getResult();
        foreach ($this->getInput() as $input) {
            $found = false;
            foreach ($results as $i) {
                if ($i['symbol'] == $input) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $missing[] = $input;
            }
        }
        return $missing;
    }

    public function build() {
        return new QuoteMediaStocksResult($this);
    }

    /**
     * Take a list of companies and flatten them.
     * @param array $json deserialized array built from XML->json
     * @param string $function_id function ID
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     * @return array flattened array
     */
    private function flattenResults($json, $function_id, $use_assoc) {
//        print_r($json);
        switch ($function_id) {
            case QuoteMediaConst::GET_QUOTES:
                $build_function_name = 'flattenQuote';
                break;
            case QuoteMediaConst::GET_PROFILES:
                $build_function_name = 'flattenProfile';
                break;
            case QuoteMediaConst::GET_FUNDAMENTALS:
                $build_function_name = 'flattenFundamental';
                break;
            case QuoteMediaConst::GET_KEY_RATIOS:
                $build_function_name = 'flattenKeyRatio';
                break;
        }
        if (array_key_exists('symbolinfo', $json) || array_key_exists('key', $json)) {//there is only one company
            $result = $this->$build_function_name($json);
            return array($use_assoc ? $result['symbol'] : 0 => $result);
        }
        $result = array();
        foreach ($json as &$company) {
            if (isset($company['@attributes']['datatype']) && $company['@attributes']['datatype'] == 'n/a') {
                continue;
            }
            $line = $this->$build_function_name($company);
            if (!$use_assoc) {//simple array
                $result[] = $line;
                continue;
            }//else
            $result[$line['symbol']] = $line;
        }
        //print_r($json);
        //print_r($result);
        return $result;
    }

    /**
     * Flatten the resulting array from a getQuotes call for a single company.
     * @param array $company all relevant data for a single company taken straight from raw response->json->array
     * @return array flattened array
     */
    private function flattenQuote(&$company) {
        if (!isset($company['equityinfo'])) {
            return;
        }
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
            unset($company['fundamental']['dividend']);
            $add = array_merge($add, $dividend);
        }
        unset($company['fundamental']['shortinterest']);
        $add = array_merge($add, $company['fundamental']);
        return $add;
    }

    private function flattenKeyRatio(&$company) {
        $add = $company['symbolinfo']['key'];
        $add = array_merge($add, $company['symbolinfo']['equityinfo'], $company['keyratios']['incomestatements'], $company['keyratios']['financialstrength'], $company['keyratios']['managementeffectiveness'], $company['keyratios']['valuationmeasures'], $company['keyratios']['dividendssplits'], $company['keyratios']['profitability'], $company['keyratios']['assets']);
        return $add;
    }

}

?>