<?php

class QuoteMediaApi {

    public function __construct($webmaster_id) {
        $this->data['webmaster_id'] = $webmaster_id;
    }

    /**
     * @param QuoteMediaStocks(constant) $type type of call to make
     * @param array $tickers array of ticker strings
     * @returns array returns raw xml data from API call
     */
    public function callStock($type, $tickers) {
        $url = $this->buildStockURL($type, $tickers);
//      echo $url;
        $response = file_get_contents($url);
        if (!$response) {
            //error can't reach the url
            $this->errorID = QuoteMediaError::API_HTTP_REQUEST_ERROR;
            return false;
        }
        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (!$xml) {
            //error parsing the XML
            $this->errorID = QuoteMediaError::API_XML_PARSE_ERROR;
            return false;
        }
        $count = 0;
        switch ($type) {//at this point $type is guaranteed to be correct due to $url
            case QuoteMediaConst::GET_QUOTES:
                $count = $xml['size'];
                break;
            case QuoteMediaConst::GET_PROFILES:
            case QuoteMediaConst::GET_FUNDAMENTALS:
                $count = $xml->symbolcount;
                break;
        }
        if ($count != count($tickers)) {
            // TODO: determine which ticker didn't get included and report it or retry
        }
        return $xml;
    }

    /**
     * @param array $s_tickers a one dimensional array of tickers
     * @returns string url to use
     */
    public function buildStockURL($type, &$tickers) {
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
            default:
//TODO: error, invalid type (programmer error)
        }

        return QuoteMediaConst::URL_ROOT . $url_middle .
                '?webmasterId=' . $this->data['webmaster_id'] .
                '&symbols=' . $this->stringifyTickers($tickers);
    }

}

?>