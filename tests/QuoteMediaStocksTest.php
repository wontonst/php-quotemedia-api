<?php

class QuoteMediaStocksTest extends QuoteMediaStocksTester {

    protected function setUp() {
        $this->api = new QuoteMediaStocks(TEST_WEBMASTER_ID);
        $this->setUpInputs();
    }

    /* generalized routines */

    private function validateGetProfiles(&$output) {
        $fields = array(
            'symbol',
            'exchange',
            'longname',
            'shortname',
            'shortdescription',
            'longdescription',
            'address1',
            'address2',
            'city',
            'state',
            'country',
            'postcode',
            'telephone',
            'facisimile',
            'website',
            'email',
            'ceo',
            'employees',
            'issuetype',
            'isocfi',
            'auditor',
            'lastAudit',
            'marketcap',
            'sector',
            'industry',
            'qmid',
            'qmdescription',
            'cik',
            'naics',
            'sics',
        );
        $this->validateHasFields($fields, $output);
    }

    private function validateGetFundamentals(&$output) {
        $fields = array(
            'symbol',
            'exchange',
            'longname',
            'shortname',
            'sharesoutstanding',
            'marketcap',
            'eps',
            'peratio',
            'pbratio',
            //'dividenddate',//optional
            //'dividendamount',
            //'dividendyield',
            'sdate',
            'sshares',
            'sratio',
            'adrratio',
            'ptbratio',
            'pcfratio',
            'pfcfratio',
            'week52high',
            'week52low',
            //'week52performance',//apparently optional too
            'day21movingavg',
            'day50movingavg',
            'day200movingavg',
            'avg10dayvolume',
            'avg30dayvolume',
            'avg90dayvolume',
            'alpha',
            'beta',
            'r2',
            'stddev',
            'periods',
            'day21ema',
            'day50ema',
            'day200ema',
        );
        $this->validateHasFields($fields, $output);
    }

    private function validateGetQuotes($output) {
        $fields = array(
            'symbol',
            'exchange',
            'longname',
            'shortname',
            'last',
            'change',
            'changepercent',
            'open',
            'high',
            'low',
            'prevclose',
            //'bid',
            //  'ask',
            'bidsize',
            'asksize',
            'rawbidsize',
            'rawasksize',
            'tradevolume',
            'sharevolume',
            'vwap',
            'lasttradedatetime',
            'sharesoutstanding',
            'marketcap',
            'eps',
            'peratio',
            'pbratio',
            'week52high',
            'week52low',
                //'dividenddate',//optional
                //'dividendamount',
                //'dividendyield',
                //'dividendlastamount',
                //'dividendfrequency',
                //'sharesescrow',//wow optionall to wtf
        );
        $this->validateHasFields($fields, $output);
    }

    private function validateGetKeyRatios($output) {
        $fields = array(
            'symbol',
            'exchange',
            'longname',
            'shortname',
            'revenue',
            'revenuepershare',
            'revenue3years',
            'revenue5years',
            'quickratio',
            'currentratio',
            'longtermdebttocapital',
            'totaldebttoequity',
            'intcoverage',
            'leverageratio',
            'returnonequity',
            'returnoncapital',
            'returnonassets',
            'peratio',
            'pehighlast5years',
            'pelowlast5years',
            'pricetosales',
            'pricetobook',
            'pricetotangiblebook',
            'pricetocashflow',
            'pricetofreecash',
            'grossmargin',
            'ebitmargin',
            'ebitdamargin',
            'pretaxprofitmargin',
            'profitmargincont',
            'profitmargintot',
            'assetsturnover',
            'invoiceturnover',
            'receivablesturnover',
        );
        $this->validateHasFields($fields, $output);
    }

    /* get array routines & tests */

    private function getArrayTest($function, $inputArrays) {
        foreach ($inputArrays as $input) {
            $result = $this->api->$function($input, false);
            $this->validateArray($input, $result, $function);
            $out[] = $result;
        }
        return $out;
    }

    public function testGetProfilesArray() {
        $result = $this->getArrayTest('getProfiles', $this->sArray);
        foreach ($result as $res) {
            $this->validateGetProfiles($res);
        }
    }

    public function testGetFundamentalsArray() {
        $result = $this->getArrayTest('getFundamentals', $this->sArray);
        foreach ($result as $res) {
            $this->validateGetFundamentals($res);
        }
    }

    public function testGetQuotesArray() {
        $result = $this->getArrayTest('getQuotes', $this->sArray);
        foreach ($result as $res) {
            $this->validateGetQuotes($res);
        }
    }

    public function testGetKeyRatiosArray() {
        $result = $this->getArrayTest('getKeyRatios', $this->sArray);
        foreach ($result as $res) {
            $this->validateGetKeyRatios($res);
        }
    }

    public function testGetProfilesArrayMultiple() {
        $result = $this->getArrayTest('getProfiles', $this->mArray);
        foreach ($result as $res) {
            $this->validateGetProfiles($res);
        }
    }

    public function testGetQuotesArrayMultiple() {
        $result = $this->getArrayTest('getQuotes', $this->mArray);
        foreach ($result as $res) {
            $this->validateGetQuotes($res);
        }
    }

    public function testGetFundamentalsArrayMultiple() {
        $result = $this->getArrayTest('getFundamentals', $this->mArray);
        foreach ($result as $res) {
            $this->validateGetFundamentals($res);
        }
    }

    /* get associative array routines & tests */

    private function getAssocTest($function, $inputArrays) {
        foreach ($inputArrays as $input) {
            $result = $this->api->$function($input, true);
            $this->validateAssoc($input, $result, $function);
            $out[] = $result;
        }
        return $out;
    }

    public function testGetProfilesAssoc() {
        $result = $this->getAssocTest('getProfiles', $this->sArray);
        foreach ($result as $res) {
            $this->validateGetProfiles($res);
        }
    }

    public function testGetFundamentalsAssoc() {
        $result = $this->getAssocTest('getFundamentals', $this->sArray);
        foreach ($result as $res) {
            $this->validateGetFundamentals($res);
        }
    }

    public function testGetQuotesAssoc() {
        $result = $this->getAssocTest('getQuotes', $this->sArray);
        foreach ($result as $res) {
            $this->validateGetQuotes($res);
        }
    }

    public function testGetQuotesMultipleAssoc() {
        $result = $this->getAssocTest('getQuotes', $this->mArray);
        foreach ($result as $res) {
            $this->validateGetQuotes($res);
        }
    }

}

?>