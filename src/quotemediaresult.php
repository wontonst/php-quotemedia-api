<?php

/**
 * Class that stores output of a call to the library. Contains error information and call result. This class should not be instantiated by the user.
 */
abstract class QuoteMediaResult {

    protected $error;
    protected $errorhistory;
    protected $result;

    public function __construct($builder) {
        $this->error = $builder->getError();
        $this->errorhistory = $builder->getErrorIDHistory();
        $this->result = $builder->getResult();
    }

    public function hasError() {
        return $this->error != QuoteMediaError::GOOD;
    }

    public function getErrorID() {
        return $this->error;
    }

    public function getError() {
        return QuoteMediaError::IDtoError($this->error);
    }

    public function getErrorIDHistory() {
        return $this->errorhistory;
    }

    /**
     * @returns the result, if the result is null means it couldn't get any meaningful result from API, like if a connection error occurred.
     */
    public function getResult() {
        return $this->result;
    }

}

?>