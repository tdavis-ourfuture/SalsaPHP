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
 * Action
 *
 * Class for interacting with Salsa Actions
 *
 * @author Trevor Davis <tdavis@ourfutureorg>
 * @version .1
 * @package SalsaPHP
 */


class Action {

  /**
   * List actions   *
   * @param int $limit
   * @return array
   */
	public static function listActions($limit=500){
		$result = SalsaPHP::getClient()->get('/api/getObjects.sjs',  array(), array(
							'query' => array( 'object' => 'action',
			                'limit'=>$limit,
			                'json'=>1)
							))->send();
		return json_decode($result->getBody());
	}

	
	/**
	 * Get a single action
	 * @param int $action_KEY
	 * @return array
	 */
     public static function getAction($action_KEY){
         $result = SalsaPHP::getClient()->get('/api/getObjects.sjs',  array(), array(
             'query' => array( 'object' => 'action',
                 'limit'=>1,
                 'condition'=>'action_KEY='.$action_KEY,
                 'json'=>1)
         ))->send();
         $result = json_decode($result->getBody());
         return array_pop($result);
     }
     
  /**
   * Get actions for a supporter actions   *
   * @param int $supporter_KEY
   * @return array
   */
	public static function getSupporterActions($supporter_KEY,$limit=10){
		$result = SalsaPHP::getClient()->get('/api/getObjects.sjs',  array(), array(
							'query' => array( 'object' => 'supporter_action',
			                'limit'=>$limit,
						    'orderBy'=>'-supporter_action_KEY',
			                'condition'=>'supporter_KEY='.$supporter_KEY,
			                'json'=>1)
							))->send();
		return json_decode($result->getBody());
	}

  /**
   * Add user to action
   * @param int $supporter_KEY
   * @param int $action_KEY
   */
	public static function addSupporterAction($supporter_KEY,$action_KEY){
		$client = SalsaPHP::getClient();

		$args = array('object' => 'supporter_action', 'json' => '1','supporter_KEY'=>$supporter_KEY,'action_KEY'=>$action_KEY);

		$args = array_merge($args,$fields);

		$req = $client->post('/save',  array(), $args);

		$result=$req->send();

		return json_decode($result->getBody());
	}


}
