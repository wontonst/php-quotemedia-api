<?php

class QuoteMediaStocksHelper {

    /**
     * @param array $s_tickers a one dimensional array of tickers
     * @returns string url to use
     */
    public static function buildStockURL($type, &$tickers, $webmaster_id) {
        $url_middle = ''; //will be used to store profile, fundamentals, or quote
        switch ($type) {
            case QuoteMediaConst::GET_QUOTES:
                $url_middle = 'getQuotes.xml';
                break;
            case QuoteMediaConst::GET_PROFILES:
                $url_middle = 'getProfiles.xml';
                break;
            case QuoteMediaConst::GET_FUNDAMENTALS:
                $url_middle = 'getFundamentals.xml';
                break;
            case QuoteMediaConst::GET_KEY_RATIOS:
                $url_middle = 'getKeyRatiosBySymbol.xml';
                break;
            default:
	throw exception('Invalid type '.$type.'. $type must be a valid QuoteMediaConst function identifier.');
break;
        }

        return QuoteMediaConst::URL_ROOT . $url_middle .
                '?webmasterId=' . $webmaster_id .
                ($type == QuoteMediaConst::GET_KEY_RATIOS ? '&symbol=' : '&symbols=') .
                QuoteMediaBase::csvify($tickers);
    }

}

?>
