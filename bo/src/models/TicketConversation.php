<?php

/**
 * @author 
 * @desc <RaiseGen>Auto Generated model
 */

namespace src\models;

use src\lib\Database;
use inc\Raise;
use src\lib\Router;

/**
 * @property int(10) $id
 * @property varchar(20) $name
 * @property varchar(20) $description
 * @property int(10) $status
 * */
class TicketConversation extends Database {

    /**
     * Constructor of the model
     */
    public function __construct($db = 'db') {
        parent::__construct(Raise::params()[$db]);
        $this->tableName = "ticket_chat";
        $this->assignAttrs();
       
        $this->adminID   = $_SESSION['INF_adminID'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
    }

    /**
     * 
     * @return Array
     */
    public static function attrs() {
        return ["id","ticket_id","reply_type","message","read_status","created_at","created_ip","created_by"];
    }

    /**
     * 
     * @return $this
     */
    public function assignAttrs($attrs = []) {
        $isExternal = !empty($attrs);
        foreach (($isExternal ? $attrs : self::attrs()) as $eAttr => $attr) {
            $aAttr = $isExternal ? $eAttr : $attr;
            $this->{$aAttr} = $isExternal ? $attr : (Router::post($attr) !== "" ? Router::post($attr) : "");
            $values{$aAttr}= $isExternal ? $attr : (Router::post($attr) !== "" ? Router::post($attr) : "");
        }
        //return $this;
        return $values;
    }
    public function getTicketlist($data){
    
        $search = ' WHERE id!=0';


        if(!empty($data['datefrom']) && !empty($data['dateto'])){
            $search .= " AND created_at BETWEEN '$data[datefrom]' AND '$data[dateto]' ";
        }

        if(!empty($data['username'])){

            $user = $this->callsql("SELECT id FROM `user` WHERE username LIKE '$data[username]%' ","rows");
            $users = array_column($user, 'id');
            $list = empty($users) ? 0 : implode(',', $users);

            $search .= " AND user_id IN ($list) ";  
        }

        if($data['status']!=""){
            $search .= " AND status = '$data[status]' ";
        }

        if(empty($data['datefrom']) && empty($data['username']) && $data['status']==""){
            $search .= " AND status IN (0,1)";
        }
	  $this->query("UPDATE `ticket_list` SET status='1' ".$search." and status=0 ");
	$this->execute();
	 
      return $this->callsql("SELECT * FROM `ticket_list` ".$search." ORDER BY`status` ASC","rows");

      
    }

	public function getTicketlist1($data){
    
        $search = ' WHERE status=0 ';


        if(!empty($data['datefrom']) && !empty($data['dateto'])){
            $search .= " AND created_at BETWEEN '$data[datefrom]' AND '$data[dateto]' ";
        }

        if(!empty($data['username'])){

            $user = $this->callsql("SELECT id FROM `user` WHERE username LIKE '$data[username]%' ","rows");
            $users = array_column($user, 'id');
            $list = empty($users) ? 0 : implode(',', $users);

            $search .= " AND user_id IN ($list) ";  
        }

        if($data['status']!=""){
            $search .= " AND status = '$data[status]' ";
        }

        if(empty($data['datefrom']) && empty($data['username']) && $data['status']==""){
            $search .= " AND status IN (0,1)";
        }
		
	 
     
      return $this->callsql("SELECT * FROM `ticket_list` ".$search." ORDER BY`status` ASC","rows");

      
    }
	
	
    public function getConversation(){

    return $this->callsql("SELECT * FROM $this->tableName  where `id` in
      (SELECT max(`id`) from ticket_chat group by `user_id` )ORDER BY `created_at` DESC ","rows"); 
    }
    public function getUserChat($user,$ticket){
      
		$this->query("UPDATE $this->tableName SET read_status='1' WHERE `ticket_id`='$ticket' and reply_type=0 ");
		$this->execute();
		return $this->callsql("SELECT * FROM $this->tableName where  ticket_id='$ticket' ORDER BY `created_at` ASC ","rows");
    }
	public function getUserChat1($user,$ticket){
      
		//$this->query("UPDATE $this->tableName SET read_status='1' WHERE `ticket_id`='$ticket' ");
		//$this->execute();
		return $this->callsql("SELECT * FROM $this->tableName where  ticket_id='$ticket' AND read_status='0' and reply_type=0 ORDER BY `created_at` ASC ","rows");
    }
    public function sendMessage($data){
      
    $time=time();
    $ip=$_SERVER['REMOTE_ADDR'];

    $checkentry=$this->callsql("SELECT COUNT(id) FROM ticket_chat WHERE`ticket_id`='$data[ticket_id]' AND `reply_type`=1","value");
    $status=$this->getTicketstatus($data['ticket_id']);
    if($checkentry>0 && empty($status )){
      $status=1;
    }
    
     $this->query("UPDATE ticket_list SET status='$status',updated_at='$time',updated_by='$this->adminID',updated_ip='$ip' WHERE `id`='$data[ticket_id]' ");
         $this->execute();
         
    $this->query("INSERT INTO $this->tableName SET ticket_id='$data[ticket_id]',reply_type='$data[reply_type]',message='$data[message]', read_status='$data[read_status]', created_at='$time',created_ip='$this->IP',created_by='$this->adminID'");
          $this->execute();
      

       $act="Replied to ticket coversation , User ID ".$data['user_id'];
          $this->adminActivityLog($act);
    
    }

   
    public function  getTicketdetails($user,$ticket){

    return $this->callsql("SELECT * FROM ticket_list where ticket_id='$ticket' AND user_id='$user' ORDER BY `created_at` ASC ","rows");
    }
     public function getTicketstatus($ticket){

    return $this->callsql("SELECT   status FROM `ticket_list` WHERE id='$ticket' ","value");
    }

     public function UpdateTicket($data)
    {
        
        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
        if($data['reopen']=='true'){
        $status=0;
      }
      else{
        $status=2;
      }

        $this->query("UPDATE ticket_list SET status='$status',updated_at='$time',updated_by='$admin_id',updated_ip='$ip' WHERE `id`='$data[ticketId]' AND user_id='$data[user_id]'");
         $this->execute();
         $activity="Ticket Status Updated ,Ticket ID".$data['ticketId']." By ".$this->adminID;
        $this->adminActivityLog($activity);
       

       return true;
    }
     public function adminActivityLog($activity){

        $time=time();


        $this->query("INSERT INTO admin_activity_log SET admin_id ='$this->adminID' ,action ='$activity', created_at= '$time',created_ip='$this->IP'");
        $this->execute();

        return true;
    }
    

    

}
