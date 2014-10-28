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
            $this->assertInternalType('array', $result, 'Error is ' . $this->api->getError());
            $this->assertEquals(count($arr), count($result), 'Array size returned by ' . $v . ' does not matched input array size.');

            foreach ($result as &$row) {
                if(is_bool($row)){
                    echo $v.' encountered an error: '.$this->api->getError()."\n";
                }
                $error_msg = 'A row in ' . $v . ' is type ' . gettype($row) . ' instead of array. ';
                $error_msg .= $row === false ? $this->api->getError() : 'Dump: ' . print_r($row, true);
                $this->assertInternalType('array', $row, $error_msg);
            }
        }
        foreach($result as $v){
            
        }
    }
}

?>