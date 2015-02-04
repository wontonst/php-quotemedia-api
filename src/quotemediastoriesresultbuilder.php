<?php

/**
 * 
 */
class QuoteMediaStoriesResultBuilder extends QuoteMediaResultBuilder {

    private $topicSize; ///< number of topics returned
    private $newsSize; ///< number of articles returned

    public function __construct() {
        parent::__construct();
        $this->size = 0;
    }

    public function getTopicSize() {
        return $this->topicSize;
    }

    public function getNewsSize() {
        return $this->newsSize;
    }

    public function processXml() {
        $json = QuoteMediaResultBuilder::xml2json($this->getXml());
        //print_r($json);
        $this->topicSize = $json['topiccount'];
        $this->newsSize = 0;
        $result = array();
        if ($this->topicSize == 1) {
            $result[] = $this->processTopic($json['new']);
        } else {
            foreach ($json['news'] as $news) {
                $result[] = $this->processTopic($news);
            }
        }
        $this->setResult($result);
        //print_r($this);
    }

    private function processTopic($news) {
        $this->newsSize += $news['newsitemcount'];
        $result = array();
        $topicstring = $news['@attributes']['topicstring'];
        $topicinfo = $news['topicinfo'];
        foreach ($news['newsitem'] as $item) {
            $row = $item['@attributes'];
            unset($item['@attributes']);
            $row = array_merge($row, $item);
            $result[] = $row;
        }
        return array('topicstring' => $topicstring, 'topicinfo' => $topicinfo, 'newsitem' => $result);
    }

    public function build() {
        return new QuoteMediaStoriesResult($this);
    }

}

?>