<?php

class EmailBlast {

    /**
     * Creates a email blast.
     *
     * @param string       $refname  The Salsa reference name.
     * @param string       $subject  The subject line.
     * @param string       $html  The html for the email.
     * @param string       $text  The text version of the email.
     * @param string       $fromname  The name of the sender
     * @param string       $fromaddress  The email address of the sender
     * @param string       $replyto  The reply to address.
     *
     * @throws \Exception
     * @return int email_blast_KEY
     */	
	public static function create($refname,$subject,$html,$text,$fromname,$fromaddress,$replyto ) {
		$client = SalsaPHP::getClient();


		$req = $client->post('/save',  array(), array(
							'object' => 'email_blast', 
				            'json' => '1',
				            'Reference_Name' => $refname, 
				            '_From' => $fromname, 
				            'From_Email_address' =>$fromaddress, 
				            'Reply_To_Email'=>$replyto, 
				            'From_Name'=>$fromname,
				            'Text_Content' => $text,
				            'Subject' => $subject, 
				            'HTML_Content' => $html
		              	));
			$result=$req->send();


		$result = json_decode($result->getBody());

		return $result[0]->key;

		}

	public static function setQuery($email_blast_KEY,$query_KEY){
		$client = SalsaPHP::getClient();

		if (Query::getQuery($query_KEY) == false) {
			throw new Exception('Invalid query key');
		}

		$req = $client->post('/save',  array(), array(
							'object' => 'email_blast', 
				            'json' => '1',
               		 		'key'=>$email_blast_KEY,
               		 		'query_KEY'=>$query_KEY
						));

		$result=$req->send();



		return true;


	}

	public static function scheduleEmail($email_blast_KEY,$time_scheduled){
		$client = SalsaPHP::getClient();

		if (strtotime("now") < strtotime($time_scheduled)) {
			throw new Exception('Scheduled time is in the past');
		}


		$time_scheduled = date('Y-m-d H:i:s',strtotime($time_scheduled));

		$req = $client->post('/save',  array(), array(
							'object' => 'email_blast', 
							'json' => '1',
							'key'=>$email_blast_KEY,
                   			'Stage'=>"Scheduled",
                   			"Scheduled_Time"=>$time_scheduled
						));

		$result=$req->send();

		return json_decode($result->getBody());


	}


	
	public static function getEmail($email_blast_KEY){
		$client = SalsaPHP::getClient();


		$req = $client->get('/api/getObjects.sjs',  array(), array(
							'query' => array( 'object' => 'email_blast',
			                'include'=>'email_blast_KEY,Last_Modified,Date_Created,Date_Requested,Reference_Name,template_KEY,Stage,Subject,From_Name,From_Email_address,Reply_To_Email,chapter_KEY,campaign_manager_KEY,query_KEY,Status,campaign_KEY,number_failed,number_sent,total_target_supporters',
			                'condition'=>'email_blast_KEY='.$email_blast_KEY,
			                'json'=>1)
							));

		$result=$req->send();

		$result = json_decode($result->getBody());

		return $result[0];
	}

	public static function listEmails($limit=500){
		$client = SalsaPHP::getClient();


		$req = $client->get('/api/getObjects.sjs',  array(), array(
							'query' => array( 'object' => 'email_blast',
			                'orderBy'=>'-email_blast_KEY',
			                'limit'=>$limit,
			                'include'=>'email_blast_KEY,Last_Modified,Date_Created,Date_Requested,Reference_Name,template_KEY,Stage,Subject,From_Name,From_Email_address,Reply_To_Email,chapter_KEY,campaign_manager_KEY,query_KEY,Status,campaign_KEY,number_failed,number_sent,total_target_supporters',
			                'json'=>1)
							));

		$result=$req->send();

		return json_decode($result->getBody());
	}

	public static function Statistics($email_blast_KEY){
		$client = SalsaPHP::getClient();
		$req = $client->get('/api/getObjects.sjs',  array(), array(
							'query' => array( 'object' => 'email_blast_statistics',
			                'limit'=>'1',
			                'condition'=>'email_blast_KEY='.$email_blast_KEY,
			                'json'=>1)
							));

		$result=$req->send();

		return array_pop(json_decode($result->getBody()));
	}

}