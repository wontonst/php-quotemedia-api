<?php

include(__DIR__.'/autoload.php');

/*
ENTER YOUR WEBMASTER ID HERE. DONT FORGET TO REMOVE IT WHEN YOU'RE COMMITING
Easy local repo cmd so you don't accidentally push your webmaster id up: git update-index --assume-unchanged testsautoload.php
*/
define('TEST_WEBMASTER_ID', );//WEBMASTER ID HERE

spl_autoload_register(function($class){
    @include_once(__DIR__.'/tests/'.$class.'.php');
  });

?>