<?php

/**
  Retrieves all stock data at once for an arbitrarily long input. Built on top of QuoteMediaStocks thru composition.
 * Todo
 * Enforce getSubrtn $cmd parameter
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
            $res = $this->api->$cmd($slice);
            $ret[] = $res;
        }
        return $ret;
    }

    /**
     * Merges the results of getProfile getFundamentals getQuotes into a single array.
     * @param array $results array size 3 keyed 'quotes','fundamentals','profiles' to be merged into a single array.
     * @return array merged array
     */
    private function mergeResults($results, $use_assoc) {
        $ret = array();

        foreach ($results[0] as $k => &$q) {
            $line = $q;
            for ($i = 1; $i != count($results); $i++) {
                $line = array_merge($line, $results[$i][$k]);
            }
            if ($use_assoc) {
                $ret[$line['symbol']] = $line;
                continue;
            }//else
            $ret[] = $line;
        }
        return $ret;
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
            $result[] = $res;
        }
        return $this->mergeResults($result, $use_assoc);
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