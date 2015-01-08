<?php

/**
 * Class that stores output of a call to the library. Contains error information and call result. This class should not be instantiated by the user.
 */
abstract class QuoteMediaResult {

    protected $error;
    protected $result;

    public function __construct($result, $error) {
        $this->error = $error;
        $this->result = $result;
    }

    public function getErrorID() {
        return $this->error;
    }

    public function getError() {
        return QuoteMediaError::IDtoError($this->error);
    }

    /**
     * @returns the result, if the result is null means it couldn't get any meaningful result from API, like if a connection error occurred.
     */
    public function getResult() {
        return $this->result;
    }

}

?>