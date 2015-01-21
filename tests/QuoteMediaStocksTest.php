<?php

class QuoteMediaStocksTest extends QuoteMediaStocksTester {

    protected function setUp() {
        $this->api = new QuoteMediaStocks(TEST_WEBMASTER_ID);
        $this->setUpInputs();
    }

    public function testNotArrayInput() {//tests every single input value in $this->badInputs and every single function in QuoteMediaConst::$STOCKS_FUNCTIONS
        foreach (QuoteMediaConst::$STOCKS_FUNCTIONS as $fnctid) {
            if ($fnctid == QuoteMediaConst::GET_KEY_RATIOS) {
                continue;
            }
            foreach ($this->notArrayInputs as $bad) {
                $function_name = QuoteMediaConst::functIdToStr($fnctid);
                $result = $this->api->$function_name($bad, false);
                $this->assertNotNull($result, 'Result is NULL!');
                $this->assertTrue($result->hasError(), 'Bad input ' . print_r($bad, true) . ' was not caught by ' . $function_name);
                $this->assertEquals(QuoteMediaError::INPUT_IS_NOT_ARRAY, $result->getErrorID(), 'Giving ' . $function_name . ' a non array input yields incorrect error ' . $result->getError());
                $this->setUp();
            }
        }
    }

    public function testNotArrayInputAssoc() {//tests every single input value in $this->badInputs and every single function in QuoteMediaConst::$STOCKS_FUNCTIONS
        foreach (QuoteMediaConst::$STOCKS_FUNCTIONS as $fnctid) {
            if ($fnctid == QuoteMediaConst::GET_KEY_RATIOS) {
                continue;
            }
            foreach ($this->notArrayInputs as $bad) {
                $function_name = QuoteMediaConst::functIdToStr($fnctid);
                $result = $this->api->$function_name($bad, true);
                $this->assertNotNull($result, 'Result is NULL!');
                $this->assertTrue($result->hasError(), 'Bad input ' . print_r($bad, true) . ' was not caught by ' . $function_name);
                $this->assertEquals(QuoteMediaError::INPUT_IS_NOT_ARRAY, $result->getErrorID(), 'Giving ' . $function_name . ' a non array input yields incorrect error ' . $result->getError());
                $this->setUp();
            }
        }
    }

    private function resultArrayContainsSymbol($array, $symbol) {
        foreach ($array as $v) {
            if ($v['symbol'] == $symbol) {
                return true;
            }
        }
        return false;
    }

    public function testMalformedSymbol() {
        foreach (QuoteMediaConst::$STOCKS_FUNCTIONS as $fnctid) {
            if ($fnctid == QuoteMediaConst::GET_KEY_RATIOS) {
                continue;
            }
            $function_name = QuoteMediaConst::functIdToStr($fnctid);
            $result = $this->api->$function_name($this->malformedSymbols['input'], false);
            $this->assertNotNull($result, 'Result is NULL!');
            $this->assertTrue($result->hasError(), 'Malformed symbol array ' . print_r($this->malformedSymbols, true) . ' was not caught by ' . $function_name);
            $this->assertEquals(QuoteMediaError::MALFORMED_SYMBOL, $result->getErrorID(), 'Giving ' . $function_name . ' a malformed symbol yields incorrect error ' . $result->getError());
            $malformed = $result->getMalformed();
            //verify that malformed symbols are in the malformed array
            foreach ($this->malformedSymbols['malformed'] as $m) {
                $this->assertTrue(in_array($m, $malformed), 'Malformed symbol ' . $m . ' was not found in the result\'s getMalformed() array: ' . print_r($malformed, true));
            }
            //verify that good symbols are not in the malformed array
            foreach ($this->malformedSymbols['result'] as $m) {
                $this->assertFalse(in_array($m, $malformed), 'Valid symbol ' . $m . ' was found in the result\'s getMalformed() array: ' . print_r($malformed, true));
            }
            $getresult = $result->getResult();
            //verify that malformed symbols are not in the result array
            foreach ($this->malformedSymbols['malformed'] as $m) {
                $this->assertFalse($this->resultArrayContainsSymbol($getresult, $m), 'Malformed symbol ' . $m . ' was found in the result\'s getResult() array: ' . print_r($getresult, true));
            }
            //verify that good symbols are in the result array
            foreach ($this->malformedSymbols['result'] as $m) {
                $this->assertTrue($this->resultArrayContainsSymbol($getresult, $m), 'Valid symbol ' . $m . ' was not found in the result\'s getResult() array: ' . print_r($getresult, true));
            }
            $this->setUp();
        }
    }

    public function testMalformedSymbolAssoc() {
        foreach (QuoteMediaConst::$STOCKS_FUNCTIONS as $fnctid) {
            if ($fnctid == QuoteMediaConst::GET_KEY_RATIOS) {
                continue;
            }
            $function_name = QuoteMediaConst::functIdToStr($fnctid);
            $result = $this->api->$function_name($this->malformedSymbols['input'], true);
            $this->assertNotNull($result, 'Result is NULL!');
            $this->assertTrue($result->hasError(), 'Malformed symbol array ' . print_r($this->malformedSymbols, true) . ' was not caught by ' . $function_name);
            $this->assertEquals(QuoteMediaError::MALFORMED_SYMBOL, $result->getErrorID(), 'Giving ' . $function_name . ' a malformed symbol yields incorrect error ' . $result->getError());
            $malformed = $result->getMalformed();
            //verify that malformed symbols are in the malformed array
            foreach ($this->malformedSymbols['malformed'] as $m) {
                $this->assertTrue(in_array($m, $malformed), 'Malformed symbol ' . $m . ' was not found in the result\'s getMalformed() array: ' . print_r($malformed, true));
            }
            //verify that good symbols are not in the malformed array
            foreach ($this->malformedSymbols['result'] as $m) {
                $this->assertFalse(in_array($m, $malformed), 'Valid symbol ' . $m . ' was found in the result\'s getMalformed() array: ' . print_r($malformed, true));
            }
            $getresult = $result->getResult();
            //verify that malformed symbols are not in the result array
            foreach ($this->malformedSymbols['malformed'] as $m) {
                $this->assertFalse(in_array($m, array_keys($getresult)), 'Malformed symbol ' . $m . ' was found in the result\'s getResult() array: ' . print_r($getresult, true));
            }
            //verify that good symbols are in the result array
            foreach ($this->malformedSymbols['result'] as $m) {
                $this->assertTrue(in_array($m, array_keys($getresult)), 'Valid symbol ' . $m . ' was not found in the result\'s getResult() array: ' . print_r($getresult, true));
            }
            $this->setUp();
        }
    }

    public function testSymbolNotString() {
        foreach (QuoteMediaConst::$STOCKS_FUNCTIONS as $fnctid) {
            if ($fnctid == QuoteMediaConst::GET_KEY_RATIOS) {
                continue;
            }
            $function_name = QuoteMediaConst::functIdToStr($fnctid);
            $result = $this->api->$function_name($this->nonStringSymbols['input'], false);
            $this->assertNotNull($result, 'Result is NULL!');
            $this->assertTrue($result->hasError(), 'Nonstring symbol input ' . print_r($this->nonStringSymbols['input'], true) . ' was not caught by ' . $function_name);
            $this->assertEquals(QuoteMediaError::SYMBOL_IS_NOT_STRING, $result->getErrorID(), 'Giving ' . $function_name . ' a symbol that is not a string yields incorrect error ' . $result->getError());
            //verify that malformed symbols are in the malformed array
            foreach ($this->nonStringSymbols['malformed'] as $m) {
                $this->assertTrue(in_array($m, $result->getMalformed()), 'Nonstring symbol ' . $m . ' was not found in the result\'s getMalformed() array.');
            }
            //verify that good symbols are not in malformed array
            foreach ($this->nonStringSymbols['result'] as $m) {
                $this->assertFalse(in_array($m, $result->getMalformed()), 'Valid symbol ' . $m . ' was found in the result\'s getMalformed() array.');
            }
            $getresult = $result->getResult();
            //verify that malformed symbols are not in the result array
            foreach ($this->nonStringSymbols['malformed'] as $m) {
                $this->assertFalse($this->resultArrayContainsSymbol($getresult, $m), 'Nonstring symbol ' . $m . ' was found in the result\'s getResult() array: ' . print_r($getresult, true));
            }
            //verify that good symbols are in the result array
            foreach ($this->nonStringSymbols['result'] as $m) {
                $found = false;
                foreach ($getresult as $v) {
                    if ($v['symbol'] == $m) {
                        $found = true;
                        break;
                    }
                }
                $this->assertTrue($this->resultArrayContainsSymbol($getresult, $m), 'Valid symbol ' . $m . ' was not found in the result\'s getResult() array: ' . print_r($getresult, true));
            }
            $this->setUp();
        }
    }

    public function testSymbolNotStringAssoc() {
        foreach (QuoteMediaConst::$STOCKS_FUNCTIONS as $fnctid) {
            if ($fnctid == QuoteMediaConst::GET_KEY_RATIOS) {
                continue;
            }
            $function_name = QuoteMediaConst::functIdToStr($fnctid);
            $result = $this->api->$function_name($this->nonStringSymbols['input'], false);
            $this->assertNotNull($result, 'Result is NULL!');
            $this->assertTrue($result->hasError(), 'Nonstring symbol input ' . print_r($this->nonStringSymbols['input'], true) . ' was not caught by ' . $function_name);
            $this->assertEquals(QuoteMediaError::SYMBOL_IS_NOT_STRING, $result->getErrorID(), 'Giving ' . $function_name . ' a symbol that is not a string yields incorrect error ' . $result->getError());
            //verify that malformed symbols are in the malformed array
            foreach ($this->nonStringSymbols['malformed'] as $m) {
                $this->assertTrue(in_array($m, $result->getMalformed()), 'Nonstring symbol ' . $m . ' was not found in the result\'s getMalformed() array.');
            }
            //verify that good symbols are not in malformed array
            foreach ($this->nonStringSymbols['result'] as $m) {
                $this->assertFalse(in_array($m, $result->getMalformed()), 'Valid symbol ' . $m . ' was found in the result\'s getMalformed() array.');
            }
            $getresult = $result->getResult();
            //verify that malformed symbols are not in the result array
            foreach ($this->nonStringSymbols['malformed'] as $m) {
                $this->assertFalse($this->resultArrayContainsSymbol($getresult, $m), 'Nonstring symbol ' . $m . ' was found in the result\'s getResult() array: ' . print_r($getresult, true));
            }
            //verify that good symbols are in the result array
            foreach ($this->nonStringSymbols['result'] as $m) {
                $found = false;
                foreach ($getresult as $v) {
                    if ($v['symbol'] == $m) {
                        $found = true;
                        break;
                    }
                }
                $this->assertTrue($this->resultArrayContainsSymbol($getresult, $m), 'Valid symbol ' . $m . ' was not found in the result\'s getResult() array: ' . print_r($getresult, true));
            }
            $this->setUp();
        }
    }

    public function testSymbolDoesNotExist() {
        foreach (QuoteMediaConst::$STOCKS_FUNCTIONS as $fnctid) {
            if ($fnctid == QuoteMediaConst::GET_KEY_RATIOS) {
                continue;
            }
            $function_name = QuoteMediaConst::functIdToStr($fnctid);
            $result = $this->api->$function_name($this->nonexistantSymbols['input'], true);
            $this->assertNotNull($result, 'Result is NULL!');
            $this->assertEquals(QuoteMediaError::SYMBOL_DOES_NOT_EXIST, $result->getErrorID(), 'Did not find the expected Symbol Does Not Exist error, instead got ' . $result->getError());

            //verify that result array has the correct values
            $getresult = $result->getResult();
            foreach ($this->nonexistantSymbols['result'] as $row) {
                $this->assertTrue(in_array($row, array_keys($getresult)), 'Valid symbol ' . $row . ' was not found in result array: ' . print_r($getresult, true));
            }
            //verify that result array does not have invalid values
            foreach ($this->nonexistantSymbols['missing'] as $row) {
                $this->assertFalse(in_array($row, array_keys($getresult)), 'Nonexistant symbol ' . $row . ' was found in the result array: ' . print_r($getresult, true));
            }
            //verify that missing array has nonexistant symbols
            $missing = $result->getMissing();
            foreach ($this->nonexistantSymbols['missing'] as $row) {
                $this->assertTrue(in_array($row, array_values($missing)), 'Nonexistant symbol ' . $row . ' was not found in missing array: ' . print_r($missing, true));
            }
            //veirfy that missing array does not have valid symbols
            foreach ($this->nonexistantSymbols['result'] as $row) {
                $this->assertFalse(in_array($row, array_values($missing)), 'Valid symbol ' . $row . ' was found in the missing array: ' . print_r($missing, true));
            }
        }
    }

    public function testSymbolDoesNotExistAssoc() {
        foreach (QuoteMediaConst::$STOCKS_FUNCTIONS as $fnctid) {
            if ($fnctid == QuoteMediaConst::GET_KEY_RATIOS) {
                continue;
            }
            $function_name = QuoteMediaConst::functIdToStr($fnctid);
            $result = $this->api->$function_name($this->nonexistantSymbols['input'], true);
            $this->assertNotNull($result, 'Result is NULL!');
            $this->assertEquals(QuoteMediaError::SYMBOL_DOES_NOT_EXIST, $result->getErrorID(), 'Did not find the expected Symbol Does Not Exist error, instead got ' . $result->getError());

            //verify that result array has the correct values
            $output = $result->getResult();
            foreach ($this->nonexistantSymbols['result'] as $row) {
                $this->assertTrue(in_array($row, array_keys($output)), 'Valid symbol ' . $row . ' was not found in result array: ' . print_r($output, true));
            }
            //verify that result array does not have invalid values
            foreach ($this->nonexistantSymbols['missing'] as $row) {
                $this->assertFalse(in_array($row, array_keys($output)), 'Nonexistant symbol ' . $row . ' was found in the result array: ' . print_r($output, true));
            }
            //verify that missing array has nonexistant symbols
            $missing = $result->getMissing();
            foreach ($this->nonexistantSymbols['missing'] as $row) {
                $this->assertTrue(in_array($row, array_values($missing)), 'Nonexistant symbol ' . $row . ' was not found in missing array: ' . print_r($missing, true));
            }
            //veirfy that missing array does not have valid symbols
            foreach ($this->nonexistantSymbols['result'] as $row) {
                $this->assertFalse(in_array($row, array_values($missing)), 'Valid symbol ' . $row . ' was found in the missing array: ' . print_r($missing, true));
            }
        }
    }

    public function testErrorHistory() {
        //TODO
    }

    /* get array routines & tests */

    private function getArrayTest($function, $inputArrays) {
        foreach ($inputArrays as $input) {
            $result = $this->api->$function($input, false);
            $this->validateArray($input, $result, $function);
            $out[] = $result->getResult();
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
            $out[] = $result->getResult();
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