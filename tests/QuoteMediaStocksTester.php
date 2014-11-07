<?php

abstract class QuoteMediaStocksTester extends PHPUnit_Framework_TestCase {

    protected function setUpInputs() {
        $this->sArray = array(
            array('AAPL'),
            array('KO'),
            array('SWHC'),
            array('MS'),
            array('GOOG')
        );
        $this->mArray = array(
            array('GOOG', 'AAPL'),
            array('SWHC', 'MSFT', 'MS', 'C', 'XLNX', 'GOOG', 'AAPL', 'KO', 'PX', 'F')
        );
    }

    /**
     * Checks to make sure there are no errors, that the result is an array, that the array size is same as input array size, and makes sure each row in the array is also an array/
     * @param type $input
     * @param type $output
     * @param type $function_name
     */
    private function validateOutput(&$input, &$output, $function_name) {
        $this->assertInternalType('array', $output, 'Error is ' . $this->api->getError());
        $this->assertEquals(QuoteMediaError::GOOD, $this->api->getErrorID(), 'No longer in GOOD state, current state is ' . $this->api->getError());
        $this->assertEquals(count($output), count($input), 'Array size returned by ' . $function_name . ' does not matched input array size.' . "\n" . print_r($output, true));

        foreach ($output as &$row) {
            $error_msg = 'A row in the result of ' . $function_name . ' is type ' . gettype($row) . ' instead of array. ';
            $error_msg .= $row === false ? 'Encountered error: ' . $this->api->getError() : 'Dump: ' . print_r($row, true);
            $this->assertInternalType('array', $row, $error_msg);
        }
    }

    protected function validateHasFields(&$fields, &$output) {
        foreach ($output as $out) {
            foreach ($fields as $f) {
                $this->assertTrue(isset($out[$f]), 'Resulting array is missing field ' . $f . "\n" . print_r($out, true));
            }
        }
    }

    protected function validateArray(&$input, &$output, $function_name) {
        $this->validateOutput($input, $output, $function_name);
        //assert tickers
        for ($i = 0; $i != count($input); $i++) {
            $found = false;
            foreach ($output as $out) {
                if ($input[$i] == $out['symbol']) {
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, 'Ticker ' . $input[$i] . ' could not be found in the resulting array. Dump: ' . print_r($output, true));
        }
    }

    protected function validateAssoc(&$input, &$output, $function_name) {
        $this->validateOutput($input, $output, $function_name);
        foreach ($input as $in) {
            $this->assertTrue(isset($output[$in]), $in . ' could not be found in the resulting associative array. Dump: ' . print_r($output, true));
            $this->assertEquals($in, $output[$in]['symbol'], 'Resulting associative array has incorrect symbol "' . $output[$in]['symbol'] . '", expecting ' . $in);
        }
    }

}
