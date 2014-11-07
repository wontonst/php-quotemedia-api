<?php

class QuoteMediaStocksTest extends PHPUnit_Framework_TestCase {

    protected function setUp() {
        $this->api = new QuoteMediaStocks(TEST_WEBMASTER_ID);
        $this->sArray = array('AAPL');
    }

    private function validateOutput(&$input, &$output, $function_name) {
        $this->assertInternalType('array', $output, 'Error is ' . $this->api->getError());
        $this->assertEquals(count($output), count($input), 'Array size returned by ' . $function_name . ' does not matched input array size.' . "\n" . print_r($output, true));

        foreach ($output as &$row) {
            $error_msg = 'A row in the result of ' . $function_name . ' is type ' . gettype($row) . ' instead of array. ';
            $error_msg .= $row === false ? 'Encountered error: ' . $this->api->getError() : 'Dump: ' . print_r($row, true);
            $this->assertInternalType('array', $row, $error_msg);
        }
    }

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

    private function validateAssoc(&$input, &$output, $function_name) {
        $this->validateOutput($input, $output, $function_name);
        foreach ($input as $in) {
            $this->assertTrue(isset($output[$in]), $in . ' could not be found in the resulting associative array. Dump: ' . print_r($output, true));
            $this->assertEquals($in, $output[$in]['symbol'], 'Resulting associative array has incorrect symbol "' . $output[$in]['symbol'] . '", expecting ' . $in);
        }
    }

    private function getArrayTest($function, $tickers) {
        $result = $this->api->$function($tickers, false);
        $this->validateArray($tickers, $result, $function);
    }

    public function testGetProfilesArray() {
        $this->getArrayTest('getProfiles', $this->sArray);
    }

    public function testGetFundamentalsArray() {
        $this->getArrayTest('getFundamentals', $this->sArray);
    }

    public function testGetQuotesArray() {
        $this->getArrayTest('getQuotes', $this->sArray);
    }

    private function getAssocTest($function, $tickers) {
        $result = $this->api->$function($tickers, true);
        $this->validateAssoc($tickers, $result, $function);
    }

    public function testGetProfilesAssoc() {
        $this->getAssocTest('getProfiles', $this->sArray);
    }

    public function testGetFundamentalsAssoc() {
        $this->getAssocTest('getFundamentals', $this->sArray);
    }

    public function testGetQuotesAssoc() {
        $this->getAssocTest('getQuotes', $this->sArray);
    }
/*
    public function testGetArrayMultiple() {
        $functions = array('getProfiles', 'getQuotes', 'getFundamentals');

        $tickers = array('AAPL', 'GOOG', 'LUV', 'SWHC', 'AAL', 'F', 'C', 'PX', 'TXN');

        foreach ($functions as $v) {
            $result = $this->api->$v($tickers, false);
            $this->validateArray($tickers, $result, $v);
        }
    }

    public function testGetAssocMultiple() {
        $functions = array('getProfiles', 'getQuotes', 'getFundamentals');

        $tickers = array('AAPL', 'GOOG', 'LUV', 'SWHC', 'AAL', 'F', 'C', 'PX', 'TXN');

        foreach ($functions as $v) {
            $result = $this->api->$v($tickers, true);
            $this->validateAssoc($tickers, $result, $v);
        }
    }
*/
}

?>