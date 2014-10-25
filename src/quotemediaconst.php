<?php

class QuoteMediaConst {

    const URL_ROOT = 'http://app.quotemedia.com/data/';
    
    /* Function Identifiers */
    const GET_QUOTES = 0; ///< function identifier for getQuotes
    const GET_PROFILES = 1; ///< function identifier for getProfiles
    const GET_FUNDAMENTALS = 2; ///< function identifier for getFundamentals

    /**
     * Convert a function ID to string
     * @param integer $id function integer id
     * @return string function name
     */
    public static function functIdToStr($id) {
        switch ($id) {
            case GET_QUOTES:
                return "getQuotes";
            case GET_PROFILES:
                return "getProfiles";
            case GET_FUNDAMENTALS:
                return "getFundamentals";
            default:
                die('functIdToStr passed invalid $id ' . $id);
        }
    }

    /* Constants */
    const GET_QUOTES_MAX_SYMBOLS = 100; ///< maximum symbols per getQuotes call
    const GET_PROFILES_MAX_SYMBOLS = 50; ///< maximum symbols per getProfiles call
    const GET_FUNDAMENTALS_MAX_SYMBOLS = 50; ///< maximum symbols per getFundamentals call

}

?>