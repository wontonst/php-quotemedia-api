<?php

class QuoteMediaStocksBatcherTest extends QuoteMediaStocksTester {

    protected function setUp() {
        $stocks = new QuoteMediaStocks(TEST_WEBMASTER_ID);
        $this->api = $stocks->getBatcher();
        $this->setUpInputs();
    }

    public function testNotArraySymbolInput() {
        foreach ($this->notArrayInputs as $bad) {
            $result = $this->api->getAll($bad, false);
            $this->assertTrue($result->hasError(), 'Bad first parameter ' . print_r($bad, true) . ' was not caught by get()');
            $this->assertEquals(QuoteMediaError::INPUT_IS_NOT_ARRAY, $result->getErrorID(), 'Giving get() a non array input yields incorrect error ' . $result->getError());
            $this->setUp();
        }
        foreach ($this->notArrayInputs as $bad) {
            $result = $this->api->getAll($bad, true);
            $this->assertTrue($result->hasError(), 'Bad first parameter ' . print_r($bad, true) . ' was not caught by get()');
            $this->assertEquals(QuoteMediaError::INPUT_IS_NOT_ARRAY, $result->getErrorID(), 'Giving get() a non array input yields incorrect error ' . $result->getError());
            $this->setUp();
        }
    }

    public function testNotArrayFunctionInput() {
        foreach ($this->notArrayInputs as $bad) {
            $result = $this->api->get(array('AAPL'), $bad, false);
            $this->assertTrue($result->hasError(), 'Bad second parameter ' . print_r($bad, true) . ' was not caught by get()');
            $this->assertEquals(QuoteMediaError::INPUT_IS_NOT_ARRAY, $result->getErrorID(), 'Giving get() a non array input yields incorrect error ' . $result->getError());
            $this->setUp();
        }
        foreach ($this->notArrayInputs as $bad) {
            $result = $this->api->get(array('AAPL'), $bad, true);
            $this->assertTrue($result->hasError(), 'Bad second parameter ' . print_r($bad, true) . ' was not caught by get()');
            $this->assertEquals(QuoteMediaError::INPUT_IS_NOT_ARRAY, $result->getErrorID(), 'Giving get() a non array input yields incorrect error ' . $result->getError());
            $this->setUp();
        }
    }

    public function testMalformedSymbolArr() {
        $result = $this->api->getAll($this->malformedSymbols['input'], false);
        $this->assertTrue($result->hasError(), 'Malformed symbol array ' . print_r($this->malformedSymbols['input'], true) . ' was not caught by get()');
        $this->assertEquals(QuoteMediaError::MALFORMED_SYMBOL, $result->getErrorID(), 'Giving get() a malformed symbol yields incorrect error ' . $result->getError());
    }

    public function testMalformedSymbolAssoc() {
        $result = $this->api->getAll($this->malformedSymbols['input'], true);
        $this->assertTrue($result->hasError(), 'Malformed symbol array ' . print_r($this->malformedSymbols['input'], true) . ' was not caught by get()');
        $this->assertEquals(QuoteMediaError::MALFORMED_SYMBOL, $result->getErrorID(), 'Giving get() a malformed symbol yields incorrect error ' . $result->getError());
    }

    public function testSymbolNotStringArr() {
        $result = $this->api->getAll($this->nonStringSymbols['input'], false);
        $this->assertTrue($result->hasError(), 'Nonstring symbol error was not caught by get()');
        $this->assertEquals(QuoteMediaError::SYMBOL_IS_NOT_STRING, $result->getErrorID(), 'Giving get() a symbol that is not a string yields incorrect error ' . $result->getError());
        $this->setUp();
    }

    public function testSymbolNotStringAssoc() {
        $result = $this->api->getAll($this->nonStringSymbols['input'], true);
        $this->assertTrue($result->hasError(), 'Nonstring symbol error was not caught by get()');
        $this->assertEquals(QuoteMediaError::SYMBOL_IS_NOT_STRING, $result->getErrorID(), 'Giving get() a symbol that is not a string yields incorrect error ' . $result->getError());
        $this->setUp();
    }

    private function validate(&$output) {
        $this->validateGetKeyRatios($output);
        $this->validateGetFundamentals($output);
        $this->validateGetProfiles($output);
        $this->validateGetQuotes($output);
    }

    private function getArrayAllTest($input) {
        foreach ($input as $v) {
            $result = $this->api->getAll($v, false);
            $this->validateArray($v, $result, 'getAll');
            $this->validate($result->getResult());
        }
    }

    private function getArrayTest($input, $functions, $validates) {
        foreach ($input as $v) {
            $result = $this->api->get($v, $functions, false);
            $this->validateArray($v, $result, 'get');
            foreach ($validates as $validate) {
                $this->$validate($result->getResult());
            }
        }
    }

    private function getAssocAllTest($input) {
        foreach ($input as $v) {
            $result = $this->api->getAll($v, true);
            $this->validateAssoc($v, $result, 'getAll');
            $this->validate($result->getResult());
        }
    }

    private function getAssocTest($input, $functions, $validates) {
        foreach ($input as $v) {
            $result = $this->api->get($v, $functions, true);
            $this->validateArray($v, $result, 'get');
            foreach ($validates as $validate) {
                $this->$validate($result->getResult());
            }
        }
    }

    public function testGetArray() {
        $this->getArrayAllTest($this->sArray);
    }

    public function testGetArrayMultiple() {
        $this->getArrayAllTest($this->sArray);
    }

    public function testGetQuotesArray() {
        $functions = array(QuoteMediaConst::GET_QUOTES);
        $validates = array('validateGetQuotes');
        $this->getArrayTest($this->sArray, $functions, $validates);
    }

    public function testGetQuotesArrayMultiple() {
        $functions = array(QuoteMediaConst::GET_QUOTES);
        $validates = array('validateGetQuotes');
        $this->getArrayTest($this->mArray, $functions, $validates);
    }

    public function testGetProfilesArray() {
        $functions = array(QuoteMediaConst::GET_PROFILES);
        $validates = array('validateGetProfiles');
        $this->getArrayTest($this->sArray, $functions, $validates);
    }

    public function testGetProfilesArrayMultiple() {
        $functions = array(QuoteMediaConst::GET_PROFILES);
        $validates = array('validateGetProfiles');
        $this->getArrayTest($this->mArray, $functions, $validates);
    }

    public function testGetQuotesProfilesArray() {
        $functions = array(QuoteMediaConst::GET_QUOTES, QuoteMediaConst::GET_PROFILES);
        $validates = array('validateGetQuotes', 'validateGetProfiles');
        $this->getArrayTest($this->sArray, $functions, $validates);
    }

    public function testGetQuotesProfilesArrayMultiple() {
        $functions = array(QuoteMediaConst::GET_QUOTES, QuoteMediaConst::GET_PROFILES);
        $validates = array('validateGetQuotes', 'validateGetProfiles');
        $this->getArrayTest($this->mArray, $functions, $validates);
    }

    public function testGetFundamentalsKeyRatiosArray() {
        $functions = array(QuoteMediaConst::GET_FUNDAMENTALS, QuoteMediaConst::GET_KEY_RATIOS);
        $validates = array('validateGetFundamentals', 'validateGetKeyRatios');
        $this->getArrayTest($this->sArray, $functions, $validates);
    }

    public function testGetFundamentalsKeyRatiosArrayMultiple() {
        $functions = array(QuoteMediaConst::GET_FUNDAMENTALS, QuoteMediaConst::GET_KEY_RATIOS);
        $validates = array('validateGetFundamentals', 'validateGetKeyRatios');
        $this->getArrayTest($this->mArray, $functions, $validates);
    }

    public function testGetAssoc() {
        $this->getAssocAllTest($this->mArray);
    }

    public function testGetAssocMultiple() {
        $this->getAssocAllTest($this->mArray);
    }

    public function testGetAssocLarge() {
        $this->getAssocAllTest($this->lArray);
    }

    public function testGetQuotesAssoc() {
        $functions = array(QuoteMediaConst::GET_QUOTES);
        $validates = array('validateGetQuotes');
        $this->getAssocTest($this->sArray, $functions, $validates);
    }

    public function testGetQuotesAssocMultiple() {
        $functions = array(QuoteMediaConst::GET_QUOTES);
        $validates = array('validateGetQuotes');
        $this->getAssocTest($this->mArray, $functions, $validates);
    }

    public function testGetQuotesAssocLarge() {
        $functions = array(QuoteMediaConst::GET_QUOTES);
        $validates = array('validateGetQuotes');
        $this->getAssocTest($this->lArray, $functions, $validates);
    }

    public function testGetProfilesAssoc() {
        $functions = array(QuoteMediaConst::GET_PROFILES);
        $validates = array('validateGetProfiles');
        $this->getAssocTest($this->sArray, $functions, $validates);
    }

    public function testGetProfilesAssocMultiple() {
        $functions = array(QuoteMediaConst::GET_PROFILES);
        $validates = array('validateGetProfiles');
        $this->getAssocTest($this->mArray, $functions, $validates);
    }

    public function testGetProfilesAssocLarge() {
        $functions = array(QuoteMediaConst::GET_PROFILES);
        $validates = array('validateGetProfiles');
        $this->getAssocTest($this->lArray, $functions, $validates);
    }

    public function testGetQuotesProfilesAssoc() {
        $functions = array(QuoteMediaConst::GET_QUOTES, QuoteMediaConst::GET_PROFILES);
        $validates = array('validateGetQuotes', 'validateGetProfiles');
        $this->getAssocTest($this->sArray, $functions, $validates);
    }

    public function testGetQuotesProfilesAssocMultiple() {
        $functions = array(QuoteMediaConst::GET_QUOTES, QuoteMediaConst::GET_PROFILES);
        $validates = array('validateGetQuotes', 'validateGetProfiles');
        $this->getAssocTest($this->mArray, $functions, $validates);
    }

    public function testGetQuotesProfilesAssocLarge() {
        $functions = array(QuoteMediaConst::GET_QUOTES, QuoteMediaConst::GET_PROFILES);
        $validates = array('validateGetQuotes', 'validateGetProfiles');
        $this->getAssocTest($this->lArray, $functions, $validates);
    }

    public function testGetFundamentalsKeyRatiosAssoc() {
        $functions = array(QuoteMediaConst::GET_FUNDAMENTALS, QuoteMediaConst::GET_KEY_RATIOS);
        $validates = array('validateGetFundamentals', 'validateGetKeyRatios');
        $this->getAssocTest($this->sArray, $functions, $validates);
    }

    public function testGetFundamentalsKeyRatiosAssocMultiple() {
        $functions = array(QuoteMediaConst::GET_FUNDAMENTALS, QuoteMediaConst::GET_KEY_RATIOS);
        $validates = array('validateGetFundamentals', 'validateGetKeyRatios');
        $this->getAssocTest($this->mArray, $functions, $validates);
    }

    public function testGetFundamentalsKeyRatiosAssocLarge() {
        $functions = array(QuoteMediaConst::GET_FUNDAMENTALS, QuoteMediaConst::GET_KEY_RATIOS);
        $validates = array('validateGetFundamentals', 'validateGetKeyRatios');
        $this->getAssocTest($this->lArray, $functions, $validates);
    }

}

?>