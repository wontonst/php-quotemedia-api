<?php

/**
 * TODO: Custom keys for result
 */
class QuoteMediaStocks extends QuoteMediaBase {

    public function __construct($webmaster_id) {
        parent::__construct();
        $this->data['webmaster_id'] = $webmaster_id;
    }

    private function resetKeys() {
        $this->keys = array(
        );
    }

    /**
     * Perform a getQuotes call to retrieve basic stock quote information. The max size of the $array is QuoteMediaConst::GET_QUOTES_MAX_SYMBOLS
     * @param array $array array of ticker strings
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     */
    public function getQuotes(&$array, $use_assoc = false) {
        if (!$this->verifyInput($array, QuoteMediaConst::GET_QUOTES_MAX_SYMBOLS, QuoteMediaError::GET_QUOTES_EXCEED_MAX_SYMBOL)) {
            return false;
        }
        $xml = $this->callAPI(QuoteMediaConst::GET_QUOTES, $array);
        if (!$xml) {
            return false;
        }
        return $this->buildResult($xml, QuoteMediaConst::GET_QUOTES, $use_assoc);
    }

    /**
     * Perform a getQuotes call to retrieve basic company information. The max size of the $array is QuoteMediaConst::GET_PROFILES_MAX_SYMBOLS
     * @param type $array array of ticker strings
     */
    public function getProfiles(&$array, $use_assoc = false) {
        if (!$this->verifyInput($array, QuoteMediaConst::GET_PROFILES_MAX_SYMBOLS, QuoteMediaError::GET_PROFILES_EXCEED_MAX_SYMBOL)) {
            return false;
        }
        $xml = $this->callAPI(QuoteMediaConst::GET_PROFILES, $array);
        if (!$xml) {
            return false;
        }
        return $this->buildResult($xml, QuoteMediaConst::GET_PROFILES, $use_assoc);
    }

    /**
     * Perform a getQuotes call to retrieve company fundamental information. The max size of the $array is QuoteMediaConst::GET_FUNDAMENTALS_MAX_SYMBOLS
     * @param type $array array of ticker strings
     */
    public function getFundamentals(&$array, $use_assoc = false) {
        if (!$this->verifyInput($array, QuoteMediaConst::GET_FUNDAMENTALS_MAX_SYMBOLS, QuoteMediaError::GET_FUNDAMENTALS_EXCEED_MAX_SYMBOL)) {
            return false;
        }
        $xml = $this->callAPI(QuoteMediaConst::GET_FUNDAMENTALS, $array);
        if (!$xml) {
            return false;
        }
        return $this->buildResult($xml, QuoteMediaConst::GET_FUNDAMENTALS, $use_assoc);
    }

    /**
     * Convert an array of tickers into a comma delimited string for use in web API
     * @param array $tickers array of ticker strings
     * @return type
     */
    public function stringifyTickers(&$tickers) {
        return implode(',', $tickers);
    }

    /**
     * @param QuoteMediaStocks(constant) $type type of call to make
     * @param array $tickers array of ticker strings
     * @returns array returns raw xml data from API call
     */
    private function callAPI($type, &$tickers) {
        $url = $this->buildURL($type, $tickers);
//      echo $url;
        $response = file_get_contents($url);
        if (!$response) {
            //error can't reach the url
            $this->errorID = QuoteMediaError::API_HTTP_REQUEST_ERROR;
            return false;
        }
        $xml = simplexml_load_string($response);
        $count = 0;
        switch ($type) {//at this point $type is guaranteed to be correct due to $url
            case QuoteMediaConst::GET_QUOTES:
                $count = $xml->quote->count;
                break;
            case QuoteMediaConst::GET_PROFILES:
            case QuoteMediaConst::GET_FUNDAMENTALS:
                $count = $xml->company->count;
                break;
        }
        if ($count != $batch_size) {
            // TODO: determine which ticker didn't get included and report it or retry
        }
        return $xml;
    }

    private function verifyInput(&$input, $max, $max_exceed_error) {
        if (!is_array($input)) {
            $this->error = QuoteMediaError::INPUT_IS_NOT_ARRAY;
            return false;
        }
        if (count($input) > $max) {
            $this->error = $max_exceed_error;
            return false;
        }
        return true;
    }

    /**
     * Converts XML to an array using buildQuote/buildFundamentals/buildProfiles
     * @param SimpleXMLElement $xml xml to convert to array
     * @param integer $buildFunctionId function id, ex. QuoteMediaaStocks::GET_QUOTES
     * @param boolean $use_assoc return a map instead of an array mapping ticker to data.
     */
    private function buildResult(&$xml, $buildFunctionId, $use_assoc) {
        $buildFunctionStr = QuoteMediaConst::functIdToStr($buildFunctionId);
        switch ($buildFunctionId) {//id guaranteed to be correct now
            case QuoteMediaConst::GET_QUOTES:
                $element = 'quote';
                break;
            case QuoteMediaConst::GET_PROFILES:
                $element = 'company';
                break;
            case QuoteMediaConst::GET_FUNDAMENTALS:
                $element = 'company';
                break;
        }
        //may the programming Gods have mercy on my soul
        $mydignity = json_encode($xml);
        $isgone = json_decode($mydignity, TRUE);
var_dump($isgone);
        $return = array();
        if ($use_assoc) {
            foreach ($xml->$element as $v) {
                $arr = $this->$buildFunctionStr($v);
                $return[$arr['CompanyTicker']] = $arr;
            }
        } else {
            foreach ($xml->$element as $v) {
                $return[] = $this->$buildFunctionStr($v);
            }
        }
        return $return;
    }

    private function buildQuote(&$data) {
        $fields = array();
        $fields['CompanyName'] = str_replace('\'', '\'\'', $data->equityinfo->longname);
        $fields['CompanyNameReq'] = ucwords(strtolower($fields['CompanyName']));
        $fields['date123'] = date('Y-m-d');
        $fields['MarketCap'] = (int) $data->fundamental->marketcap;
        $fields['CompanyTicker'] = (string) $data->equityinfo->shortname;
        $fields['PercDec'] = (float) $data->pricedata->changepercent;
        $fields['ClosingPrice'] = (float) $data->pricedata->last;
        $fields['DollarChange'] = (float) $data->pricedata->change;
        $fields['peratio'] = (float) $data->fundamental->peratio;
        $fields['OpeningPrice'] = (float) $data->pricedata->open;
        $fields['IntradayLow'] = (float) $data->pricedata->low;
        $fields['IntradayHigh'] = (float) $data->pricedata->high;
        $fields['DailyVolume'] = (float) $data->pricedata->sharevolume;
        $fields['NumOfTrades'] = (int) $data->pricedata->tradevolume;
        $fields['ShareOutstanding'] = (int) $data->fundamental->sharesoutstanding;
        return $fields;
    }

    private function buildProfile(&$data) {
        //var_dump($data);  
        $temp = array(); //assuming mohit is right I need to addslash foreach
        $temp['CompanyDesc'] = $data->profile->longdescription;
        $temp['CompanyTicker'] = $data->symbolinfo->key->symbol;
        $temp['NameofCEO'] = $data->profile->details->ceo;
        $temp['NumofEmployees'] = $data->profile->details->employees;
        $temp['City'] = $data->profile->info->address->city;
        $temp['State'] = $data->profile->info->address->state;
        foreach ($temp as $k => &$v) {
            $v = addslashes($v);
        }
        return $temp;
    }

    private function buildFundamental(&$data) {
        $fields = array();
        $fields['CompanyTicker'] = (string) $data->symbolinfo->key->symbol;
        $fields['AvgDailyVol'] = (int) $data->statistical->avg30dayvolume;
        $fields['Week52high'] = (float) $data->statistical->week52high;
        $fields['Week52low'] = (float) $data->statistical->week52low;
        $fields['SMA50day'] = (float) $data->statistical->day50movingavg;
        $fields['SMA200day'] = (float) $data->statistical->day200movingavg;
        return $fields;
    }

    /**
     * @param array $s_tickers a one dimensional array of tickers
     * @returns string url to use
     */
    private function buildURL($type, &$tickers) {
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