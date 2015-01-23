<?php

/**
 * TODO: Custom keys for result
 */
class QuoteMediaStories extends QuoteMediaBase {

    /**
     * Create a new QuoteMediaStories class.
     * @param integer $id user webmaster id.
     */
    public function __construct($id) {
        parent::__construct($id);
    }

    private function callStories() {
        
    }

    public function getHeadlines($config = null) {
        if ($config == NULL) {
            $config = new QuoteMediaStoriesConfig();
        }
        $builder = new QuoteMediaStoriesResultsBuilder();
        $url = $config->generateGet($builder);
        
    }

}

?>