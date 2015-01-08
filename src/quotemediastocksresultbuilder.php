<?php

class QuoteMediaStocksResultBuilder extends QuoteMediaResultBuilder {

    private $missing; ///<tickers requested that QM never responded to 
    private $malformed; ///<malformed tickers

    public function __construct() {
        $this->missing = array();
        $this->malformed = array();
    }

    public function addMissing($missing) {
        $this->missing[] = $missing;
    }

    public function setMissing($missingarray) {
        $this->missing = $missingarray;
    }

    public function addMalformed($malformed) {
        $this->malformed[] = $malformed();
    }

    public function setMalformed($malformedarray) {
        $this->malformed = $malformedarray;
    }
    public function processXml($build_function_name, $use_assoc){
      if(!$this->getXml()){
            $this->setResult(NULL);
	return;
      }
        $this->setResult($this->flattenResult(QuoteMediaBase::xml2json($this->getXml()), $build_function_name, $use_assoc));
    }
    public function build() {
            return new QuoteMediaStocksResult($this->getResult(), $this->getError(), $this->missing, $this->malformed);
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