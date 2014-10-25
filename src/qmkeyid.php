<?php

/**
 * Schema for parsing XML and typecasting appropriately.
 */
class GetQuotesSchema {

    private static $schema = null;

    public function parseXML(&$xml) {
        if ($GetQuotesSchema::$schema === null) {
            GetQuotesSchema::instantiate();
        }
    }

    private function instantiate() {
        $this->schema = array(
          'quote'=> array(
              'symbol' => array()
                  ),  
        );
    }

    /*     * **Result Key Identifiers*** */

    /* getQuotes */

    const TYPE = 28;
    const DATE = 1;
    const COMPANY_NAME = 0;
    const MARKET_CAP = 2;
    const SYMBOL = 3;
    const EXCHANGE = 26;
    const LAST_PRICE = 27;
    const CHANGE = 4;
    const CHANGE_PERCENT = 5;
    const CLOSING_PRICE = 6;
    const OPENING_PRICE = 7;
    const DAY_HIGH = 12;
    const DAY_LOW = 11;
    const PREV_DAY_CLOSE = 29;
    const PE_RATIO = 8;
    const OPEN_PRICE = 9;
    const CLOSE_PRICE = 10;
    const DAY_VOLUME = 13; ///< volume of stocks traded in a day
    const DAY_TRADE_VOLUME = 14; ///< number of trades in a day
    const SHARES_OUTSTANDING = 15;

    /* getProfiles */
    const COMPANY_DESCRIPTION = 16;
    const CEO_NAME = 17;
    const NUM_EMPLOYEES = 18;
    const COMPANY_CITY = 19;
    const COMPANY_STATE = 20;

    /* getFundamentals */
    const AVG_VOLUME = 21;
    const WEEK_52_HIGH = 22;
    const WEEK_52_LOW = 23;
    const DAY_50_MA = 24;
    const DAY_200_MA = 25;

}

?>