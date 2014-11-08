<?php

final class QuoteMediaConst {

    const URL_ROOT = 'http://app.quotemedia.com/data/';

    /* Function Identifiers */
    const GET_QUOTES = 0; ///< function identifier for getQuotes
    const GET_PROFILES = 1; ///< function identifier for getProfiles
    const GET_FUNDAMENTALS = 2; ///< function identifier for getFundamentals
    const GET_KEY_RATIOS = 3; ///< function identifier for getKeyRatios

    /**
     * Convert a function ID to string.
     * If this gets too long, convert to a singleton hash map.
     * @param integer $id function integer id
     * @return string function name
     */

    public static function functIdToStr($id) {
        switch ($id) {
            case QuoteMediaConst::GET_QUOTES:
                return "getQuotes";
            case QuoteMediaConst::GET_PROFILES:
                return "getProfiles";
            case QuoteMediaConst::GET_FUNDAMENTALS:
                return "getFundamentals";
            default:
                die('functIdToStr passed invalid $id ' . $id);
        }
    }

    /* Constants */

    const GET_QUOTES_MAX_SYMBOLS = 100; ///< maximum symbols per getQuotes call
    const GET_PROFILES_MAX_SYMBOLS = 50; ///< maximum symbols per getProfiles call
    const GET_FUNDAMENTALS_MAX_SYMBOLS = 50; ///< maximum symbols per getFundamentals call
    const GET_KEY_RATIOS_MAX_SYMBOLS = 1; ///< maximum symbols per getKeyRatios call

}

?>