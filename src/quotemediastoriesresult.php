<?php

/**
 * 
 */
class QuoteMediaStoriesResult extends QuoteMediaResult {

    private $topicSize; ///< number of topics returned
    private $newsSize; ///< number of articles returned

    public function __construct($builder) {
        parent::__construct($builder);
        $this->topicSize = $builder->getTopicSize();
        $this->newsSize = $builder->getNewsSize();
    }
    public function getTopicSize() {
        return $this->topicSize;
    }

    public function getNewsSize() {
        return $this->newsSize;
    }
}

?>