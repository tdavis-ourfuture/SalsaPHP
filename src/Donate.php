<?php
/**
 * SalsaPHP
 *
 * PHP Version 5.4
 *
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      https://github.com/tdavis-ourfuture/SalsaPHP
 */

namespace SalsaPHP;

/**
 * Donate
 * 
 * Read and write from the donate table.
 *
 * @author Trevor Davis <tdavis@ourfutureorg>
 * @version .1
 * @package SalsaPHP
 */
class Donate {	

  /**
   * Get individual supporter's donations
   *
   * @param int $supporter_KEY
   */
	public static function getSupporterDonations($supporter_KEY){

		$client = SalsaPHP::getClient();


		$req = $client->get('/api/getObjects.sjs',  array(), array(
							'query' => array( 'object' => 'donation',
			                'condition'=>'supporter_KEY='.$supporter_KEY,
			                'json'=>1)
							));

		$result=$req->send();

		return json_decode($result->getBody());
	}

  /**
   * Log donation to salsa.
   * @param int $supporter_KEY
   * @param int $amount
   * @param string $firstName
   * @param string $lastName
   * @param string $email
   * @param array $args
   * @return int the donation_KEY
   */
	public static function addSupporterDonations($supporter_KEY,$amount,$firstName,$lastName,$email,$args=array()){

		$client = SalsaPHP::getClient();
		$arr = array( 'object' => 'donation',
	               	  'supporter_KEY'=>$supporter_KEY,
	               	  'First_Name'=>$firstName,
	               	  'Last_Name'=>$lastName,
	               	  'Email'=>$email,
	               	  'amount'=>$amount,
  	                  'json'=>1);
		$arr = array_merge($arr,$args);

		$result = SalsaPHP::getClient()->get('/save',  array(), array('query' => $arr))->send();



		return $result[0]->key;
	}



}