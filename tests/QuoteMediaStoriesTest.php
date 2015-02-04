<?php

class QuoteMediaStoriesTest extends QuoteMediaStoriesTester {

    protected function setUp() {
        $this->api = new QuoteMediaStories(TEST_WEBMASTER_ID);
        $this->setUpInputs();
    }

    public function testHeadlinesDefault() {
        $result = $this->api->getHeadlines();
        $this->verifyHeadlines($result, QuoteMediaError::GOOD, 1, 250);
    }
    public function testHeadlinesTopic(){
        $config = new QuoteMediaStoriesConfig();
        $config->setTopics($this->stdTopics);
        $result = $this->api->getHeadlines();
        $this->verifyHeadlines($result,QuoteMediaError::GOOD,count($this->stdTopics),250);
    }

}

?>