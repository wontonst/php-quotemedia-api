<?php

/**
 * Class that stores the result of a call to the stocks library. This class should not be instantiated by the user.
 */
class QuoteMediaStocksResult {

    protected $has_missing; /// <boolean of whether or not there are missing symbols
    protected $missing; ///< array of missing symbols

    /**
     * Construct an object to store results of a call to stocks library.
     * @param array $result result of the call
     * @param array $missing list of missing symbols
     */

    public function __construct($result, $missing = NULL) {
        if ($missing != NULL) {
            $this->$has_missing = true;
            $this->missing = $missing;
            parent::construct($result, QuoteMediaError::SYMBOL_DOES_NOT_EXIST);
        } else {
            $this->$has_missing = false;
            $this->missing = array();
            parent::construct($result, QuoteMediaError::GOOD);
        }
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
}

?>