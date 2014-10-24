<?php

/**
  Retrieves all stock data at once for an arbitrarily long input. Built on top of QuoteMediaStocks thru composition.
 * Todo change merge from o(N^2) to o(n)
 * Enforce getSubrtn $cmd parameter
 */
class QuoteMediaBatcher extends QuoteMediaBase {

    private $api; ///< instance of QuoteMediaStocks
    private $batching; ///< whether or not the batching is complete

    public function __construct($in) {
        parent::__construct();
        if (is_int($in) || ctype_digit($in)) {
            $this->api = new QuoteMediaStocks($in);
        } else {
            $type = get_class($in);
            if ($type == 'QuoteMediaStocks') {
                //TODO: if we upgrade to PHP 5.5 this 
                //should be replaced with QuoteMediaStocks::class so
                //code doesn't break in event of class name change
                $this->api = $in;
            } else {
                throw new Exception('Invalid QuoteMediaBatcher constructor parameter');
            }
        }
    }

    /**
     * Calls ((QuoteMediaStocks)this->api)->$cmd() to call and grab data from QuoteMedia.
     * @param type $array arbitrarily long array of stock tickers
     * @param type $max_per_call maximum number of tickers to pass to the api on each call.
     * @param type $cmd commands to run are "getQuotes", "getProfiles", "getFundamentals"
     * @return array list of data for reach stock ticker passed
     */
    private function getSubrtn($array, $max_per_call, $cmd) {
        $ret = array();
        $iter = ceil(count($array) / $max_per_call);
        for ($i = 0; $i != $iter; $i++) {
            $slice = array_slice($array, $i * $max_per_call, $max_per_call);
            $res = $this->api->$cmd($slice);
            if ($res === false) {
                $this->errorID = $this->api->getErrorID();
                return false;
            }
            foreach($res as &$v){
                $ret[$v['CompanyTicker']] = $v;
            }
        }
        return $ret;
    }
/**
 * Merges the results of getProfile getFundamentals getQuotes into a single array.
 * @param array $results array size 3 keyed 'quotes','fundamentals','profiles' to be merged into a single array.
 * @return array merged array
 */
    private function mergeResults($results) {
        $ret = array();

        foreach ($results['quotes'] as $k=>&$q) {
            $line = $q;
            if(isset($results['fundamentals'][$k])){
                $line = array_merge($line, $results['fundamentals'][$k]);
            }
            if(isset($results['profiles'][$k])){
                $line = array_merge($line, $results['profiles'][$k]);
            }
            $ret[] = $line;
        }
        return $ret;
    }

    /**
      Retrieves all stock data for an arbitrarily long input.
     */
    public function getAll($arr) {
        if (!is_array($arr)) {//makek sure input is valid
            $this->error = QuoteMediaError::INPUT_IS_NOT_ARRAY;
            return false;
        }

        $quotes = $this->getSubrtn($arr, QuoteMediaStocks::GET_QUOTES_MAX_SYMBOLS, 'getQuotes');
        if ($quotes === false) {
            //todo: error getting quotes
        }
        $profiles = $this->getSubrtn($arr, QuoteMediaStocks::GET_PROFILES_MAX_SYMBOLS, 'getProfiles');
        if ($profiles === false) {
            //todo: error getting quotes
        }
        $fundamentals = $this->getSubrtn($arr, QuoteMediaStocks::GET_FUNDAMENTALS_MAX_SYMBOLS, 'getFundamentals');
        if ($fundamentals === false) {
            //todo: error getting quotes
        }

        $result = array();
        $result['quotes'] = $quotes;
        $result['profiles'] = $profiles;
        $result['fundamentals'] = $fundamentals;
        return $this->mergeResults($result);
    }

    public function getAllBatched($arr) {
        if ($this->batching) {
//TODO ERROR already batching
        }

        $this->batching = true;
    }

    public function hasNextBatch() {
        
    }

    public function getNextBatch() {
        
    }

}

?>