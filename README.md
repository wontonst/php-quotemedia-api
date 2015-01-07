
<!-- DO NOT EDIT README.MD DIRECTLY, YOUR CHANGES WILL BE WIPED OUT. PLEASE EDIT generate_readme.php INSTEAD! -->

# QuoteMedia API PHP Library

This library is intended to make QuoteMedia API calls easier to manage.

## Getting Started
Download/clone this repo from Github.

To start using this library, you must first include the autoload.php in the root directory.

## Stocks

This library includes code for using QuoteMedia's stock APIs.

### QuoteMediaStocks Class

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
  [0] =>
  array(26) {
    'symbol' =>
    string(4) "GOOG"
    'exchange' =>
    string(3) "NGS"
    'longname' =>
    string(10) "Google Inc"
    'shortname' =>
    string(4) "GOOG"
    'last' =>
    string(6) "537.50"
    'change' =>
    string(4) "2.67"
    'changepercent' =>
    string(8) "0.499224"
    'open' =>
    string(6) "541.61"
    'high' =>
    string(6) "542.14"
    'low' =>
    string(6) "536.56"
    'prevclose' =>
    string(6) "534.83"
    'bidsize' =>
    string(1) "0"
    'asksize' =>
    string(1) "0"
    'rawbidsize' =>
    string(1) "0"
    'rawasksize' =>
    string(1) "0"
    'tradevolume' =>
    string(5) "24093"
    'sharevolume' =>
    string(7) "2218249"
    'vwap' =>
    string(10) "538.767956"
    'lasttradedatetime' =>
    string(25) "2014-11-21T16:45:04-05:00"
    'sharesoutstanding' =>
    string(9) "678365654"
    'marketcap' =>
    string(12) "364621539025"
    'eps' =>
    string(5) "19.07"
    'peratio' =>
    string(5) "28.00"
    'pbratio' =>
    string(4) "3.69"
    'week52high' =>
    string(6) "604.83"
    'week52low' =>
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

#### Function Reference
+ getQuotes (array, bool) - function id (0) : calls QuoteMedia's getQuotes. Can accept up to 100 symbols at a time.
+ getProfiles (array, bool) - function id (1) : calls QuoteMedia's getProfiles. Can accept up to 50 symbols at a time.
+ getFundamentals (array, bool) - function id (2) : calls QuoteMedia's getFundamentals. Can accept up to 50 symbols at a time.
+ getKeyRatios (array, bool) - function id (3) : calls QuoteMedia's getKeyRatios. Can accept up to 1 symbols at a time.

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
  [0] =>
  array(110) {
    'symbol' =>
    string(4) "GOOG"
    'exchange' =>
    string(3) "NGS"
    'longname' =>
    string(10) "Google Inc"
    'shortname' =>
    string(4) "GOOG"
    'last' =>
    string(6) "537.50"
    'change' =>
    string(4) "2.67"
    'changepercent' =>
    string(8) "0.499224"
    'open' =>
    string(6) "541.61"
    'high' =>
    string(6) "542.14"
    'low' =>
    string(6) "536.56"
    'prevclose' =>
    string(6) "534.83"
    'bidsize' =>
    string(1) "0"
    'asksize' =>
    string(1) "0"
    'rawbidsize' =>
    string(1) "0"
    'rawasksize' =>
    string(1) "0"
    'tradevolume' =>
    string(5) "24093"
    'sharevolume' =>
    string(7) "2218249"
    'vwap' =>
    string(10) "538.767956"
    'lasttradedatetime' =>
    string(25) "2014-11-21T16:45:04-05:00"
    'sharesoutstanding' =>
    string(9) "678365654"
    'marketcap' =>
    string(12) "364621539025"
    'eps' =>
    string(5) "19.07"
    'peratio' =>
    string(4) "28.0"
    'pbratio' =>
    string(4) "3.69"
    'week52high' =>
    string(6) "604.83"
    'week52low' =>
    string(6) "502.80"
    'address1' =>
    string(25) "1600 Amphitheatre Parkway"
    'address2' =>
    array(0) {
    }
    'city' =>
    string(13) "Mountain View"
    'state' =>
    string(2) "CA"
    'country' =>
    string(3) "USA"
    'postcode' =>
    string(5) "94043"
    'telephone' =>
    string(15) "+1 650 253-0000"
    'facisimile' =>
    string(15) "+1 650 253-0001"
    'website' =>
    string(21) "http://www.google.com"
    'email' =>
    string(17) "irgoog@google.com"
    'ceo' =>
    string(10) "Larry Page"
    'employees' =>
    string(5) "55030"
    'issuetype' =>
    string(2) "CS"
    'sectype' =>
    string(3) "EQS"
    'isocfi' =>
    array(0) {
    }
    'auditor' =>
    string(17) "Ernst & Young LLP"
    'lastAudit' =>
    string(2) "UQ"
    'indices' =>
    array(1) {
      'index' =>
      array(5) {
        ...
      }
    }
    'sector' =>
    string(10) "Technology"
    'industry' =>
    string(12) "Online Media"
    'qmid' =>
    string(8) "31168144"
    'qmdescription' =>
    string(30) "Internet Content & Information"
    'cik' =>
    string(7) "1288776"
    'naics' =>
    string(6) "519130"
    'sics' =>
    array(1) {
      'sic' =>
      string(4) "7375"
    }
    'shortdescription' =>
    string(48) "Offers advertising and internet search solutions"
    'longdescription' =>
    string(157) "Google Inc is a web search and online advertising company that offers search, advertising, operating systems and platforms, enterprise and hardware products."
    'day21movingavg' =>
    string(7) "544.867"
    'day50movingavg' =>
    string(7) "554.425"
    'day200movingavg' =>
    string(7) "558.378"
    'avg10dayvolume' =>
    string(7) "1468849"
    'avg30dayvolume' =>
    string(7) "1997116"
    'avg90dayvolume' =>
    string(7) "1687450"
    'alpha' =>
    string(9) "-0.004902"
    'beta' =>
    string(8) "0.088778"
    'r2' =>
    string(8) "0.001966"
    'stddev' =>
    string(8) "0.038205"
    'periods' =>
    string(1) "8"
    'day21ema' =>
    string(6) "543.13"
    'day50ema' =>
    string(7) "551.308"
    'day200ema' =>
    string(6) "559.03"
    'sdate' =>
    string(10) "2014-10-31"
    'sshares' =>
    string(7) "2614149"
    'sratio' =>
    string(5) "1.136"
    'spercent' =>
    string(4) "0.40"
    'adrratio' =>
    string(1) "0"
    'ptbratio' =>
    string(4) "4.62"
    'pcfratio' =>
    string(5) "20.40"
    'pfcfratio' =>
    string(5) "53.10"
    'revenue' =>
    string(11) "64756000000"
    'revenuepershare' =>
    string(8) "95.45885"
    'revenue3years' =>
    string(5) "19.16"
    'revenue5years' =>
    string(5) "23.38"
    'quickratio' =>
    string(3) "4.0"
    'currentratio' =>
    string(3) "4.5"
    'longtermdebttocapital' =>
    string(4) "0.03"
    'totaldebttoequity' =>
    string(4) "0.05"
    'intcoverage' =>
    string(5) "159.7"
    'leverageratio' =>
    string(3) "1.3"
    'returnonequity' =>
    string(5) "14.37"
    'returnoncapital' =>
    string(5) "10.81"
    'returnonassets' =>
    string(3) "4.1"
    'pehighlast5years' =>
    array(0) {
    }
    'pelowlast5years' =>
    array(0) {
    }
    'pricetosales' =>
    string(8) "5.602729"
    'pricetobook' =>
    string(4) "3.67"
    'pricetotangiblebook' =>
    string(4) "4.62"
    'pricetocashflow' =>
    string(4) "20.4"
    'pricetofreecash' =>
    string(4) "53.1"
    'dividendrate' =>
    array(0) {
    }
    'dividendyield' =>
    array(0) {
    }
    'dividend3years' =>
    array(0) {
    }
    'dividend5years' =>
    array(0) {
    }
    'paymenttype' =>
    array(0) {
    }
    'exdividenddate' =>
    array(0) {
    }
    'grossmargin' =>
    string(4) "55.4"
    'ebitmargin' =>
    string(4) "24.7"
    'ebitdamargin' =>
    string(4) "32.0"
    'pretaxprofitmargin' =>
    string(4) "29.6"
    'profitmargincont' =>
    string(4) "19.9"
    'profitmargintot' =>
    string(5) "20.17"
    'assetsturnover' =>
    string(3) "0.6"
    'invoiceturnover' =>
    string(5) "112.4"
    'receivablesturnover' =>
    string(3) "7.0"
  }
}
</pre>

If you want to grab only a few sections instead of all, you can use the get() function, like so

<pre>
$webmaster_id = 000000;//user inputs webmaster id 
$input = array( 'GOOG');
$api = new QuoteMediaBatcher($webmaster_id);
$result = $api->get($input,array(QuoteMediaConst::GET_KEY_RATIOS,QuoteMediaConst::GET_QUOTES));
var_dump($result);
</pre>

The var dump would look something like

<pre>
array(1) {
  [0] =>
  array(61) {
    'symbol' =>
    string(4) "GOOG"
    'exchange' =>
    string(3) "NGS"
    'longname' =>
    string(10) "Google Inc"
    'shortname' =>
    string(4) "GOOG"
    'revenue' =>
    string(11) "64756000000"
    'revenuepershare' =>
    string(8) "95.45885"
    'revenue3years' =>
    string(5) "19.16"
    'revenue5years' =>
    string(5) "23.38"
    'quickratio' =>
    string(3) "4.0"
    'currentratio' =>
    string(3) "4.5"
    'longtermdebttocapital' =>
    string(4) "0.03"
    'totaldebttoequity' =>
    string(4) "0.05"
    'intcoverage' =>
    string(5) "159.7"
    'leverageratio' =>
    string(3) "1.3"
    'returnonequity' =>
    string(5) "14.37"
    'returnoncapital' =>
    string(5) "10.81"
    'returnonassets' =>
    string(3) "4.1"
    'peratio' =>
    string(5) "28.00"
    'pehighlast5years' =>
    array(0) {
    }
    'pelowlast5years' =>
    array(0) {
    }
    'pricetosales' =>
    string(8) "5.602729"
    'pricetobook' =>
    string(4) "3.67"
    'pricetotangiblebook' =>
    string(4) "4.62"
    'pricetocashflow' =>
    string(4) "20.4"
    'pricetofreecash' =>
    string(4) "53.1"
    'dividendrate' =>
    array(0) {
    }
    'dividendyield' =>
    array(0) {
    }
    'dividend3years' =>
    array(0) {
    }
    'dividend5years' =>
    array(0) {
    }
    'paymenttype' =>
    array(0) {
    }
    'exdividenddate' =>
    array(0) {
    }
    'grossmargin' =>
    string(4) "55.4"
    'ebitmargin' =>
    string(4) "24.7"
    'ebitdamargin' =>
    string(4) "32.0"
    'pretaxprofitmargin' =>
    string(4) "29.6"
    'profitmargincont' =>
    string(4) "19.9"
    'profitmargintot' =>
    string(5) "20.17"
    'assetsturnover' =>
    string(3) "0.6"
    'invoiceturnover' =>
    string(5) "112.4"
    'receivablesturnover' =>
    string(3) "7.0"
    'last' =>
    string(6) "537.50"
    'change' =>
    string(4) "2.67"
    'changepercent' =>
    string(8) "0.499224"
    'open' =>
    string(6) "541.61"
    'high' =>
    string(6) "542.14"
    'low' =>
    string(6) "536.56"
    'prevclose' =>
    string(6) "534.83"
    'bidsize' =>
    string(1) "0"
    'asksize' =>
    string(1) "0"
    'rawbidsize' =>
    string(1) "0"
    'rawasksize' =>
    string(1) "0"
    'tradevolume' =>
    string(5) "24093"
    'sharevolume' =>
    string(7) "2218249"
    'vwap' =>
    string(10) "538.767956"
    'lasttradedatetime' =>
    string(25) "2014-11-21T16:45:04-05:00"
    'sharesoutstanding' =>
    string(9) "678365654"
    'marketcap' =>
    string(12) "364621539025"
    'eps' =>
    string(5) "19.07"
    'pbratio' =>
    string(4) "3.69"
    'week52high' =>
    string(6) "604.83"
    'week52low' =>
    string(6) "502.80"
  }
}
</pre>

Again, you can choose to return an associative map instead of an array using an optional parameter, ie

<pre>
$api->getAll($input,true);
$result = $api->get($input,array(QuoteMediaConst::GET_KEY_RATIOS,QuoteMediaConst::GET_QUOTES),true);
</pre>

## Articles
N/A TBA


## Error Codes
+ 0 : No error has occurred.
+ -1 : Could not access API due to HTTP error
+ -2 : You did not pass an array on your last function call. Functions like getQuote expect an array as the first parameter.
+ -3 : getQuotes cannot request more than 100 symbols at a time.
+ -4 : getProfiles cannot request more than 50 symbols at a time.
+ -5 : getFundamentals cannot request more than 50 symbols at a time.
+ -6 : getKeyRatios cannot request more than 1 symbols at a time.
+ -7 : Result from QuoteMedia is not a parsable XML file.s
+ -8 : Expected a symbols string but was not passed a string
+ -9 : Symbol contains invalid characters
+ -10 : Invalid function identifier

# Contributing
Contributions can be submitted right here on Github through a pull request. 

All functions should be documented in doxygen/javadoc style. The current code the code is in doxygen/javadoc style so you can generate it yourself for reference.

Before any pull request can be submitted, be sure to run the unit tests. You can use the run_tests.sh script if you don't know phpunit. Don't forget to add your webmaster ID to testsautoload.php before running the test or it will give you a syntax error. Also note that some tests validates data that is only available during the weekday, so be weary of running tests over the weekend...
