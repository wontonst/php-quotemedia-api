<?php

/**
 * 
 */
class QuoteMediaStoriesResultsBuilder extends QuoteMediaResultsBuilder {

    public function processXml() {
        $json = QuoteMediaResultBuilder::xml2json($this->getXml());
        var_dump($json);
    }

}

?>