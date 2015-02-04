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
        $builder = new QuoteMediaStoriesResultBuilder();
        $url = QuoteMediaConst::URL_ROOT.'getHeadlines.xml?webmasterId='.$this->getWebmasterId().$config->generateGetParam($builder);
	//var_dump($url);
	//var_dump($config);
        $this->callApi($url, $builder);
        $builder->processXml();
        return $builder->build();
    }
    public function getHeadlinesStory(){
        
        //http://app.quotemedia.com/data/getHeadlinesStory.xml?topic=msft&webmasterId=XXXX
    }
}

?>