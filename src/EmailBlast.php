<?php
/**
 * SalsaPHP
 *
 * PHP Version 5.4
 *
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @version .1
 * @link      https://github.com/tdavis-ourfuture/SalsaPHP
 */

namespace SalsaPHP;

/**
 * EmailBlast
 * 
 * Class for reading and writing email blasts.
 *
 * @author Trevor Davis <tdavis@ourfutureorg>
 * @version .1
 * @package SalsaPHP
 */
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
		
		
		/**
		 * Update the contente.  Really just a weird hack for private reasons.  Call it a joke, an aside. 
		 *
		 * @param int $email_blast_KEY
		 * @param string $html
		 */
		public static function updateContent($email_blast_KEY,$html){
		    $client = SalsaPHP::getClient();
		
		    if (Query::getQuery($query_KEY) == false) {
		        throw new Exception('Invalid query key');
		    }
		
		    $req = $client->post('/save',  array(), array(
		        'object' => 'email_blast',
		        'json' => '1',
		        'key'=>$email_blast_KEY,
		        'HTML_Content'=>$html
		    ));
		
		    $result=$req->send();
		
		    return true;
		}
		
		
  /**
   * Assign a particular query to a blast.
   *
   * @param int $email_blast_KEY
   * @param int $query_KEY
   */
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
  /**
   * Schedule a blast.
   *
   * @param int $email_blast_KEY
   * @param string $time_scheduled Date and time email is to be sent.  Must be in the future.
   */
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


  /**
   * Get a blast from the email_blast table.
   *
   * @param int $email_blast_KEY
   * @return array
   */	
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

  /**
   * List email blasts.
   *
   * @param int $limit
   * @return array
   */	
	public static function listEmails($limit=500,$total_target=null,$email_blast_start=null){
	          
	       if ($limit<=500){
	           return self::_listEmails($limit,$total_target,0,$email_blast_start);
	       }	           
	        $o = round(($limit/500));
            $i=0;
	        $results = array();
            while ($i<= $o) {
                   $offset = 500 * $i;
                   
                   $progress= self::_listEmails(500,$total_target,$offset);
                 $results =	 array_merge($results,$progress);
              
            }
	           
	   return $results;

	}
	
    public static function _listEmails($limit=500,$total_target=null,$offset=0,$email_blast_start=null){
        
        $client = SalsaPHP::getClient();
        $query =  [ 'object' => 'email_blast',
            'orderBy'=>'-email_blast_KEY',
            'limit'=>$offset.','.$limit,
            'include'=>'email_blast_KEY,Last_Modified,Date_Created,Date_Requested,Reference_Name,template_KEY,Stage,Subject,From_Name,From_Email_address,Reply_To_Email,chapter_KEY,campaign_manager_KEY,query_KEY,Status,campaign_KEY,number_failed,number_sent,total_target_supporters,HTML_Content',
            'json'=>1];
           
        if (!empty($email_blast_start)){
        
            $query['condition'] = 'email_blast_KEY>'.$email_blast_start;
        
        }
       elseif (!empty($total_target)){
            $query['condition'] = (empty($query['condition'])) ? 'total_target_supporters>'.$total_target  : $query['condition'].'&total_target_supporters>'.$total_target; 
        
        }


        

        $req = $client->get('/api/getObjects.sjs',  array(), array(
            'query' => $query
        ));
        
        $result=$req->send();
        
        return json_decode($result->getBody());
    }
  /**
   * Get statistics about an individual email blast.
   *
   * @param int $email_blast_KEY
   * @return array
   */
	public static function Statistics($email_blast_KEY){
		$client = SalsaPHP::getClient();
		$req = $client->get('/api/getObjects.sjs',  array(), array(
							'query' => array( 'object' => 'email_blast_statistics',
			                'limit'=>'1',
			                'condition'=>'email_blast_KEY='.$email_blast_KEY,
			                'json'=>1)
							));

		$result=$req->send();
	$result = $result->getBody();
	$result= json_decode($result);
		$result =  array_pop($result);
		return $result;
	}

  /**
   * Get a testing set results (using the a/b functions in salsa)
   *
   * @param int $email_blast_KEY
   * @return array
   */	
	public static function getEmailBlastSet($email_blast_KEY){	


		$email_blast_set_KEY =  self::getSetKey($email_blast_KEY);



		$result=	SalsaPHP::getClient()->get('/api/getObjects.sjs',  array(), array(
							'query' => array( 'object' => 'email_blast_set_email_blast',
			                'include'=>'email_blast_KEY',
			                'condition'=>'email_blast_set_KEY='.$email_blast_set_KEY,
			                'json'=>1)
							))->send()->getBody();
		$result = json_decode($result);

		$output = array();


		foreach ($result as $blast) {
			$output[] = (object)array_merge((array)self::Statistics($blast->email_blast_KEY),(array)self::getEmail($blast->email_blast_KEY));


		}
		var_dump($output);
		exit;

	}

  /**
   * Get the email test set key.
   *
   * @param int $email_blast_KEY
   * @return int
   */	
	private static function getSetKey($email_blast_KEY){
				$result=	SalsaPHP::getClient()->get('/api/getObjects.sjs',  array(), array(
							'query' => array( 'object' => 'email_blast_set_email_blast',
			                'include'=>'email_blast_set_KEY',
			                'condition'=>'email_blast_KEY='.$email_blast_KEY,
			                'json'=>1)
							))->send()->getBody();
		$result = json_decode($result);

		if (!isset($result[0]->email_blast_set_KEY)){ throw new Exception('Not part of a test set.'); }

		return $result[0]->email_blast_set_KEY;
	}
}
