<?php

/**
 * Class for all exchange related requests.
 */
class QuoteMediaExchanges extends QuoteMediaBase {

    public function __construct($webmaster_id) {
        parent::__construct($webmaster_id);
        $this->batcher = NULL;
    }

    public function getTickers($exchange) {
        $url = QuoteMediaConst::URL_ROOT.'getSymbols.json'.
                '?webmasterId=' . $this->webmaster_id . '&exgroup=' . $exchange;
        $response = file_get_contents($url);
	if (!$response) {
	   //can't reach url
	   throw new Exception(QuoteMediaError::IDtoError(QuoteMediaError::API_HTTP_REQUEST_ERROR).' '.$url);
	}
	$parsed = json_decode($response, true);
	if (!$parsed) {
	   throw new Exception(QuoteMediaError::API_XML_PARSE_ERROR);
	}
	$symbols=[];
	foreach ($parsed['results']['lookupdata'] as $symbol) {
	    $row = trim($symbol['key']['symbol']);
	    if ($row) {
	       $symbols[] = $row;
	    }
	}
	return $symbols;}
}

?>