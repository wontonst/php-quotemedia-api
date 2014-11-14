<?php

/**
  Retrieves all stock data at once for an arbitrarily long input. Built on top of QuoteMediaStocks thru composition.
 * Todo
 * Enforce getSubrtn $cmd parameter
 */
class QuoteMediaBatcher extends QuoteMediaBase {

    private $api; ///< instance of QuoteMediaStocks

    //private $batching; ///< whether or not the batching is complete

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
        //$batching=false;
    }

    /**
     * Return the error in the QuoteMediaStocks instance.
     */
    public function getStocksError() {
        return $this->api->getError();
    }

    /**
     * Return the error ID in the QuoteMediaStocks instance.
     */
    public function getStocksErrorID() {
        return $this->api->getErrorID();
    }

    /**
     * Calls ((QuoteMediaStocks)this->api)->$cmd() to call and grab data from QuoteMedia.
     * @param type $array arbitrarily long array of stock tickers
     * @param type $max_per_call maximum number of tickers to pass to the api on each call.
     * @param type $cmd id of function to run, ex QuoteMediaConst::GET_QUOTES
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
            foreach ($res as &$v) {
                $ret[$v['symbol']] = $v;
            }
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

    public function get($arr, $functions, $use_assoc = false) {
        if (!is_array($arr)) {//make sure input is valid
            $this->error = QuoteMediaError::INPUT_IS_NOT_ARRAY;
            return false;
        }
        if (!is_array($functions)) {//make sure input is valid
            $this->error = QuoteMediaError::INPUT_IS_NOT_ARRAY;
            return false;
        }
        $result = array();
        foreach ($functions as $function) {
            $res = $this->getSubrtn($arr, QuoteMediaConst::getMaxSymbols($function), QuoteMediaConst::functIdToStr($function));
            if($res == false){
                $this->error = $this->api->getErrorID();
                return false;
            }
            $result[]=$res;
        }
        return $this->mergeResults($result, $use_assoc);
    }

    /**
      Retrieves all stock data for an arbitrarily long input.
     */
    public function getAll($arr, $use_assoc = false) {
        return $this->get($arr, QuoteMediaConst::$STOCKS_FUNCTIONS, $use_assoc);
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