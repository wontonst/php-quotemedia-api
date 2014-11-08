**Disclaimer**: this library is still in development. There are weird behaviors like closing price=last price because it's ported from application specific code. 

# QuoteMedia API PHP Library

This library is intended to make QuoteMedia API calls easier to manage.

## Getting Started
Download/clone this repo from Github.

To start using this library, you must first include the autoload.php in the root directory.

If you want thorough documentation, the code is in doxygen/javadoc style so you can generate it yourself if you wish.

### Stocks

This library includes code for using QuoteMedia's stock APIs.

#### QuoteMediaStocks Class

If you want quick requests for basic information about stocks, you can use the QuoteMediaStocks class. Note that you should use this class when you want information on less than 50 tickers at once, since that's the API call limit. To go beyond this limit, skip to the next section.

Here's a basic runthrough.

<pre>
$webmaster_id = XXXXX; // REPLACE this with your webmaster ID
$tickers = array('GOOG');//any size array up to maximum per API call
$api = new QuoteMediaStocks($webmaster_id);
$quotes = $api->getQuotes($tickers);
var_dump($quotes);
</pre>

The var dump will show something like this

<pre>
array(1) {
  [0]=>
  array(29) {
    ["symbol"]=>
    string(4) "GOOG"
    ["exchange"]=>
    string(3) "NGS"
    ["longname"]=>
    string(10) "Google Inc"
    ["shortname"]=>
    string(4) "GOOG"
    ["last"]=>
    string(6) "541.01"
    ["change"]=>
    string(5) "-1.03"
    ["changepercent"]=>
    string(9) "-0.190023"
    ["tick"]=>
    string(1) "0"
    ["open"]=>
    string(6) "546.21"
    ["high"]=>
    string(6) "546.21"
    ["low"]=>
    string(6) "538.67"
    ["prevclose"]=>
    string(6) "542.04"
    ["bid"]=>
    string(6) "540.75"
    ["ask"]=>
    string(6) "541.40"
    ["bidsize"]=>
    string(4) "1000"
    ["asksize"]=>
    string(3) "100"
    ["rawbidsize"]=>
    string(2) "10"
    ["rawasksize"]=>
    string(1) "1"
    ["tradevolume"]=>
    string(5) "20951"
    ["sharevolume"]=>
    string(7) "1628014"
    ["vwap"]=>
    string(10) "540.759786"
    ["lasttradedatetime"]=>
    string(25) "2014-11-07T16:15:00-05:00"
    ["sharesoutstanding"]=>
    string(9) "678365654"
    ["marketcap"]=>
    string(12) "367002602471"
    ["eps"]=>
    string(5) "19.07"
    ["peratio"]=>
    string(5) "28.40"
    ["pbratio"]=>
    string(5) "3.714"
    ["week52high"]=>
    string(6) "604.83"
    ["week52low"]=>
    string(6) "502.80"
  }
}
</pre>

Other calls are

<pre>
$profiles = $api->getProfiles($tickers);
$fundamentals = $api->getFundamentals($tickers);
$keyratios = $api->getKeyRatios($tickers);
</pre>

If you want it to return a associative map (SYMBOL => RESULT ARRAY) just add second parameter true, ie

<pre>
$quotes = $api->getQuotes($ticker,true);
</pre>

#### QuoteMediaBatcher Class

Note that in the previous section you can only retrieve each section separately and you are limited to 50 tickers at a time in getProfiles. This is due to limitations on the API itself. To bypass these limitations you can use the QuoteMediaBatcher class.

<pre>
$webmaster_id = 000000;//user inputs webmaster id 
$input = array( 'GOOG');
$api = new QuoteMediaBatcher($webmaster_id);
$result = $api->getAll($input);
var_dump($result);
</pre>

The var dump would look something like this

<pre>
array(1) {
  [0]=>
  array(78) {
    ["symbol"]=>
    string(4) "GOOG"
    ["exchange"]=>
    string(3) "NGS"
    ["longname"]=>
    string(10) "Google Inc"
    ["shortname"]=>
    string(4) "GOOG"
    ["last"]=>
    string(6) "541.01"
    ["change"]=>
    string(5) "-1.03"
    ["changepercent"]=>
    string(9) "-0.190023"
    ["tick"]=>
    string(1) "0"
    ["open"]=>
    string(6) "546.21"
    ["high"]=>
    string(6) "546.21"
    ["low"]=>
    string(6) "538.67"
    ["prevclose"]=>
    string(6) "542.04"
    ["bid"]=>
    string(6) "540.75"
    ["ask"]=>
    string(6) "541.40"
    ["bidsize"]=>
    string(4) "1000"
    ["asksize"]=>
    string(3) "100"
    ["rawbidsize"]=>
    string(2) "10"
    ["rawasksize"]=>
    string(1) "1"
    ["tradevolume"]=>
    string(5) "20952"
    ["sharevolume"]=>
    string(7) "1629159"
    ["vwap"]=>
    string(10) "540.759786"
    ["lasttradedatetime"]=>
    string(25) "2014-11-07T16:15:00-05:00"
    ["sharesoutstanding"]=>
    string(9) "678365654"
    ["marketcap"]=>
    string(12) "367701319094"
    ["eps"]=>
    string(5) "19.07"
    ["peratio"]=>
    string(5) "28.40"
    ["pbratio"]=>
    string(5) "3.721"
    ["week52high"]=>
    string(6) "604.83"
    ["week52low"]=>
    string(6) "502.80"
    ["day21movingavg"]=>
    string(6) "540.56"
    ["day50movingavg"]=>
    string(7) "562.789"
    ["day200movingavg"]=>
    string(7) "559.559"
    ["avg10dayvolume"]=>
    string(7) "1565459"
    ["avg30dayvolume"]=>
    string(7) "2079834"
    ["avg90dayvolume"]=>
    string(7) "1691702"
    ["alpha"]=>
    string(9) "-0.005975"
    ["beta"]=>
    string(8) "0.307033"
    ["r2"]=>
    string(8) "0.023768"
    ["stddev"]=>
    string(8) "0.037244"
    ["periods"]=>
    string(1) "8"
    ["day21ema"]=>
    string(7) "548.389"
    ["day50ema"]=>
    string(7) "557.005"
    ["day200ema"]=>
    string(7) "561.081"
    ["sdate"]=>
    string(10) "2014-10-15"
    ["sshares"]=>
    string(7) "2105138"
    ["sratio"]=>
    string(5) "1.091"
    ["spercent"]=>
    string(4) "0.30"
    ["adrratio"]=>
    string(1) "0"
    ["ptbratio"]=>
    string(4) "4.68"
    ["pcfratio"]=>
    string(5) "20.60"
    ["pfcfratio"]=>
    string(5) "53.80"
    ["address1"]=>
    string(25) "1600 Amphitheatre Parkway"
    ["address2"]=>
    array(0) {
    }
    ["city"]=>
    string(13) "Mountain View"
    ["state"]=>
    string(2) "CA"
    ["country"]=>
    string(3) "USA"
    ["postcode"]=>
    string(5) "94043"
    ["telephone"]=>
    string(15) "+1 650 253-0000"
    ["facisimile"]=>
    string(15) "+1 650 253-0001"
    ["website"]=>
    string(21) "http://www.google.com"
    ["email"]=>
    string(17) "irgoog@google.com"
    ["ceo"]=>
    string(10) "Larry Page"
    ["employees"]=>
    string(5) "55030"
    ["issuetype"]=>
    string(2) "CS"
    ["sectype"]=>
    string(3) "EQS"
    ["isocfi"]=>
    array(0) {
    }
    ["auditor"]=>
    string(17) "Ernst & Young LLP"
    ["lastAudit"]=>
    string(2) "UQ"
    ["indices"]=>
    array(1) {
      ["index"]=>
      array(5) {
        [0]=>
        array(2) {
          ["indexsymbol"]=>
          string(7) "^DJUSNS"
          ["indexname"]=>
          string(29) "Dow Jones U.S. Internet Index"
        }
        [1]=>
        array(2) {
          ["indexsymbol"]=>
          string(4) "^OEX"
          ["indexname"]=>
          string(7) "S&P 100"
        }
        [2]=>
        array(2) {
          ["indexsymbol"]=>
          string(4) "^SPX"
          ["indexname"]=>
          string(7) "S&P 500"
        }
        [3]=>
        array(2) {
          ["indexsymbol"]=>
          string(11) "^QM31168144"
          ["indexname"]=>
          string(30) "Internet Content & Information"
        }
        [4]=>
        array(2) {
          ["indexsymbol"]=>
          string(5) "^GDOW"
          ["indexname"]=>
          string(20) "The Global Dow (USD)"
        }
      }
    }
    ["sector"]=>
    string(10) "Technology"
    ["industry"]=>
    string(12) "Online Media"
    ["qmid"]=>
    string(8) "31168144"
    ["qmdescription"]=>
    string(30) "Internet Content & Information"
    ["cik"]=>
    string(7) "1288776"
    ["naics"]=>
    string(6) "519130"
    ["sics"]=>
    array(1) {
      ["sic"]=>
      string(4) "7375"
    }
    ["shortdescription"]=>
    string(48) "Offers advertising and internet search solutions"
    ["longdescription"]=>
    string(157) "Google Inc is a web search and online advertising company that offers search, advertising, operating systems and platforms, enterprise and hardware products."
  }
}
</pre>

### Articles
N/A TBA
