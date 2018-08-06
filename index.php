<?php

require __DIR__.'/app/getValasBNI.php';

/**
 * Initialize Program
 */
class index
{
    /**
     * Construtor for class index
     * 
     */
    public function __construct()
    {
    	$this->exec();   
    }

    /**
     * Execute the function to be running
     *
     */
    public function exec()
    {
    	// === || default = BNI | syariah = BNI Syariah || === //
    	$vendor = 'syariah';
        
        // === all | USD | SGD | AUD | EUR | GBP | CAD | CHF | HKD | JPY | SAR  === //
    	$kurs   = 'all';

        echo GetValasBNI::run($vendor, $kurs);
    }
}

/**
 * Up we go || Running the program
 */
$run = new index();
?>