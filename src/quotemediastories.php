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

    public function getHeadlines($config = null) {
        if ($config == NULL) {
            $config = new QuoteMediaStoriesConfig();
        }
        $builder = new QuoteMediaHeadlinesResultBuilder();
        $url = QuoteMediaConst::URL_ROOT . 'getHeadlines.xml?webmasterId=' . $this->getWebmasterId() . $config->generateGetParam($builder);
        //var_dump($url);
        //var_dump($config);
        $this->callApi($url, $builder);
        $builder->processXml();
        return $builder->build();
    }

    public function getStories($config = null) {
        if ($config == NULL) {
            $config = new QuoteMediaStoriesConfig();
        }
        $builder = new QuoteMediaStoriesResultBuilder();
        $url = QuoteMediaConst::URL_ROOT . 'getHeadlinesStory.xml?webmasterId=' . $this->getWebmasterId() . $config->generateGetParam($builder);
        $this->callApi($url, $builder);
        $builder->processXml();
        return $builder->build();
    }

}

?>