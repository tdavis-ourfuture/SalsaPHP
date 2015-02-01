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
 * Query
 * 
 * List individual and multiple Salsa queries for targeting supporters.
 *
 * @author Trevor Davis <tdavis@ourfutureorg>
 * @version .1
 * @package SalsaPHP
 */
class Query {


  /**
   * Lists queries.
   *
   * @param int $limit
   */
	public static function listQuery($limit=500){

		$client = SalsaPHP::getClient();
		$req = $client->get('/api/getObjects.sjs',  array(), array(
							'query' => array( 'object' => 'query',
			                'orderBy'=>'-query_KEY',
			                'limit'=>$limit,
			                'condition'=>'Name IS NOT EMPTY',
			                 'include'=>'query_KEY,chapter_KEY,campaign_manager_KEY,Last_Modified,Date_Created,Name',
			                'json'=>1)
							));

		$result=$req->send();

		return json_decode($result->getBody());
	}	

  /**
   * Get individual query.
   *
   * @param int $query_KEY
   */
	public static function  getQuery($query_KEY){

		$client = SalsaPHP::getClient();
		$req = $client->get('/api/getObjects.sjs',  array(), array(
							'query' => array( 'object' => 'query',
			                'limit'=>1,
			                'condition'=>'query_KEY='.$query_KEY,
			                 'include'=>'query_KEY,chapter_KEY,campaign_manager_KEY,Last_Modified,Date_Created,Name',
			                'json'=>1)
							));

		$result=$req->send();

		$result =  json_decode($result->getBody());

		if (isset($result[0])) {
			return $result[0];
		}

		return false;
	}	


}