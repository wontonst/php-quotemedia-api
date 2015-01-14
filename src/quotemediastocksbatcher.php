<?php

/**
 * Retrieves all stock data at once for an arbitrarily long input. Built on top of QuoteMediaStocks thru composition.
 * DO NOT call the constructor, instead create a QuoteMediaStocks object and call getBatcher()
 */
class QuoteMediaStocksBatcher extends QuoteMediaBase {

    private $api; ///< instance of QuoteMediaStocks

    //private $batching; ///< whether or not the batching is complete

    public function __construct($stocks) {
        $type = get_class($stocks);
        if ($type != 'QuoteMediaStocks') {
            throw new Exception('Invalid QuoteMediaBatcher constructor parameter. Only accepts a QuoteMediaStocks parent object.');
        }
        $this->api = $stocks;
    }

    /**
     * Calls ((QuoteMediaStocks)this->api)->$cmd() to call and grab data from QuoteMedia.
     * @param array $array arbitrarily long array of stock tickers
     * @param int $max_per_call maximum number of tickers to pass to the api on each call.
     * @param int $cmd id of function to run, ex QuoteMediaConst::GET_QUOTES
     * @return array array of QuoteMediaStocksResult objects
     */
    private function getSubrtn($array, $max_per_call, $cmd) {
        $ret = array();
        $iter = ceil(count($array) / $max_per_call);
        for ($i = 0; $i != $iter; $i++) {
            $slice = array_slice($array, $i * $max_per_call, $max_per_call);
            $res = $this->api->$cmd($slice, true); //always grab as assoc
            $ret[] = $res;
        }
        return $ret;
    }

    /**
     * Merges an array of QuoteMediaResult objects into a single results array.
     * @param QuoteMediaStocksResultsBuilder $builder
     * @param array $results array size 3 keyed 'quotes','fundamentals','profiles' to be merged into a single array.
     */
    private function mergeResults(&$builder, $results, $use_assoc) {
        $ret = array();

        foreach ($results as $ro) {
            $result = $ro->getResult();
            foreach ($result as $symbol => $data) {
                if (isset($ret[$symbol])) {
                    $ret[$symbol] = array_merge($ret[$symbol], $data);
                } else {
                    $ret[$symbol] = $data;
                    if (!$use_assoc) {
                        $ret[$symbol]['symbol'] = $symbol;
                    }
                }
            }
        }
        $builder->setResult($use_assoc ? $ret : array_values($ret));
    }

    private function mergeOther(&$builder, $results, $name) {
        $ret = array();
        $get = 'get' . $name;
        $set = 'set' . $name;
        foreach ($results as $ro) {
            $result = $ro->$get();
            $ret = array_unique(array_merge($ret, $result));
        }
        $builder->$set($ret);
    }

    /**
     * Retrieve specified stock data for arbitrarily long input.
     * @param array $symbols array of tickers
     * @param array $functions array of function identifiers (see QuoteMediaConst)
     * @param bool $use_assoc whether not to return an associative map
     * @return array array/map of stock data
     */
    public function get($symbols, $functions, $use_assoc = false) {
        $builder = new QuoteMediaStocksResultBuilder();
        if (!is_array($functions)) {//make sure input is valid
            $builder->setError(QuoteMediaError::INPUT_IS_NOT_ARRAY);
            return $builder->build();
        }
        $builder->setRawInput($symbols);
        if (!$builder->verifyRawInputIsArray()) {
            return $builder->build(); //cannot proceed if input is not array
        }
        $verified = $this->removeMalformed($symbols, $builder); //malformed symbols are not in $verified
        $cleaned = $this->cleanSymbolArray($verified);
        $builder->setInput($cleaned);

        $result = array();
        foreach ($functions as $function) {
            $res = $this->getSubrtn($cleaned, QuoteMediaConst::getMaxSymbols($function), QuoteMediaConst::functIdToStr($function));
            $result = array_merge($result, $res);
        }
        $this->mergeResults($builder, $result, $use_assoc);
        $this->mergeOther($builder, $result, 'Missing');
        $this->mergeOther($builder, $result, 'Malformed');
        $this->mergeOther($builder, $result, 'ErrorIDHistory');

        return $builder->build();
    }

    /**
     *       Retrieves all stock data for an arbitrarily long input.
     * @param array $symbols array of tickers
     * @param bool $use_assoc whether not to return an associative map
     * @return array array/map of stock data
     */
    public function getAll($symbols, $use_assoc = false) {
        return $this->get($symbols, QuoteMediaConst::$STOCKS_FUNCTIONS, $use_assoc);
    }

    /* TO BE DEVELOPED
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
     */
}

?>