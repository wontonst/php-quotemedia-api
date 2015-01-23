<?php

/**
 * 
 */
class QuoteMediaStoriesResultBuilder extends QuoteMediaResultBuilder {

    public function __construct() {
        parent::__construt();
    }

    public function processXml() {
        $json = QuoteMediaResultBuilder::xml2json($this->getXml());
        var_dump($json);
    }

}

?>