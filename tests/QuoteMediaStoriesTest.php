<?php

class QuoteMediaStoriesTest extends QuoteMediaStoriesTester {

    protected function setUp() {
        $this->api = new QuoteMediaStories(TEST_WEBMASTER_ID);
        $this->setUpInputs();
    }

    public function testHeadlinesDefault() {
        $result = $this->api->getHeadlines();
        $this->verifyHeadlines($result, QuoteMediaError::GOOD, 1, QuoteMediaConst::MAX_STORIES_PER_TOPIC);
    }

    public function testHeadlinesTopic() {
        $config = new QuoteMediaStoriesConfig();
        $config->setTopics($this->stdTopics);
        $result = $this->api->getHeadlines($config);
        $this->verifyHeadlines($result, QuoteMediaError::GOOD, count($this->stdTopics), QuoteMediaConst::MAX_STORIES_PER_TOPIC * count($this->stdTopics));
    }

    public function testHeadlinesTopicPerTopic() {
        foreach ($this->perTopic as $pt) {
            $config = new QuoteMediaStoriesConfig();
            $config->setTopics($this->stdTopics);
            $config->setPerTopic($pt);
            $result = $this->api->getHeadlines($config);
            $this->verifyHeadlines($result, QuoteMediaError::GOOD, count($this->stdTopics), count($this->stdTopics) * $pt);
        }
    }

    public function testStoriesDefault() {
        $result = $this->api->getStories();
        var_dump($result);
    }

}

?>