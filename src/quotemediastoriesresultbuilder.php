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
	if($this->topicSize == 1){
        $this->newsSize = $json['news']['newsitemcount'] + 0;
	}
	else{
	  $this->newsSize = 0;	  
foreach($json['news'] as $news){
  $this->newsSize += $news['newsitemcount'];
	  }
	}
        $result = array();
        foreach ($json['news']['newsitem'] as $item) {
            $row = $item['@attributes'];
            unset($item['@attributes']);
            $row = array_merge($row, $item);
            $result[] = $row;
        }
        $this->setResult($result);
        //print_r($this);
    }

    public function build() {
      return new QuoteMediaStoriesResult($this);
    }

}

?>