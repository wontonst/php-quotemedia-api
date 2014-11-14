
<!-- DO NOT EDIT README.MD DIRECTLY, YOUR CHANGES WILL BE WIPED OUT. PLEASE EDIT generate_readme.php INSTEAD! -->

# QuoteMedia API PHP Library

This library is intended to make QuoteMedia API calls easier to manage.

## Getting Started
Download/clone this repo from Github.

To start using this library, you must first include the autoload.php in the root directory.

If you want thorough documentation, the code is in doxygen/javadoc style so you can generate it yourself if you wish.

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
  array(29) {
    'symbol' =>
    string(4) "GOOG"
    'exchange' =>
    string(3) "NGS"
    'longname' =>
    string(10) "Google Inc"
    'shortname' =>
    string(4) "GOOG"
    'last' =>
    string(6) "544.02"
    'change' =>
    string(5) "-1.36"
    'changepercent' =>
    string(9) "-0.249367"
    'tick' =>
    string(2) "-1"
    'open' =>
    string(6) "546.68"
    'high' =>
    string(6) "546.68"
    'low' =>
    string(6) "542.15"
    'prevclose' =>
    string(6) "545.38"
    'bid' =>
    string(6) "544.01"
    'ask' =>
    string(6) "544.05"
    'bidsize' =>
    string(3) "100"
    'asksize' =>
    string(3) "100"
    'rawbidsize' =>
    string(1) "1"
    'rawasksize' =>
    string(1) "1"
    'tradevolume' =>
    string(5) "12969"
    'sharevolume' =>
    string(6) "883866"
    'vwap' =>
    string(10) "544.072853"
    'lasttradedatetime' =>
    string(25) "2014-11-14T15:30:54-05:00"
    'sharesoutstanding' =>
    string(9) "678365654"
    'marketcap' =>
    string(12) "369044483089"
    'eps' =>
    string(5) "19.07"
    'peratio' =>
    string(5) "28.60"
    'pbratio' =>
    string(5) "3.735"
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
  array(113) {
    'symbol' =>
    string(4) "GOOG"
    'exchange' =>
    string(3) "NGS"
    'longname' =>
    string(10) "Google Inc"
    'shortname' =>
    string(4) "GOOG"
    'last' =>
    string(6) "544.02"
    'change' =>
    string(5) "-1.36"
    'changepercent' =>
    string(9) "-0.249367"
    'tick' =>
    string(2) "-1"
    'open' =>
    string(6) "546.68"
    'high' =>
    string(6) "546.68"
    'low' =>
    string(6) "542.15"
    'prevclose' =>
    string(6) "545.38"
    'bid' =>
    string(6) "544.01"
    'ask' =>
    string(6) "544.05"
    'bidsize' =>
    string(3) "100"
    'asksize' =>
    string(3) "100"
    'rawbidsize' =>
    string(1) "1"
    'rawasksize' =>
    string(1) "1"
    'tradevolume' =>
    string(5) "12969"
    'sharevolume' =>
    string(6) "883866"
    'vwap' =>
    string(10) "544.072853"
    'lasttradedatetime' =>
    string(25) "2014-11-14T15:30:54-05:00"
    'sharesoutstanding' =>
    string(9) "678365654"
    'marketcap' =>
    string(12) "369967060378"
    'eps' =>
    string(5) "19.07"
    'peratio' =>
    string(4) "28.6"
    'pbratio' =>
    string(5) "3.744"
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
    string(7) "541.747"
    'day50movingavg' =>
    string(7) "559.858"
    'day200movingavg' =>
    string(6) "559.15"
    'avg10dayvolume' =>
    string(7) "1419513"
    'avg30dayvolume' =>
    string(7) "2054165"
    'avg90dayvolume' =>
    string(7) "1695460"
    'alpha' =>
    string(9) "-0.004898"
    'beta' =>
    string(8) "0.262499"
    'r2' =>
    string(8) "0.017802"
    'stddev' =>
    string(8) "0.036665"
    'periods' =>
    string(1) "8"
    'day21ema' =>
    string(6) "547.65"
    'day50ema' =>
    string(7) "555.075"
    'day200ema' =>
    string(6) "560.36"
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
    string(4) "4.71"
    'pcfratio' =>
    string(5) "20.80"
    'pfcfratio' =>
    string(5) "54.10"
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
    string(9) "5.7132473"
    'pricetobook' =>
    string(4) "3.74"
    'pricetotangiblebook' =>
    string(4) "4.71"
    'pricetocashflow' =>
    string(4) "20.8"
    'pricetofreecash' =>
    string(4) "54.1"
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
  array(64) {
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
    string(5) "28.60"
    'pehighlast5years' =>
    array(0) {
    }
    'pelowlast5years' =>
    array(0) {
    }
    'pricetosales' =>
    string(9) "5.7132473"
    'pricetobook' =>
    string(4) "3.74"
    'pricetotangiblebook' =>
    string(4) "4.71"
    'pricetocashflow' =>
    string(4) "20.8"
    'pricetofreecash' =>
    string(4) "54.1"
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
    string(6) "544.02"
    'change' =>
    string(5) "-1.36"
    'changepercent' =>
    string(9) "-0.249367"
    'tick' =>
    string(2) "-1"
    'open' =>
    string(6) "546.68"
    'high' =>
    string(6) "546.68"
    'low' =>
    string(6) "542.15"
    'prevclose' =>
    string(6) "545.38"
    'bid' =>
    string(6) "544.01"
    'ask' =>
    string(6) "544.05"
    'bidsize' =>
    string(3) "200"
    'asksize' =>
    string(3) "100"
    'rawbidsize' =>
    string(1) "2"
    'rawasksize' =>
    string(1) "1"
    'tradevolume' =>
    string(5) "12969"
    'sharevolume' =>
    string(6) "883866"
    'vwap' =>
    string(10) "544.072853"
    'lasttradedatetime' =>
    string(25) "2014-11-14T15:30:54-05:00"
    'sharesoutstanding' =>
    string(9) "678365654"
    'marketcap' =>
    string(12) "369044483089"
    'eps' =>
    string(5) "19.07"
    'pbratio' =>
    string(5) "3.735"
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
