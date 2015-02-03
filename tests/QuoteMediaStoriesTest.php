<?php

class QuoteMediaStoriesTest extends QuoteMediaStocksTester {

    protected function setUp() {
        $this->api = new QuoteMediaStories(TEST_WEBMASTER_ID);
    }

    private function verifyHeadlines($result, $expected_error, $expected_tsize,$expected_nsize) {
        $this->assertEqual($result->getErrorID(), $expected_error);
        $this->assertEqual($result->getTopicSize(),$expected_tsize);
        $this->assertEqual($result->getNewsSize(),$expected_nsize);
        $this->assertEqual(count($result->getResult()),$expected_nsize);
    }

    public function testHeadlinesDefault() {
        $result = $this->api->getHeadlines();
        $this->verifyHeadlines($result,QuoteMediaErrors::GOOD,1,250);
    }

}

?>