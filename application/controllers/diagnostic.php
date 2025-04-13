<?php

class Diagnostic extends CI_Controller
{

	public function index()
	{
		echo 'Hello, Diagnostic here!';
        exit();
	}
    
    public function smtp_email()
    {
        echo 'smtp email sending test!<br />';
        exit();
        
        $this->load->library('email');
        
        $this->email->initialize(array(
          'protocol' => 'smtp',
          'smtp_host' => 'smtp.sendgrid.net',
          'smtp_user' => 'sandiwinter',
          'smtp_pass' => 'sandiwinter',
          'smtp_port' => 587,
          'crlf' => "\r\n",
          'newline' => "\r\n"
        ));
        
        $this->email->from('tony.spark08@gmail.com', 'Tony Spark');
        $this->email->to('sanljiljan@geniuscript.com');
        //$this->email->cc('another@another-example.com');
        //$this->email->bcc('them@their-example.com');
        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');
        $this->email->send();
        
        echo $this->email->print_debugger();

    }

}