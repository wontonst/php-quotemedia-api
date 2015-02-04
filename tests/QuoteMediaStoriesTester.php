<?php

abstract class QuoteMediaStoriesTester extends PHPUnit_Framework_TestCase {

    protected function setUpInputs() {
        $this->stdTopics = array(
            'GOOG', 'AAPL',
        );
        $this->perTopic = array(5, 50, 100, 150, 250);
    }

    private function verify($result, $expected_error, $expected_tsize, $expected_nsize, $expected_keys, $function_name) {
        $this->assertEquals($expected_error, $result->getErrorID(), 'Expected error ' . QuoteMediaError::IDtoError($expected_error) . ' but instead got ' . $result->getError());
        $this->assertEquals($expected_tsize, $result->getTopicSize());
        $this->assertEquals($expected_nsize, $result->getNewsSize());

        $res = $result->getResult();
        $this->assertEquals(count($res), $expected_tsize);
        foreach ($res as $row) {//each topic row
            foreach ($row['newsitem'] as $r) {//each headline row
                foreach ($expected_keys as $k) {//each value of the headline entry
                    $this->assertTrue(isset($r[$k]), $function_name . ' entry is missing the value for ' . $k . '. Dump of result row: ' . print_r($r, true));
                    $this->assertTrue(strlen($r[$k]) > 0, 'Value for a ' . $function_name . ' entry is empty: ' . print_r($r, true));
                }
            }
        }
    }

    protected function verifyHeadlines($result, $expected_error, $expected_tsize, $expected_nsize) {
        $expected_keys = array('newsid', 'datetime', 'source', 'headline', 'storyurl');
        $this->verify($result, $expected_error, $expected_tsize, $expected_nsize, $expected_keys, 'getHeadlines');
    }

    protected function verifyStories($result, $expected_error, $expected_tsize, $expected_nsize) {
        $expected_keys = array('newsid', 'datetime', 'source', 'headline', 'story');
        $this->verify($result, $expected_error, $expected_tsize, $expected_nsize, $expected_keys, 'getStories');
    }

}
