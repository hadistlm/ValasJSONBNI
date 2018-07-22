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
    	echo GetValasBNI::run();
    }
}

/**
 * Up we go || Running the program
 */
$run = new index();
?>