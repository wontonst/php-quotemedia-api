<?php

final class QuoteMediaError {

    const GOOD = 0;
    const API_HTTP_REQUEST_ERROR = -1;
    const INPUT_IS_NOT_ARRAY = -2;
    const GET_QUOTES_EXCEED_MAX_SYMBOLS = -3;
    const GET_PROFILES_EXCEED_MAX_SYMBOLS = -4;
    const GET_FUNDAMENTALS_EXCEED_MAX_SYMBOLS = -5;
    const GET_KEY_RATIOS_EXCEED_MAX_SYMBOLS = -6;
    const API_XML_PARSE_ERROR = -7;
    const SYMBOL_IS_NOT_STRING = -8;
    const MALFORMED_SYMBOL = -9;

    private static $instance = null;

    private function __construct() {
        $this->data = array(
            QuoteMediaError::GOOD => 'No error has occurred.',
            QuoteMediaError::API_HTTP_REQUEST_ERROR => 'Could not access API due to HTTP error',
            QuoteMediaError::INPUT_IS_NOT_ARRAY => 'You did not pass an array on your last function call. Functions like getQuote expect an array as the first parameter.',
            QuoteMediaError::GET_QUOTES_EXCEED_MAX_SYMBOLS => 'getQuotes cannot request more than ' . QuoteMediaConst::GET_QUOTES_MAX_SYMBOLS . ' symbols at a time.',
            QuoteMediaError::GET_PROFILES_EXCEED_MAX_SYMBOLS => 'getProfiles cannot request more than ' . QuoteMediaConst::GET_PROFILES_MAX_SYMBOLS . ' symbols at a time.',
            QuoteMediaError::GET_FUNDAMENTALS_EXCEED_MAX_SYMBOLS => 'getFundamentals cannot request more than ' . QuoteMediaConst::GET_FUNDAMENTALS_MAX_SYMBOLS . ' symbols at a time.',
            QuoteMediaError::GET_KEY_RATIOS_EXCEED_MAX_SYMBOLS => 'getKeyRatios cannot request more than ' . QuoteMediaConst::GET_KEY_RATIOS_MAX_SYMBOLS . ' symbols at a time.',
            QuoteMediaError::API_XML_PARSE_ERROR => 'Result from QuoteMedia is not a parsable XML file.s',
            QuoteMediaError::MALFORMED_SYMBOL => 'Symbol contains invalid characters',
            QuoteMediaError::SYMBOL_IS_NOT_STRING => 'Expected a symbols string but was not passed a string',
        );
    }

    public static function IDtoError($id) {
        if (QuoteMediaError::$instance == null) {
            QuoteMediaError::$instance = new QuoteMediaError();
        }
        if (array_key_exists($id, QuoteMediaError::$instance->data)) {
            return QuoteMediaError::$instance->data[$id];
        } else {
            return false;
        }
    }

}

?>