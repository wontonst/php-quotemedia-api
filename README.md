
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
$result = $api->getQuotes($tickers);
var_dump($result->getResult());
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
    string(6) "534.39"
    ["change"]=>
    string(5) "16.35"
    ["changepercent"]=>
    string(6) "3.1561"
    ["tick"]=>
    string(1) "0"
    ["open"]=>
    string(6) "521.48"
    ["high"]=>
    string(6) "536.33"
    ["low"]=>
    string(6) "519.70"
    ["prevclose"]=>
    string(6) "518.04"
    ["bid"]=>
    string(6) "533.86"
    ["ask"]=>
    string(6) "535.10"
    ["bidsize"]=>
    string(3) "100"
    ["asksize"]=>
    string(3) "100"
    ["rawbidsize"]=>
    string(1) "1"
    ["rawasksize"]=>
    string(1) "1"
    ["tradevolume"]=>
    string(5) "36520"
    ["sharevolume"]=>
    string(7) "2669215"
    ["vwap"]=>
    string(10) "528.921563"
    ["lasttradedatetime"]=>
    string(25) "2015-01-22T16:15:00-05:00"
    ["sharesoutstanding"]=>
    string(9) "678365654"
    ["marketcap"]=>
    string(12) "362511821841"
    ["eps"]=>
    string(5) "19.07"
    ["peratio"]=>
    string(5) "27.20"
    ["pbratio"]=>
    string(5) "3.668"
    ["week52high"]=>
    string(6) "604.83"
    ["week52low"]=>
    string(6) "487.56"
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

#### Result Object
You may have realized that to get the actual data, you call $result->getResult(). 

This is because the returned value from a get* function call is actually a QuoteMediaStockResult object. Here is a quick reference of the functions you can call on this object:

+ hasMissing() - boolean of whether or not there are missing symbols not returned from API
+ getMissing() - get array of missing symbols
+ hasMalformed() - boolean of whether or not there are malformed symbols in the input array
+ getMalformed() - get array of malformed symbols
+ hasError() - boolean of whether or not an error occurred
+ getError() - return string that describes the last error encountered
+ getErrorID() - return ID of the last error encountered
+ getErrorIDHistory() - if multiple errors occurred, this will be an array of errors
+ getResult() - return the result obtained from API

#### QuoteMediaBatcher Class

Note that in the previous section you can only retrieve each section separately and you are limited to 50 tickers at a time in getProfiles. This is due to limitations on the API itself. To bypass these limitations you can use the QuoteMediaBatcher class that is conveniently embedded in the QuoteMediaStocks class.

<pre>
$webmaster_id = 000000;//user inputs webmaster id 
$input = array( 'GOOG');
$api = new QuoteMediaStocks($webmaster_id);
$batcher = $api->getBatcher();
$result = $batcher->getAll($input);
var_dump($result->getResult());
</pre>

The var dump would look something like this

<pre>
    array(1) {
  [0]=>
  array(113) {
    ["symbol"]=>
    string(4) "GOOG"
    ["exchange"]=>
    string(3) "NGS"
    ["longname"]=>
    string(10) "Google Inc"
    ["shortname"]=>
    string(4) "GOOG"
    ["last"]=>
    string(6) "534.39"
    ["change"]=>
    string(5) "16.35"
    ["changepercent"]=>
    string(6) "3.1561"
    ["tick"]=>
    string(1) "0"
    ["open"]=>
    string(6) "521.48"
    ["high"]=>
    string(6) "536.33"
    ["low"]=>
    string(6) "519.70"
    ["prevclose"]=>
    string(6) "518.04"
    ["bid"]=>
    string(6) "533.86"
    ["ask"]=>
    string(6) "535.10"
    ["bidsize"]=>
    string(3) "100"
    ["asksize"]=>
    string(3) "100"
    ["rawbidsize"]=>
    string(1) "1"
    ["rawasksize"]=>
    string(1) "1"
    ["tradevolume"]=>
    string(5) "36520"
    ["sharevolume"]=>
    string(7) "2669215"
    ["vwap"]=>
    string(10) "528.921563"
    ["lasttradedatetime"]=>
    string(25) "2015-01-22T16:15:00-05:00"
    ["sharesoutstanding"]=>
    string(9) "678365654"
    ["marketcap"]=>
    string(12) "351420543398"
    ["eps"]=>
    string(5) "19.07"
    ["peratio"]=>
    string(4) "27.2"
    ["pbratio"]=>
    string(5) "3.556"
    ["week52high"]=>
    string(6) "604.83"
    ["week52low"]=>
    string(6) "487.56"
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
          string(4) "^SPX"
          ["indexname"]=>
          string(7) "S&P 500"
        }
        [2]=>
        array(2) {
          ["indexsymbol"]=>
          string(5) "^GDOW"
          ["indexname"]=>
          string(20) "The Global Dow (USD)"
        }
        [3]=>
        array(2) {
          ["indexsymbol"]=>
          string(4) "^OEX"
          ["indexname"]=>
          string(7) "S&P 100"
        }
        [4]=>
        array(2) {
          ["indexsymbol"]=>
          string(11) "^QM31168144"
          ["indexname"]=>
          string(30) "Internet Content & Information"
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
    ["day21movingavg"]=>
    string(7) "513.655"
    ["day50movingavg"]=>
    string(7) "524.522"
    ["day200movingavg"]=>
    string(7) "550.772"
    ["avg10dayvolume"]=>
    string(7) "2387404"
    ["avg30dayvolume"]=>
    string(7) "2240043"
    ["avg90dayvolume"]=>
    string(7) "1991881"
    ["alpha"]=>
    string(8) "-0.00883"
    ["beta"]=>
    string(8) "0.253523"
    ["r2"]=>
    string(8) "0.020705"
    ["stddev"]=>
    string(8) "0.034008"
    ["periods"]=>
    string(2) "10"
    ["day21ema"]=>
    string(6) "511.36"
    ["day50ema"]=>
    string(7) "522.737"
    ["day200ema"]=>
    string(7) "545.948"
    ["sdate"]=>
    string(10) "2014-12-31"
    ["sshares"]=>
    string(7) "2766885"
    ["sratio"]=>
    string(4) "1.33"
    ["spercent"]=>
    string(4) "0.40"
    ["adrratio"]=>
    string(1) "0"
    ["ptbratio"]=>
    string(4) "4.47"
    ["pcfratio"]=>
    string(5) "26.20"
    ["pfcfratio"]=>
    string(5) "88.50"
    ["revenue"]=>
    string(11) "47898000000"
    ["revenuepershare"]=>
    string(8) "70.60794"
    ["revenue3years"]=>
    string(4) "10.6"
    ["revenue5years"]=>
    string(5) "19.11"
    ["quickratio"]=>
    string(3) "4.0"
    ["currentratio"]=>
    string(3) "4.5"
    ["longtermdebttocapital"]=>
    string(4) "0.03"
    ["totaldebttoequity"]=>
    string(4) "0.05"
    ["intcoverage"]=>
    string(5) "159.7"
    ["leverageratio"]=>
    string(3) "1.3"
    ["returnonequity"]=>
    string(5) "14.37"
    ["returnoncapital"]=>
    string(5) "10.81"
    ["returnonassets"]=>
    string(4) "2.24"
    ["pehighlast5years"]=>
    array(0) {
    }
    ["pelowlast5years"]=>
    array(0) {
    }
    ["pricetosales"]=>
    string(9) "7.3368516"
    ["pricetobook"]=>
    string(4) "3.56"
    ["pricetotangiblebook"]=>
    string(4) "4.47"
    ["pricetocashflow"]=>
    string(4) "26.2"
    ["pricetofreecash"]=>
    string(4) "88.5"
    ["dividendrate"]=>
    array(0) {
    }
    ["dividendyield"]=>
    array(0) {
    }
    ["dividend3years"]=>
    array(0) {
    }
    ["dividend5years"]=>
    array(0) {
    }
    ["paymenttype"]=>
    array(0) {
    }
    ["exdividenddate"]=>
    array(0) {
    }
    ["grossmargin"]=>
    string(4) "39.7"
    ["ebitmargin"]=>
    string(4) "33.3"
    ["ebitdamargin"]=>
    string(4) "43.3"
    ["pretaxprofitmargin"]=>
    string(4) "29.6"
    ["profitmargincont"]=>
    string(5) "21.17"
    ["profitmargintot"]=>
    string(5) "20.22"
    ["assetsturnover"]=>
    string(3) "0.4"
    ["invoiceturnover"]=>
    string(4) "82.0"
    ["receivablesturnover"]=>
    string(3) "4.9"
  }
}
</pre>

If you want to grab only a few sections instead of all, you can use the get() function, like so

<pre>
$webmaster_id = 000000;//user inputs webmaster id 
$input = array( 'GOOG');
$api = new QuoteMediaStocks($webmaster_id);
$batcher = $api->getBatcher();
$result = $batcher->get($input,array(QuoteMediaConst::GET_KEY_RATIOS,QuoteMediaConst::GET_QUOTES));
var_dump($result->getResult());
</pre>

The var dump would look something like

<pre>
    array(1) {
  [0]=>
  array(64) {
    ["symbol"]=>
    string(4) "GOOG"
    ["exchange"]=>
    string(3) "NGS"
    ["longname"]=>
    string(10) "Google Inc"
    ["shortname"]=>
    string(4) "GOOG"
    ["revenue"]=>
    string(11) "47898000000"
    ["revenuepershare"]=>
    string(8) "70.60794"
    ["revenue3years"]=>
    string(4) "10.6"
    ["revenue5years"]=>
    string(5) "19.11"
    ["quickratio"]=>
    string(3) "4.0"
    ["currentratio"]=>
    string(3) "4.5"
    ["longtermdebttocapital"]=>
    string(4) "0.03"
    ["totaldebttoequity"]=>
    string(4) "0.05"
    ["intcoverage"]=>
    string(5) "159.7"
    ["leverageratio"]=>
    string(3) "1.3"
    ["returnonequity"]=>
    string(5) "14.37"
    ["returnoncapital"]=>
    string(5) "10.81"
    ["returnonassets"]=>
    string(4) "2.24"
    ["peratio"]=>
    string(5) "27.20"
    ["pehighlast5years"]=>
    array(0) {
    }
    ["pelowlast5years"]=>
    array(0) {
    }
    ["pricetosales"]=>
    string(9) "7.3368516"
    ["pricetobook"]=>
    string(4) "3.56"
    ["pricetotangiblebook"]=>
    string(4) "4.47"
    ["pricetocashflow"]=>
    string(4) "26.2"
    ["pricetofreecash"]=>
    string(4) "88.5"
    ["dividendrate"]=>
    array(0) {
    }
    ["dividendyield"]=>
    array(0) {
    }
    ["dividend3years"]=>
    array(0) {
    }
    ["dividend5years"]=>
    array(0) {
    }
    ["paymenttype"]=>
    array(0) {
    }
    ["exdividenddate"]=>
    array(0) {
    }
    ["grossmargin"]=>
    string(4) "39.7"
    ["ebitmargin"]=>
    string(4) "33.3"
    ["ebitdamargin"]=>
    string(4) "43.3"
    ["pretaxprofitmargin"]=>
    string(4) "29.6"
    ["profitmargincont"]=>
    string(5) "21.17"
    ["profitmargintot"]=>
    string(5) "20.22"
    ["assetsturnover"]=>
    string(3) "0.4"
    ["invoiceturnover"]=>
    string(4) "82.0"
    ["receivablesturnover"]=>
    string(3) "4.9"
    ["last"]=>
    string(6) "534.39"
    ["change"]=>
    string(5) "16.35"
    ["changepercent"]=>
    string(6) "3.1561"
    ["tick"]=>
    string(1) "0"
    ["open"]=>
    string(6) "521.48"
    ["high"]=>
    string(6) "536.33"
    ["low"]=>
    string(6) "519.70"
    ["prevclose"]=>
    string(6) "518.04"
    ["bid"]=>
    string(6) "533.86"
    ["ask"]=>
    string(6) "535.10"
    ["bidsize"]=>
    string(3) "100"
    ["asksize"]=>
    string(3) "100"
    ["rawbidsize"]=>
    string(1) "1"
    ["rawasksize"]=>
    string(1) "1"
    ["tradevolume"]=>
    string(5) "36520"
    ["sharevolume"]=>
    string(7) "2669215"
    ["vwap"]=>
    string(10) "528.921563"
    ["lasttradedatetime"]=>
    string(25) "2015-01-22T16:15:00-05:00"
    ["sharesoutstanding"]=>
    string(9) "678365654"
    ["marketcap"]=>
    string(12) "362511821841"
    ["eps"]=>
    string(5) "19.07"
    ["pbratio"]=>
    string(5) "3.668"
    ["week52high"]=>
    string(6) "604.83"
    ["week52low"]=>
    string(6) "487.56"
  }
}
</pre>

Again, you can choose to return an associative map instead of an array using an optional parameter, ie

<pre>
$api->getAll($input,true);
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
+ -11 : One or more symbols do not exist.

Remember you can always use the result object's getError() function to retrieve the error string instead of doing the conversion yourself.

# Contributing
Contributions can be submitted right here on Github through a pull request. 

All functions should be documented in doxygen/javadoc style. The current code the code is in doxygen/javadoc style so you can generate it yourself for reference.

Before any pull request can be submitted, be sure to add to the unit tests to test your new code. Be sure to run the entire suit of unit tests. 
You can use the run_tests.sh script if you don't know phpunit. 
Don't forget to add your webmaster ID to testsautoload.php before running the test or it will give you a syntax error.
