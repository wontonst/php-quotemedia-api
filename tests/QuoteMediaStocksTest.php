<?php

class QuoteMediaStocksTest extends PHPUnit_Framework_TestCase {

    protected function setUp() {
        $this->api = new QuoteMediaStocks(TEST_API_ID);
    }

    public function testGetQuotes() {
        $arr = array('GOOG');
        $arr = array('AAPL', 'GOOG', 'LUV');

        $o2 = $this->api->getQuotes($arr);
        $this->assertEquals(count($arr), count($o2), 'Array size returned by getQuotes does not matched input array size.');
        if (!$o2) {
            echo QuoteMediaError::IDtoError($d->getErrorID());
        } else {
            var_dump($o2);
        }
    }

    public function testGetProfiles() {
        $o = $this->api->getProfiles($arr);
        if (!$o) {
            echo QuoteMediaError::IDtoError($d->getErrorID());
        } else {
            var_dump($o);
        }
        $o3 = $this->api->getFundamentals($arr);
        if (!$o3) {
            echo QuoteMediaError::IDtoError($d->getErrorID());
        } else {
            var_dump($o3);
        }
    }

}

?>