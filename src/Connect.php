<?php
namespace SalsaPHP;

/**
 * SalsaPHP
 *
 * PHP Version 5.4
 *
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      https://github.com/tdavis-ourfuture/SalsaPHP
 */

/**
 * Connect
 * 
 * Authenticates with Salsa API.
 *
 * @author Trevor Davis <tdavis@ourfutureorg>
 * @version .1
 * @package SalsaPHP
 */
class Connect {
	

  /**
   * Authenticates with SalsaAPI and stores the coookie with SalsaPHP.
   *
   * @return bool
   */
	public static function authenticate(){
 	

 	   $client = SalsaPHP::getClient();

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