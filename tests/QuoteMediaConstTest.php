<?php

class QuoteMediaConstTest extends PHPUnit_Framework_TestCase {

public function testVerifyStocksFunctions(){
$this->assertTrue(in_array(QuoteMediaConst::GET_QUOTES,QuoteMediaConst::$STOCKS_FUNCTIONS));
}
public function testFunctIdToStr(){
$this->assertEquals('getQuotes',QuoteMediaConst::functIdToStr(QuoteMediaConst::GET_QUOTES));
}
public function testGetMaxSymbols(){
$this->assertEquals(QuoteMediaConst::GET_QUOTES_MAX_SYMBOLS, QuoteMediaConst::getMaxSymbols(QuoteMediaConst::GET_QUOTES));
}

}
?>