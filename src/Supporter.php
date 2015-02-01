<?php
/**
 * SalsaPHP
 *
 * PHP Version 5.4
 *
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      https://github.com/tdavis-ourfuture/SalsaPHP
 */

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
		$client = SalsaPHP::getClient();

		if (!is_int($group_KEY)) {
				throw new Exception('Group KEY must be an integer');
		}

		$args = array(
							'object' => 'supporter', 
							'json' => '1',
							'link'	=> 'groups',
							'linkKey' =>$group_KEY,
							'key'=>$supporter_KEY);

		$req = $client->post('/save',  array(), $args);
		$result=$req->send();

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

		$filtered = array_filter( $result, function( $item ) use ( $group_KEY ) {
			  if ($item->groups_KEY == $group_KEY  ) {
			    return true;
			  }
			  return false;
			} );

		$res = array_pop($filtered);

		$supporter_groups_KEY =$res->supporter_groups_KEY;


		$reqb = $client->get('/delete',  array(), array(
									'query' => array( 'object' => 'supporter_groups',
					                'key'=>$supporter_groups_KEY,'json'=>1)
									));
		$result=$reqb->send();


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

}
