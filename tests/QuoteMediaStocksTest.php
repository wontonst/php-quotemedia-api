<?php

class QuoteMediaStocksTest extends PHPUnit_Framework_TestCase {

    protected function setUp() {
        $this->api = new QuoteMediaStocks(TEST_WEBMASTER_ID);
    }
    public function testGetArray() {
        $functions = array('getProfiles', 'getQuotes', 'getFundamentals');

        $arr = array('AAPL', 'GOOG', 'LUV');

        foreach ($functions as $v) {
            $result = $this->api->$v($arr);
            $this->assertInternalType('array', $result, 'Error is ' . QuoteMediaError::IDtoError($this->api->getErrorID()));
            $this->assertEquals(count($arr), count($result), 'Array size returned by ' . $v . ' does not matched input array size.');

            foreach ($result as &$row) {
                $this->assertInternalType('array', $row, 'A row in ' . $v . ' is type ' . gettype($row) . ' instead of array. Dump: ' . print_r($row, true));
            }
        }
    }

    public function testGetAssoc() {
        $functions = array('getProfiles', 'getQuotes', 'getFundamentals');

        $arr = array('AAPL', 'GOOG', 'LUV');

        foreach ($functions as $v) {
            $result = $this->api->$v($arr, true);
            $this->assertInternalType('array', $result, 'Error is ' . QuoteMediaError::IDtoError($this->api->getErrorID()));
            $this->assertEquals(count($arr), count($result), 'Array size returned by ' . $v . ' does not matched input array size.');

            foreach ($result as &$row) {
                $this->assertInternalType('array', $row, 'A row in ' . $v . ' is type ' . gettype($row) . ' instead of array. Dump: ' . print_r($row, true));
            }
            foreach ($arr as $ticker) {
                $this->assertTrue(isset($result[$ticker]));
            }
        }
    }

}

?>