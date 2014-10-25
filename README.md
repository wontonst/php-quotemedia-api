# QuoteMedia API PHP Library

This library is intended to make QuoteMedia API calls easier to manage.

## Getting Started
Download/clone this repo from Github.

To start using this library, you must first include the autoload.php in the root directory.

### Stocks

This library includes code for using QuoteMedia's stock APIs.

#### QuoteMediaStocks Class

If you want quick requests for basic information about stocks, you can use the QuoteMediaStocks class. Note that you should use this class when you want information on less than 50 tickers at once, since that's the API call limit. To go beyond this limit, skip to the next section.

Here's a basic runthrough.

<pre>
$webmaster_id = XXXXX; // REPLACE this with your webmaster ID
$tickers = array('GOOG','AAPL','AMZN');
$api = new QuoteMediaStocks($webmaster_id);
$quotes = $api->getQuotes($tickers);
$profiles = $api->getProfiles($tickers);
$fundamentals = $api->getFundamentals($tickers);
var_dump($quotes);
</pre>

The var dump will show something like this

<pre>
array(3) {
  [0]=>
  array(15) {
    ["CompanyName"]=>
    string(10) "Google Inc"
    ["CompanyNameReq"]=>
    string(10) "Google Inc"
    ["date123"]=>
    string(10) "2014-10-24"
    ["MarketCap"]=>
    int(365102866203)
    ["CompanyTicker"]=>
    string(4) "GOOG"
    ["PercDec"]=>
    float(-0.772087)
    ["ClosingPrice"]=>
    float(539.78)
    ["DollarChange"]=>
    float(-4.2)
    ["peratio"]=>
    float(27.9)
    ["OpeningPrice"]=>
    float(544.36)
    ["IntradayLow"]=>
    float(535.79)
    ["IntradayHigh"]=>
    float(544.88)
    ["DailyVolume"]=>
    float(1972043)
    ["NumOfTrades"]=>
    int(27835)
    ["ShareOutstanding"]=>
    int(676391986)
  }
  [1]=>
  array(15) {
    ["CompanyName"]=>
    string(9) "Apple Inc"
    ["CompanyNameReq"]=>
    string(9) "Apple Inc"
    ["date123"]=>
    string(10) "2014-10-24"
    ["MarketCap"]=>
    int(630043365740)
    ["CompanyTicker"]=>
    string(4) "AAPL"
    ["PercDec"]=>
    float(0.372031)
    ["ClosingPrice"]=>
    float(105.22)
    ["DollarChange"]=>
    float(0.39)
    ["peratio"]=>
    float(16)
    ["OpeningPrice"]=>
    float(105.18)
    ["IntradayLow"]=>
    float(104.53)
    ["IntradayHigh"]=>
    float(105.49)
    ["DailyVolume"]=>
    float(46993509)
    ["NumOfTrades"]=>
    int(198450)
    ["ShareOutstanding"]=>
    int(5987867000)
  }
  [2]=>
  array(15) {
    ["CompanyName"]=>
    string(15) "Amazon.com Inc."
    ["CompanyNameReq"]=>
    string(15) "Amazon.com Inc."
    ["date123"]=>
    string(10) "2014-10-24"
    ["MarketCap"]=>
    int(132632086598)
    ["CompanyTicker"]=>
    string(4) "AMZN"
    ["PercDec"]=>
    float(-8.3403)
    ["ClosingPrice"]=>
    float(287.06)
    ["DollarChange"]=>
    float(-26.12)
    ["peratio"]=>
    float(817.5)
    ["OpeningPrice"]=>
    float(284.4)
    ["IntradayLow"]=>
    float(284)
    ["IntradayHigh"]=>
    float(293.81)
    ["DailyVolume"]=>
    float(19803563)
    ["NumOfTrades"]=>
    int(168329)
    ["ShareOutstanding"]=>
    int(462036113)
  }
}
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
  array(25) {
    ["CompanyName"]=>
    string(10) "Google Inc"
    ["CompanyNameReq"]=>
    string(10) "Google Inc"
    ["date123"]=>
    string(10) "2014-10-24"
    ["MarketCap"]=>
    int(365102866203)
    ["CompanyTicker"]=>
    string(4) "GOOG"
    ["PercDec"]=>
    float(-0.772087)
    ["ClosingPrice"]=>
    float(539.78)
    ["DollarChange"]=>
    float(-4.2)
    ["peratio"]=>
    float(27.9)
    ["OpeningPrice"]=>
    float(544.36)
    ["IntradayLow"]=>
    float(535.79)
    ["IntradayHigh"]=>
    float(544.88)
    ["DailyVolume"]=>
    float(1972043)
    ["NumOfTrades"]=>
    int(27835)
    ["ShareOutstanding"]=>
    int(676391986)
    ["AvgDailyVol"]=>
    int(2169156)
    ["Week52high"]=>
    float(604.83)
    ["Week52low"]=>
    float(502.8)
    ["SMA50day"]=>
    float(569.012)
    ["SMA200day"]=>
    float(560.308)
    ["CompanyDesc"]=>
    string(157) "Google Inc is a web search and online advertising company that offers search, advertising, operating systems and platforms, enterprise and hardware products."
    ["NameofCEO"]=>
    string(10) "Larry Page"
    ["NumofEmployees"]=>
    string(5) "52069"
    ["City"]=>
    string(13) "Mountain View"
    ["State"]=>
    string(2) "CA"
  }
}
</pre>

### Articles
N/A TBA
