<?php

class Errors extends MY_Controller {
	public function __construct(){
		parent::__construct();
	}
    
    public function page_missing() 
    {
    	show_404(current_url());
    }
    
}