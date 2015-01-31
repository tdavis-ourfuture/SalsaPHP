<?php

class Supporter {	

	
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

	public static function updateSupporter($supporter_KEY,$fields){
		$client = SalsaPHP::getClient();

		if (!is_array($fields)) {
				throw new Exception('Fields must be an array');
		}

		$args = array(
							'object' => 'supporter', 
							'json' => '1',
							'key'=>$supporter_KEY);


		$args = array_merge($args,$fields);

		$req = $client->post('/save',  array(), $args);

		$result=$req->send();

		return json_decode($result->getBody());


	}

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
}
