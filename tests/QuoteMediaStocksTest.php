<?php

class QuoteMediaStocksTest extends PHPUnit_Framework_TestCase {

    protected function setUp() {
        $this->api = new QuoteMediaStocks(TEST_WEBMASTER_ID);
    }

    public function testGetArray() {
        $functions = array('getProfiles', 'getQuotes', 'getFundamentals');

        $tickers = array('AAPL', 'GOOG', 'LUV');

        foreach ($functions as $v) {
            $result = $this->api->$v($tickers);
            $this->assertInternalType('array', $result, 'Error is ' . $this->api->getError());
            $this->assertEquals(count($tickers), count($result), 'Array size returned by ' . $v . ' does not matched input array size.');

            foreach ($result as &$row) {
                $error_msg = 'A row in the result of ' . $v . ' is type ' . gettype($row) . ' instead of array. ';
                $error_msg .= $row === false ? 'Encountered error: ' . $this->api->getError() : 'Dump: ' . print_r($row, true);
                $this->assertInternalType('array', $row, $error_msg);
            }
            //assert tickers
            for ($i = 0; $i != count($tickers); $i++) {
                $this->assertEquals($tickers[$i], $result[$i], 'Result ticker mismatch.');
            }
        }
    }

}

?>