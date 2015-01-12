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

    public function __construct($builder) {
        parent::__construct($builder);
        $this->missing = $builder->getMissing();
        $this->has_missing = !empty($this->missing);
        $this->malformed = $builder->getMalformed();
        $this->has_malformed = !empty($this->malformed);
    }

    /**
     * Whether or not symbols are missing.
     * @return bool
     */
    public function hasMissing() {
        return $this->has_missing;
    }

    /**
     * Return list of missing symbols.
     * @return array missing symbols
     */
    public function getMissing() {
        return $this->missing;
    }

    public function hasMalformed() {
        return $this->has_malformd;
    }

    public function getMalformed() {
        return $this->malformed;
    }

}

?>