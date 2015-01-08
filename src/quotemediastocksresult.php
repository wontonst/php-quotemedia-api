<?php

/**
 * Class that stores the result of a call to the stocks library. This class should not be instantiated by the user.
 */
class QuoteMediaStocksResult extends QuoteMediaResult {

    protected $has_missing; /// <boolean of whether or not there are missing symbols
    protected $missing; ///< array of missing symbols

    /**
     * Construct an object to store results of a call to stocks library.
     * @param array $result result of the call
     * @param array $missing list of missing symbols
     */

    public function __construct($result, $error, $missing, $malformed) {
        parent::construct($result, $error);
        $this->missing = $missing;
        $this->has_missing = !empty($missing);
        $this->malformed = $malformed;
        $this->has_malformed = !empty($malformed);
    }

    /**
     * Whether or not symbols are missing.
     * @return bool
     */
    public function hasMissingSymbols() {
        return $this->has_missing;
    }

    /**
     * Return list of missing symbols.
     * @return array missing symbols
     */
    public function getMissingSymbols() {
        return $this->missing;
    }

    public function hasMalformedSymbols() {
        return $this->has_malformd;
    }

    public function getMalformedSymbols() {
        return $this->malformed;
    }

}

?>