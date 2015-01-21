<?php

abstract class QuoteMediaStocksTester extends PHPUnit_Framework_TestCase {

    protected function setUpInputs() {
        $this->sArray = array(
            array('AAPL'),
            array('KO'),
            array('SWHC'),
            array('MS'),
            array('GOOG'),
            array('CRD.B'),
            array('VNP:CA'),
        );
        $this->mArray = array(
            array('GOOG', 'AAPL'),
            array('SWHC', 'MSFT', 'MS', 'C', 'XLNX', 'GOOG', 'AAPL', 'KO', 'PX', 'F')
        );
        $this->lArray = array(
            array(
                'WTS', 'WTW', 'WWD', 'WSO', 'WSR', 'WERN', 'WEX', 'WEYS', 'DISCK', 'LLL',
                'DLPH', 'DLTR', 'DNB', 'DNR', 'DO', 'DOV', 'DOW', 'DPS', 'DRI', 'DTE',
                'DTV', 'DUK', 'DVA', 'DVN', 'EA', 'EBAY', 'ECL', 'ED', 'EFX', 'FRGI',
                'FRM', 'FRME', 'CVX', 'FRO', 'FRP', 'FSP', 'FSS', 'MMM', 'FSTR', 'LEG',
                'FSYS', 'FTD', 'FTK', 'FUEL', 'C', 'FUR', 'HIVE', 'FVE', 'FWRD', 'LLY',
                'FXCB', 'FXCM', 'FXEN', 'GABC', 'GAIA', 'GALE', 'GALT', 'GB', 'GBCI', 'GBL',
                'GBLI', 'GBNK', 'GBX', 'GCA', 'GCAP', 'GCO', 'GDOT', 'GDP', 'GEO',
            ),
        );
        $this->notArrayInputs = array(
            123, '123', $this, false, true
        );
        $this->malformedSymbols = array(
            'input' => array(
                'AAPL', 'GOOG', '$$', 'GOOG!', '123', 'LUV@', 'antidisestablishmentarianismheyo', 'CVS', 'BRK A', 'AAPL:123', 'AAPL:LUV@', 'AAPL:AA!', 'AAPL:$$', 'AAPL:antidisestablishmentarianismismheyo', 'AAPL:A A', 'SWHC'
            ),
            'malformed' => array(
                '$$', 'GOOG!', '123', 'LUV@', 'antidisestablishmentarianismheyo', 'BRK A', 'AAPL:123', 'AAPL:LUV@', 'AAPL:AA!', 'AAPL:$$', 'AAPL:antidisestablishmentarianismismheyo', 'AAPL:A A',
            ),
            'result' => array(
                'AAPL', 'GOOG', 'CVS', 'SWHC',
            ),
        );
        $this->nonStringSymbols = array(
            'input' => array('AAPL', 'CVS', 123, 321, 2, 'SWHC', 3, array(), 'CVX', 'C'),
            'malformed' => array(123, 321, 2, 3, 'array'),
            'result' => array('AAPL', 'CVS', 'SWHC', 'CVX', 'C'),
        );
        $this->nonexistantSymbols = array(
            'input' => array('AAPL', 'GROL', 'GOOG', 'GRLO', 'C'),
            'missing' => array('GROL', 'GRLO'),
            'result' => array('AAPL', 'GOOG', 'C'),
        );
    }

    protected function validateStandard(&$output) {
        $fields = array(
            'symbol',
            'exchange',
            'longname',
            'shortname',
        );
        $this->validateHasFields($fields, $output);
    }

    protected function validateGetProfiles(&$output) {
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

    protected function validateGetFundamentals(&$output) {
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
            //'day200movingavg',//apparently some dont' have this
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

    protected function validateGetQuotes($output) {
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
            //'peratio',
            //'pbratio',
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

    protected function validateGetKeyRatios($output) {
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

    /**
     * Checks to make sure there are no errors, that the result is an array, that the array size is same as input array size, and makes sure each row in the array is also an array/
     * @param type $input
     * @param type $output
     * @param type $function_name
     */
    private function validateOutput($input, $result, $function_name) {
        $this->assertInternalType('array', $result->getResult(), 'Error is ' . $result->getError());
        $this->assertEquals(QuoteMediaError::GOOD, $result->getErrorID(), 'No longer in GOOD state, current state is ' . $result->getError());
        $getresult = $result->getResult();
        $this->assertEquals(count($getresult), count($input), 'Array size returned by ' . $function_name . ' does not matched input array size.' . "\n" . print_r($getresult, true));

        foreach ($getresult as $row) {
            $error_msg = 'A row in the result of ' . $function_name . ' is type ' . gettype($row) . ' instead of array. ';
            $error_msg .= $row === false ? 'Encountered error: ' . $result->getError() : 'Dump: ' . print_r($row, true);
            $this->assertInternalType('array', $row, $error_msg);
        }
    }

    protected function validateHasFields(&$fields, &$output) {
        foreach ($output as $out) {
            foreach ($fields as $f) {
                $this->assertTrue(isset($out[$f]), 'Resulting array' . (isset($out['symbol']) ? ' for symbol ' . $out['symbol'] : '') . ' is missing field ' . $f . "\n" . print_r($out, true));
            }
        }
    }

    protected function validateArray($input, $result, $function_name) {
        $this->validateOutput($input, $result, $function_name);
        //assert tickers
        $getresult = $result->getResult();
        for ($i = 0; $i != count($input); $i++) {
            $found = false;
            foreach ($getresult as $out) {
                if ($input[$i] == $out['symbol']) {
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, 'Ticker ' . $input[$i] . ' could not be found in the resulting array. Dump: ' . print_r($getresult, true));
        }
    }

    protected function validateAssoc($input, $result, $function_name) {
        $this->validateOutput($input, $result, $function_name);
        $getresult = $result->getResult();
        foreach ($input as $in) {
            $this->assertTrue(isset($getresult[$in]), $in . ' could not be found in the resulting associative array. Dump: ' . print_r($getresult, true));
            $this->assertEquals($in, $getresult[$in]['symbol'], 'Resulting associative array has incorrect symbol "' . $getresult[$in]['symbol'] . '", expecting ' . $in);
        }
    }

}
