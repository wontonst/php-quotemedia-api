<?php

class QuoteMediaStocksTest extends PHPUnit_Framework_TestCase {

    protected function setUp() {
        $this->api = new QuoteMediaStocks(TEST_WEBMASTER_ID);
        $this->sArray = array(
            array('AAPL'),
            array('KO'),
            array('SWHC'),
            array('MS'),
            array('GOOG')
        );
        $this->mArray = array(
            array('GOOG', 'AAPL'),
            array('SWHC', 'MSFT', 'MS', 'C', 'XLNX', 'GOOG', 'AAPL', 'KO', 'PX', 'F')
        );
    }

    /* generalized routines */

    private function validateOutput(&$input, &$output, $function_name) {
        $this->assertInternalType('array', $output, 'Error is ' . $this->api->getError());
        $this->assertEquals(count($output), count($input), 'Array size returned by ' . $function_name . ' does not matched input array size.' . "\n" . print_r($output, true));

        foreach ($output as &$row) {
            $error_msg = 'A row in the result of ' . $function_name . ' is type ' . gettype($row) . ' instead of array. ';
            $error_msg .= $row === false ? 'Encountered error: ' . $this->api->getError() : 'Dump: ' . print_r($row, true);
            $this->assertInternalType('array', $row, $error_msg);
        }
    }

    private function validateHasFields(&$fields, &$output) {
        foreach ($output as $out) {
            foreach ($fields as $f) {
                $this->assertTrue(isset($out[$f]), 'Resulting array is missing field ' . $f . "\n" . print_r($out, true));
            }
        }
    }

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
            'bid',
            'ask',
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

    /* get array routines & tests */

    private function validateArray(&$input, &$output, $function_name) {
        $this->validateOutput($input, $output, $function_name);
        //assert tickers
        for ($i = 0; $i != count($input); $i++) {
            $found = false;
            foreach ($output as $out) {
                if ($input[$i] == $out['symbol']) {
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, 'Ticker ' . $input[$i] . ' could not be found in the resulting array. Dump: ' . print_r($output, true));
        }
    }

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

    private function validateAssoc(&$input, &$output, $function_name) {
        $this->validateOutput($input, $output, $function_name);
        foreach ($input as $in) {
            $this->assertTrue(isset($output[$in]), $in . ' could not be found in the resulting associative array. Dump: ' . print_r($output, true));
            $this->assertEquals($in, $output[$in]['symbol'], 'Resulting associative array has incorrect symbol "' . $output[$in]['symbol'] . '", expecting ' . $in);
        }
    }

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