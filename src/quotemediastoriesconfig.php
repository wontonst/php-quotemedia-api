<?php

/**
 * Customization of story API requests. 
 */
class QuoteMediaStoriesConfig {

    private $topics; ///< array of topics
    private $perTopic = NULL; ///< per topic field from 1-QuoteMediaConst::MAX_TOPICS
    private $lastId = NULL; ///< id of the last news article to display
    private $start = NULL; ///< start boundary for news articles
    private $end = NULL; ///< end boundary for news articles
    private $src = NULL; ///< source to pull from (mutex with $nosrc)
    private $noSrc = NULL; ///< source to exclude (mutex with $src)
    private $newslang; ///< language of articles to get

    public function __construct() {
        $this->$topics = 'SHOWALLNEWS';
        $this->$newslang = 'en';
    }

    public function setTopics($topic) {
        $this->$topics = $topic;
    }

    private function buildTopicsStr() {
        if (is_array($this->topic)) {
            $this->topics = $this->csvify($this->topics);
        }
        return '&topics=' . $this->topics;
    }

    public function setPerTopic($pt) {
        $this->perTopic = $pt;
    }

    private function buildPerTopicStr(&$builder) {
        if ($this->perTopic == NULL) {
            return '';
        }
        if (!ctype_digit($this->perTopic)) {
            $builder->setError(QuoteMediaError::INVALID_PER_TOPIC);
            return '';
        }
        return '&perTopic=' . $this->perTopic;
    }

    public function setLastId($id) {
        $this->lastId = $id;
    }

    private function buildLastIdStr(&$builder) {
        if ($this->lastId == NULL) {
            return '';
        }
        if (!ctype_digit($this->perTopic)) {
            $builder->setError(QuoteMediaError::INVALID_LAST_ID);
            return '';
        }
        return '&perTopic=' . $this->perTopic;
    }

    public function setStart($start) {
        $this->start = $start;
    }

    private function buildStartStr(&$builder) {
        if ($this->start == NULL) {
            return '';
        }
        if (!preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $this->start)) {
            $builder->setError(QuoteMediaError::INVALID_START_DATE);
            return '';
        }
        return '&start=' . $this->start;
    }

    public function setEnd($end) {
        $this->end = $end;
    }

    private function buildEndStr(&$builder) {
        if ($this->end == NULL) {
            return '';
        }
        if (!preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $this->end)) {
            $builder->setError(QuoteMediaError::INVALID_START_DATE);
            return '';
        }
        return '&end=' . $this->end;
    }

    public function setSrc() {
        $this->src = $src;
    }

    public function setNoSrc($nosrc) {
        $this->noSrc = $nosrc;
    }

    private function buildSrcStr(&$builder) {
        if ($this->src != NULL && $this->noSrc != NULL) {
            $builder->setError(QuoteMediaError::SRC_NO_SRC_CONFLICT);
            return '';
        }
        if ($this->src != NULL) {
            return '&src=' . $this->src;
        }
        if ($this->noSrc != NULL) {
            return '&noSrc=' . $this->noSrc;
        }
        return '';
    }

    public function setLanguage($lang) {
        $this->newslang = $lang;
    }

    private function buildNewsLangStr($builder) {
        if (!$this->newslang == NULL) {
            return '';
        }
        if (is_array($this->newslang)) {
            $out = '';
            foreach ($this->newslang as $l) {
                $out.=QuoteMediaConst::langIdToStr($l);
            }
            return '&newslang=' . $out;
        }
        return '&newslang=' . $QuoteMediaConst::langIdToStr($this->newslang);
    }

    public function generateGet(&$builder) {

        $getstr = QuoteMediaConst::URL_ROOT;

        $buildF = array('Topics', 'PerTopic', 'LastId', 'Start', 'End', 'Src', 'NewsLang');

        foreach ($buildF as $function) {
            $getstr .= $this->$function($builder);
        }
        return $getstr;
    }

}

?>