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
    	// === USD | SGD | AUD | EUR | GBP | CAD | CHF | HKD | JPY | SAR  === //
    	echo GetValasBNI::run('all');
    }
}

/**
 * Up we go || Running the program
 */
$run = new index();
?>