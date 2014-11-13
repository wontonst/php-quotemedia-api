<?php

class QuoteMediaBatcherTest extends QuoteMediaStocksTester {

    protected function setUp() {
        $this->api = new QuoteMediaBatcher(TEST_WEBMASTER_ID);
        $this->setUpInputs();
    }

    private function validate(&$output) {
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
            'last',
            'change',
            'changepercent',
            'open',
            'high',
            'low',
            'prevclose',
            //'bid',//optional if past aftermarket
            //'ask',
            //'bidsize',
            //'asksize',
            'rawbidsize',
            'rawasksize',
            'tradevolume',
            'sharevolume',
            'vwap',
            'lasttradedatetime',
            'eps',
            'peratio',
            'pbratio',
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

    private function getArrayTest($input) {
        foreach ($input as $v) {
            $result = $this->api->getAll($v,false);
            $this->validateArray($v, $result, 'getAll');
            $this->validate($result);
        }
    }

    private function getAssocTest($input) {
        foreach ($input as $v) {
            $result = $this->api->getAll($v,true);
            $this->validateAssoc($v, $result, 'getAll');
            $this->validate($result);
        }
    }

    public function testGetArray() {
        $this->getArrayTest($this->sArray);
    }

    public function testGetArrayMultiple() {
        $this->getArrayTest($this->sArray);
    }

    public function testGetAssoc() {
        $this->getAssocTest($this->mArray);
    }

    public function testGetAssocMultiple() {
        $this->getAssocTest($this->mArray);
    }

}

?>