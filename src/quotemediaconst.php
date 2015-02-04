<?php

final class QuoteMediaConst {

    const URL_ROOT = 'http://app.quotemedia.com/data/';

    /* Function Identifiers */
    //stocks
    const GET_QUOTES = 0; ///< function identifier for getQuotes
    const GET_PROFILES = 1; ///< function identifier for getProfiles
    const GET_FUNDAMENTALS = 2; ///< function identifier for getFundamentals
    const GET_KEY_RATIOS = 3; ///< function identifier for getKeyRatios

    public static $STOCKS_FUNCTIONS = array(
        QuoteMediaConst::GET_QUOTES,
        QuoteMediaConst::GET_PROFILES,
        QuoteMediaConst::GET_FUNDAMENTALS,
        QuoteMediaConst::GET_KEY_RATIOS
    );

    //stories
    const GET_HEADLINES = 4; ///< function identifier for getHeadlines
    const GET_STORIES = 5; ///< function identifier for getStories

    public static $STORIES_FUNCTIONS = array(
        QuoteMediaConst::GET_HEADLINES,
        QuoteMediaConst::GET_STORIES,
    );
    private static $FUNCT_ID_STR_MAP = array(
        QuoteMediaConst::GET_QUOTES => 'getQuotes',
        QuoteMediaConst::GET_PROFILES => 'getProfiles',
        QuoteMediaConst::GET_FUNDAMENTALS => 'getFundamentals',
        QuoteMediaConst::GET_KEY_RATIOS => 'getKeyRatios',
        QuoteMediaConst::GET_HEADLINES => 'getHeadlines',
        QuoteMediaConst::GET_STORIES => 'getStories',
    );

    /**
     * Convert a function ID to string.
     * If this gets too long, convert to a singleton hash map.
     * @param integer $id function integer id
     * @return string function name
     */
    public static function functIdToStr($id) {
        if (!isset(QuoteMediaConst::$FUNCT_ID_STR_MAP[$id])) {
            die('QuoteMediaConst::functIdToStr passed invalid $id ' . $id);
        }
        return QuoteMediaConst::$FUNCT_ID_STR_MAP[$id];
    }

    /* Constants */

    const GET_QUOTES_MAX_SYMBOLS = 100; ///< maximum symbols per getQuotes call
    const GET_PROFILES_MAX_SYMBOLS = 50; ///< maximum symbols per getProfiles call
    const GET_FUNDAMENTALS_MAX_SYMBOLS = 50; ///< maximum symbols per getFundamentals call
    const GET_KEY_RATIOS_MAX_SYMBOLS = 1; ///< maximum symbols per getKeyRatios call

    private static $MAX_SYMBOLS_MAP = array(
        QuoteMediaConst::GET_QUOTES => QuoteMediaConst::GET_QUOTES_MAX_SYMBOLS,
        QuoteMediaConst::GET_PROFILES => QuoteMediaConst::GET_PROFILES_MAX_SYMBOLS,
        QuoteMediaConst::GET_FUNDAMENTALS => QuoteMediaConst::GET_FUNDAMENTALS_MAX_SYMBOLS,
        QuoteMediaConst::GET_KEY_RATIOS => QuoteMediaConst::GET_KEY_RATIOS_MAX_SYMBOLS,
    );

    public static function getMaxSymbols($id) {
        if (!isset(QuoteMediaConst::$MAX_SYMBOLS_MAP[$id])) {
            die('QuoteMediaConst::getMaxSymbols passed invalid id ' . $id);
        }
        return QuoteMediaConst::$MAX_SYMBOLS_MAP[$id];
    }

    const MAX_STORIES_PER_TOPIC = 250; ///< maximum stories to get per topic
    const LANG_ENGLISH = 0;
    const LANG_FRENCH = 1;

    private static $LANG_ID_STR_MAP = array(
        QuoteMediaConst::LANG_ENGLISH => 'en',
        QuoteMediaConst::LANG_FRENCH => 'fr',
    );

    public static function langIdToStr($id) {
        if (!isset($QuoteMediaConst::$LANG_ID_STR_MAP[$id])) {
            die('QuoteMediaConst::langIdToStr passed invalid $id ' . $id);
        }
        return QuoteMediaConst::$LANG_ID_STR_MAP[$id];
    }

}

?>