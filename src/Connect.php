<?php


class Connect {
	
	public static function authenticate(){
 	

 	   $client = SalsaPHP::getClient();

 	/*   SalsaPHP::setSessionCookie($jar);

	  	$result = $client->post('/api/authenticate.sjs',  
	  		['query' => 
	            [
	                'email' => SalsaPHP::getUserName(),
	                'password'=>SalsaPHP::getPassword(),
	                'json'=>1],
            ],   
            ['verify' => false],     
            ['cookies' => SalsaPHP::getSessionCookie()]);
*/

		$req = $client->get('/api/authenticate.sjs',  array(), array(
							'query' => array('email' => SalsaPHP::getUserName(),
							            'password'=>SalsaPHP::getPassword(),
							            'json'=>1)
							));

		$result=$req->send();


	  	$result = json_decode($result->getBody());
	  	if ($result->status!='success'){
	  		throw new Exception($result->message);
	  	}
	  	return true;
  }

	
}