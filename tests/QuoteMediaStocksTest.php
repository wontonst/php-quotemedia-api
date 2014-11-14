<?php

class QuoteMediaStocksTest extends QuoteMediaStocksTester {

    protected function setUp() {
        $this->api = new QuoteMediaStocks(TEST_WEBMASTER_ID);
        $this->setUpInputs();
    }

    /* generalized routines */

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