<?php

class QuoteMediaBatcherTest extends QuoteMediaStocksTester {

    protected function setUp() {
        $api = new QuoteMediaBatcher(TEST_WEBMASTER_ID);
        $this->setUpInputs();
    }

    private function validate() {
        $fields = array(
            'symbol',
            'exchange',
            'longname',
            'shortname',
            'shortdescription',
            'longdescription',
            'address1',
            'address2',
            'city',
            'state',
            'country',
            'postcode',
            'telephone',
            'facisimile',
            'website',
            'email',
            'ceo',
            'employees',
            'issuetype',
            'isocfi',
            'auditor',
            'lastAudit',
            'marketcap',
            'sector',
            'industry',
            'qmid',
            'qmdescription',
            'cik',
            'naics',
            'sics',
        );
    }

    public function testGetArray() {
        
    }

    public function testGetArrayMultiple() {
        
    }

    public function testGetAssoc() {
        
    }

    public function testGetAssocMultiple() {
        
    }

}

?>