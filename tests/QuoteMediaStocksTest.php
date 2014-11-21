<?php

class QuoteMediaStocksTest extends QuoteMediaStocksTester {

    protected function setUp() {
        $this->api = new QuoteMediaStocks(TEST_WEBMASTER_ID);
        $this->setUpInputs();
    }

    public function testNotArrayInput() {//tests every single input value in $this->badInputs and every single function in QuoteMediaConst::$STOCKS_FUNCTIONS
        foreach (QuoteMediaConst::$STOCKS_FUNCTIONS as $fnctid) {
            foreach ($this->notArrayInputs as $bad) {
                $function_name = QuoteMediaConst::functIdToStr($fnctid);
                $this->assertFalse($this->api->$function_name($bad, false), 'Bad input ' . print_r($bad, true) . ' was not caught by ' . $function_name);
                $this->assertEquals(QuoteMediaError::INPUT_IS_NOT_ARRAY, $this->api->getErrorID(), 'Giving ' . $function_name . ' a non array input yields incorrect error ' . $this->api->getError());
                $this->setUp();
            }
        }
        foreach (QuoteMediaConst::$STOCKS_FUNCTIONS as $fnctid) {
            foreach ($this->notArrayInputs as $bad) {
                $function_name = QuoteMediaConst::functIdToStr($fnctid);
                $this->assertFalse($this->api->$function_name($bad, true), 'Bad input ' . print_r($bad, true) . ' was not caught by ' . $function_name);
                $this->assertEquals(QuoteMediaError::INPUT_IS_NOT_ARRAY, $this->api->getErrorID(), 'Giving ' . $function_name . ' a non array input yields incorrect error ' . $this->api->getError());
                $this->setUp();
            }
        }
    }

    public function testMalformedSymbol() {
        foreach (QuoteMediaConst::$STOCKS_FUNCTIONS as $fnctid) {
            $function_name = QuoteMediaConst::functIdToStr($fnctid);
            $this->assertFalse($this->api->$function_name($this->malformedSymbols, false), 'Malformed symbol array ' . print_r($this->malformedSymbols, true) . ' was not caught by ' . $function_name);
            $this->assertEquals(QuoteMediaError::MALFORMED_SYMBOL, $this->api->getErrorID(), 'Giving ' . $function_name . ' a malformed symbol yields incorrect error ' . $this->api->getError());
            $this->setUp();
        }
        foreach (QuoteMediaConst::$STOCKS_FUNCTIONS as $fnctid) {
            $function_name = QuoteMediaConst::functIdToStr($fnctid);
            $this->assertFalse($this->api->$function_name($this->malformedSymbols, true), 'Malformed symbol array ' . print_r($this->malformedSymbols, true) . ' was not caught by ' . $function_name);
            $this->assertEquals(QuoteMediaError::MALFORMED_SYMBOL, $this->api->getErrorID(), 'Giving ' . $function_name . ' a malformed symbol yields incorrect error ' . $this->api->getError());
            $this->setUp();
        }
    }

    public function testSymbolNotString() {
        foreach (QuoteMediaConst::$STOCKS_FUNCTIONS as $fnctid) {
            foreach ($this->nonStringSymbol as $bad) {
                $function_name = QuoteMediaConst::functIdToStr($fnctid);
                $this->assertFalse($this->api->$function_name($bad, false), 'Nonstring symbol input ' . print_r($bad, true) . ' was not caught by ' . $function_name);
                $this->assertEquals(QuoteMediaError::SYMBOL_IS_NOT_STRING, $this->api->getErrorID(), 'Giving ' . $function_name . ' a symbol that is not a string yields incorrect error ' . $this->api->getError());
                $this->setUp();
            }
        }
        foreach (QuoteMediaConst::$STOCKS_FUNCTIONS as $fnctid) {
            foreach ($this->nonStringSymbol as $bad) {
                $function_name = QuoteMediaConst::functIdToStr($fnctid, true);
                $this->assertFalse($this->api->$function_name($bad), 'Nonstring symbol input ' . print_r($bad, true) . ' was not caught by ' . $function_name);
                $this->assertEquals(QuoteMediaError::SYMBOL_IS_NOT_STRING, $this->api->getErrorID(), 'Giving ' . $function_name . ' a symbol that is not a string yields incorrect error ' . $this->api->getError());
                $this->setUp();
            }
        }
    }
    
    public function testSymbolDoesNotExist(){
        $this->api->getQuotes(array('GROL'));
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