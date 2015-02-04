<?php

abstract class QuoteMediaStoriesTester extends PHPUnit_Framework_TestCase {

    protected function setUpInputs() {
        $this->stdTopics = array(
            'GOOG', 'AAPL',
        );
    }

    protected function verifyHeadlines($result, $expected_error, $expected_tsize, $expected_nsize) {
        $this->assertEquals($expected_error, $result->getErrorID());
        $this->assertEquals($expected_tsize, $result->getTopicSize());
        $this->assertEquals($expected_nsize, $result->getNewsSize());
        $this->assertEquals(count($result->getResult()), $expected_nsize);
        foreach ($result->getResult() as $r) {
            $expected_keys = array('newsid', 'datetime', 'source', 'headline', 'storyurl');
            foreach ($expected_keys as $k) {
                $this->assertTrue(isset($r[$k]), 'getHeadlines entry is missing the value for ' . $k . '. Dump of result row: ' . print_r($r, true));
                $this->assertTrue(strlen($r[$k]) > 0, 'Value for a getHeadlines entry is empty: ' . print_r($r, true));
            }
        }
    }

}
