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
 * Supporter
 *
 * Read and write from the suporter table.
 *
 * @author Trevor Davis <tdavis@ourfutureorg>
 * @version .1
 * @package SalsaPHP
 */
class Supporter {


  /**
   * Add an individual supporter.
   *
   * @param string $email
   * @param string $firstName
   * @param string $lastName
   * @param int $zipCode
   * @param array $args
   * @return int the supporter_KEY
   */
	public static function addSupporter($email,$firstName='',$lastName='',$zipCode='',$args=array()){
		$arr = array(
				     'json'=>1,
					 'object' => 'supporter',
	               	  'Email'=>$email,
	               	  'First_Name'=>$firstName,
	               	  'Last_Name'=>$lastName,
	               	  'Zip'=>$zipCode)    ;

		$arr = array_merge($arr,$args);

		$result = SalsaPHP::getClient()->get('/save',  array(), array('query' => $arr))->send();


		$result = json_decode($result->getBody());

		return $result[0]->key;

	}


  /**
   * Get individual supporter.
   *
   * @param int $supporter_KEY
   */
	public static function getSupporter($supporter_KEY){
		$client = SalsaPHP::getClient();


		$req = $client->get('/api/getObjects.sjs',  array(), array(
							'query' => array( 'object' => 'supporter',
			                'condition'=>'supporter_KEY='.$supporter_KEY,
			                'json'=>1)
							));

		$result=$req->send();

		$result = json_decode($result->getBody());

		return $result[0];
	}


  public static function getUnsubscribes($offset=0,$unsubscribe_KEY_START=null){
            $client = SalsaPHP::getClient();
            $query =  [ 'object' => 'unsubscribe',
            'orderBy'=>'-unsubscribe_KEY',
            'limit'=>$offset.',500',
            'json'=>1];

            if (!empty($unsubscribe_KEY_START)){
                $query['condition'] = 'unsubscribe_KEY>'.$unsubscribe_KEY_START;
            }

            $req = $client->get('/api/getObjects.sjs',  array(), array(
                'query' => $query
            ));
            $result=$req->send();
            return json_decode($result->getBody());
  }
  
  /**
   * Get single supporter key by email address
   *
   * @param email $email
   */
	public static function getSupporterKeyByEmail($email){

		$result = SalsaPHP::getClient()->get('/api/getObjects.sjs',  array(), array(
							'query' => array( 'object' => 'supporter',
		   		             'orderBy'=>'-supporter_KEY',
   		            		 'include'=>'supporter_KEY',
			                'condition'=>'Email='.$email,
			                'json'=>1)
							))->send()->getBody();


		$result = json_decode($result);

		return $result[0]->supporter_KEY;

	}

  /**
   * Update individual supporter.
   *
   * @param int $supporter_KEY
   * @param array $fields
   */
	public static function updateSupporter($supporter_KEY,$fields){
		$client = SalsaPHP::getClient();

		if (!is_array($fields)) {
				throw new Exception('Fields must be an array');
		}

		$args = array('object' => 'supporter', 'json' => '1','key'=>$supporter_KEY);


		$args = array_merge($args,$fields);

		$req = $client->post('/save',  array(), $args);

		$result=$req->send();

		return json_decode($result->getBody());
	}


  /**
   * Add individual supporter to group.
   *
   * @param int $supporter_KEY
   * @param array $group_KEY
   */
	public static function addSupporterToGroup($supporter_KEY,$group_KEY){
		if (!is_int($group_KEY)) {
				throw new Exception('Group KEY must be an integer');
		}

		$args = array(		'object' => 'supporter',
							'json' => '1',
							'link'	=> 'groups',
							'linkKey' =>$group_KEY,
							'key'=>$supporter_KEY);

		$result = SalsaPHP::getClient()->post('/save',  array(), $args)->send();

		$result =  json_decode($result->getBody());

		if ($result[0]->result !='success'){
			throw new Exception('Failed to add supporter to group.');
		}

		return true;


	}

  /**
   * Remove individual supporter from a group.
   *
   * @param int $supporter_KEY
   * @param int $group_KEY
   * @param array $fields
   */
	public static function deleteSupporterFromGroup($supporter_KEY,$group_KEY){
	$client = SalsaPHP::getClient();


		$req = $client->get('/api/getObjects.sjs',  array(), array(
							'query' => array( 'object' => 'supporter_groups',
			                'condition'=>'supporter_KEY='.$supporter_KEY,
			                'json'=>1)
							));

		$result=$req->send();

		$result = json_decode($result->getBody());

		$res = array_pop( $result, function( $item ) use ( $group_KEY ) {
			  if ($item->groups_KEY == $group_KEY  ) {  return true; }  return false;
			} );

		$supporter_groups_KEY =$res->supporter_groups_KEY;


		$result = $client->get('/delete',  array(), array(
									'query' => array( 'object' => 'supporter_groups',
					                'key'=>$supporter_groups_KEY,'json'=>1)
									))->send();

		$result = json_decode($result->getBody());

		if (is_array($result)){
			throw new Exception('Failed to remove user from group.');
		}

		return true;
	}

  /**
   * Unsubscribe supporter from all lists.
   *
   * @param int $supporter_KEY
   */
	public static function unsubscribeAll($supporter_KEY){
		$client = SalsaPHP::getClient();
		$args = array('object' => 'supporter', 'json' => '1','key'=>$supporter_KEY,
			'Receive_Email'=>'0');

		$req = $client->post('/save',  array(), $args);
		$result=$req->send();
		$result = json_decode($result->getBody());

		 if ($result[0]->result !='success'){
		 	throw new Exception('Failed to unsubscribe supporter');
		 }
		 return true;
	}
	/**
   * Get count of supporters who can receive emails
   *
   * @param int
   */
	public static function getSupporterCount($chapter_KEY=''){
		$client = SalsaPHP::getClient();


		$result=	SalsaPHP::getClient()->get('/api/getCounts.sjs',  array(), array(
							'query' => array( 'object' => 'supporter',
											'condition'=>'Receive_Email>0',
			                'groupBy'=>'chapter_KEY'

			               )
							))->send()->getBody();

		$xml = simplexml_load_string($result);
		$result = json_decode(json_encode($xml),TRUE);



		return (int)$result['supporter']['count'][0]['count'];

	}

	public static function getSupporterEmailStats(){


		$result=	SalsaPHP::getClient()->get('/api/getCounts.sjs',  array(), array(
							'query' => array( 'object' => 'supporter_email_statistics',
											'groupBy'=>'last_open'

										 )
							))->send()->getBody();

		$xml = simplexml_load_string($result);
		$json = json_encode($xml);
		$array = json_decode($json,TRUE);

		var_dump($array);
	}

}
