<?php include(__DIR__.'/testsautoload.php'); ?>

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
<?php
$api = new QuoteMediaStocks(TEST_WEBMASTER_ID);
$tickers=array('GOOG');
var_dump($api->getQuotes($tickers));
?>
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
<?php
$info = array(
array('getQuotes',QuoteMediaConst::GET_QUOTES,QuoteMediaConst::GET_QUOTES_MAX_SYMBOLS),
array('getProfiles',QuoteMediaConst::GET_PROFILES,QuoteMediaConst::GET_PROFILES_MAX_SYMBOLS),
array('getFundamentals',QuoteMediaConst::GET_FUNDAMENTALS,QuoteMediaConst::GET_FUNDAMENTALS_MAX_SYMBOLS),
array('getKeyRatios',QuoteMediaConst::GET_KEY_RATIOS,QuoteMediaConst::GET_KEY_RATIOS_MAX_SYMBOLS),
);
foreach ($info as $v){
echo '+ '.$v[0].' (array, bool) - function id ('.$v[1].') : calls QuoteMedia\'s '.$v[0].'. Can accept up to '.$v[2].' symbols at a time.'."\n"; 
}
?>

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
<?php
$api = new QuoteMediaBatcher(TEST_WEBMASTER_ID);
$input = array('GOOG');
var_dump($api->getAll($input));
?>
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
<?php
$input = array( 'GOOG');
$api = new QuoteMediaBatcher(TEST_WEBMASTER_ID);
$result = $api->get($input,array(QuoteMediaConst::GET_KEY_RATIOS,QuoteMediaConst::GET_QUOTES));
var_dump($result);
?>
</pre>

Again, you can choose to return an associative map instead of an array using an optional parameter, ie

<pre>
$api->getAll($input,true);
$result = $api->get($input,array(QuoteMediaConst::GET_KEY_RATIOS,QuoteMediaConst::GET_QUOTES),true);
</pre>

## Articles
N/A TBA


## Error Codes
<?php
$oClass = new ReflectionClass ('QuoteMediaError');
$array = $oClass->getConstants ();
foreach($array as $a){
echo '+ '.$a.' : '.QuoteMediaError::IDtoError($a)."\n";
}
?>

# Contributing
Contributions can be submitted right here on Github through a pull request. 

All functions should be documented in doxygen/javadoc style. The current code the code is in doxygen/javadoc style so you can generate it yourself for reference.

Before any pull request can be submitted, be sure to run the unit tests. You can use the run_tests.sh script if you don't know phpunit. Don't forget to add your webmaster ID to testsautoload.php before running the test or it will give you a syntax error.
