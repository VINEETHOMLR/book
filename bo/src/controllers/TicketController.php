<?php
namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\TicketConversation;
use inc\Raise;
/**
 * To handle the users data models
 * @author 
 */

class TicketController extends Controller {

    /**
     * 
     * @return Mixed
     */
    public function __construct(){

        $this->mdl      = (new TicketConversation);
        $this->adminId  = $_SESSION['INF_adminID'];
    }

    public function actionChat() {

      $servicesArray = $_SESSION['INF_privilages'];
      $servicesArray = array_values($servicesArray);
      $servicesArray = explode(",", $servicesArray[0]);
      $role          = $_SESSION['INF_role'];
        
        if( !in_array(6, $servicesArray) ) {
            if($role !=1){
              header("Location: ".BASEURL."");
              exit;
            }
        }

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $status   = $this->cleanMe(Router::post('status'));
         $username = $this->cleanMe(Router::post('username'));

         $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
         $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

         $filter = ["datefrom" => $date_from,
                    "dateto"   => $date_to,
                    "status"   => $status,
                    "username" => $username];

      $data['data']=array();
      if($chat=$this->mdl->getTicketlist($filter))
      {

        //global $categoryArray;
     
        foreach ($chat as $key => $value) {
            
            $data['data'][$key]['userid']=$value['user_id'];
            if(date('d-m-Y')==date("d-m-Y",$value['created_at'])) {
            $data['data'][$key]['time'] = date("H:i:sA",$value['created_at']);
            }  
            else{
             $data['data'][$key]['time'] = date(" H:i:sA",$value['created_at']);   
            } 
          
             $data['data'][$key]['user'] =$this->mdl->callsql("SELECT username FROM user WHERE id='$value[user_id]'","value");
            
             $data['data'][$key]['title'] =$value['title'];
             $data['data'][$key]['tid']=$value['id'];
             //$data['data'][$key]['ticket']=$value['ticket_id'];
             $data['data'][$key]['Status'] = $value['status'];
           }
         }
              
       return $this->render('Ticket/chat',['chatlists'=>$data['data'],'datefrom'=>$datefrom,'dateto'=>$dateto,'status'=>$status,'username'=>$username]);
    }
	
	public function actiongetChat1() {

      $servicesArray = $_SESSION['INF_privilages'];
      $servicesArray = array_values($servicesArray);
      $servicesArray = explode(",", $servicesArray[0]);
      $role          = $_SESSION['INF_role'];
        
        if( !in_array(6, $servicesArray) ) {
            if($role !=1){
              header("Location: ".BASEURL."");
              exit;
            }
        }

         $datefrom = $this->cleanMe($_POST['datefrom']); 
         $dateto   = $this->cleanMe($_POST['dateto']);
         $status   = $this->cleanMe($_POST['status']);
         $username = $this->cleanMe($_POST['username']);

         $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
         $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

         $filter = ["datefrom" => $date_from,
                    "dateto"   => $date_to,
                    "status"   => $status,
                    "username" => $username];
					
      $chat=$this->mdl->getTicketlist1($filter);
	 
	  if(empty($chat)){
		die(1);
	  }

      $data['data']=array();
      if($chat=$this->mdl->getTicketlist($filter))
      {

        //global $categoryArray;
     
        /*foreach ($chat as $key => $value) {
            
            $data['data'][$key]['userid']=$value['user_id'];
            if(date('d-m-Y')==date("d-m-Y",$value['created_at'])) {
            $data['data'][$key]['time'] = date("H:i:sA",$value['created_at']);
            }  
            else{
             $data['data'][$key]['time'] = date(" H:i:sA",$value['created_at']);   
            } 
          
             $data['data'][$key]['user'] =$this->mdl->callsql("SELECT username FROM user WHERE id='$value[user_id]'","value");
            
             $data['data'][$key]['title'] =$value['title'];
             $data['data'][$key]['tid']=$value['id'];
             //$data['data'][$key]['ticket']=$value['ticket_id'];
             $data['data'][$key]['Status'] = $value['status'];
           }*/
		   $html ="";
		   foreach($chat as $key => $value){
			    $user =$this->mdl->callsql("SELECT username FROM user WHERE id='$value[user_id]'","value");
                $html .='<div class="person" data-uid="'.$value['user_id'].'"data-tid="'.$value['id'].'">';
                $html .='<div class="user-info">';
                $html .='<div class="f-head">';
                        if(empty($value['status'])){ $html .='<span class="dot"></span>';}
                $html .='<img src="'.WEB_PATH.'assets/img/90x90.jpg" alt="avatar">';
                $html .='</div>';
                $html .='<div class="f-body">';
                $html .='<div class="meta-info">';
                $html .='<span class="user-name" data-name="Nia Hillyer">'.$value['title'].'</span>';
                $html .='</div>';
                $html .='<span class="preview">'.$user.'</span>';
                $html .='</div>';
                $html .='</div>';
                $html .='</div>'; 
            }
		   
		   
         }
		//echo $html;
         die($html);    
       //return $this->render('Ticket/chat',['chatlists'=>$data['data'],'datefrom'=>$datefrom,'dateto'=>$dateto,'status'=>$status,'username'=>$username]);
    }

    public function actiongetUserMessage(){

        $html="";
        $ticId='';
        $data=array();
        $response=array();
        $userId = (Router::post('userId'));
        $ticId = (Router::post('ticktId'));
        $ticketStatus=$this->mdl->getTicketstatus($ticId);

        if($ticketStatus==0||$ticketStatus==1){
          $action="Close Ticket";
          $reopen="false";
        }
         if($ticketStatus==2||$ticketStatus==4){
          $action="Reopen";
          $reopen="true";


        }
        $response = $this->mdl->getUserChat($userId,$ticId);
		
        foreach($response as $key=>$value){
            //$data[date("d-m-Y",$value['created_at'])][$value['id']]['userid']=$value['user_id'];
            $data[date("d-m-Y",$value['created_at'])][$value['id']]['id']=$value['id'];
            $data[date("d-m-Y",$value['created_at'])][$value['id']]['ticket_id']=$value['ticket_id'];
            $data[date("d-m-Y",$value['created_at'])][$value['id']]['reply_type']=$value['reply_type'];
            $data[date("d-m-Y",$value['created_at'])][$value['id']]['message']=$value['message'];
            $data[date("d-m-Y",$value['created_at'])][$value['id']]['read_status']=$value['read_status'];
            $data[date("d-m-Y",$value['created_at'])][$value['id']]['created_at']=date("d-m-Y H:i:sA",$value['created_at']);
        }
        // print_r($data);
        // die();
         
         $html.='<div id="chat-conversation-box-scroll" class="chat-conversation-box-scroll">';
         $html.='<div class="chat active-chat" id ="live" data-chat="person'.$userId.'" data-id="'.$userId.'" data-tid="'.$ticId.'">';
          foreach ($data as $key => $value) {
           
            $html.='<div class="conversation-start">
                    <span>'.$key.'</span>'
                ;
              foreach ($value as $key1 => $value1) {

                if($value1['reply_type']==0){
                    $c="bubble you";
                }else{
                    $c="bubble me";
                }

                $html.='<div class="'.$c.'">
                '.$value1['message'].'</div>';
            }
            $html.='</div>';

          }
         
         
          $html.='</div>';
          $html.='<div id="statusChange" data-type="'.$reopen.'">
                                                        <button  id= "c" class="btn btn-success" onclick=" changeTicket('.$userId.',\''.$ticId.'\','.$reopen.')">'.$action.'</button>
                                                    </div>';
          
       

          die($html);
       
        
    }
	
	    public function actiongetUserMessage1(){

        $html="";
        $ticId='';
        $data=array();
        $response=array();
        $userId = (Router::post('userId'));
        $ticId = (Router::post('ticktId'));
        $ticketStatus=$this->mdl->getTicketstatus($ticId);

        if($ticketStatus==0||$ticketStatus==1){
          $action="Close Ticket";
          $reopen="false";
        }
         if($ticketStatus==2||$ticketStatus==4){
          $action="Reopen";
          $reopen="true";


        }
        $response = $this->mdl->getUserChat1($userId,$ticId);
		if(empty($response)){
		die(1);
		}
		$response = $this->mdl->getUserChat($userId,$ticId);
        foreach($response as $key=>$value){
            //$data[date("d-m-Y",$value['created_at'])][$value['id']]['userid']=$value['user_id'];
            $data[date("d-m-Y",$value['created_at'])][$value['id']]['id']=$value['id'];
            $data[date("d-m-Y",$value['created_at'])][$value['id']]['ticket_id']=$value['ticket_id'];
            $data[date("d-m-Y",$value['created_at'])][$value['id']]['reply_type']=$value['reply_type'];
            $data[date("d-m-Y",$value['created_at'])][$value['id']]['message']=$value['message'];
            $data[date("d-m-Y",$value['created_at'])][$value['id']]['read_status']=$value['read_status'];
            $data[date("d-m-Y",$value['created_at'])][$value['id']]['created_at']=date("d-m-Y H:i:sA",$value['created_at']);
        }
        // print_r($data);
        // die();
         
         $html.='<div id="chat-conversation-box-scroll" class="chat-conversation-box-scroll">';
         $html.='<div class="chat active-chat" id ="live" data-chat="person'.$userId.'" data-id="'.$userId.'" data-tid="'.$ticId.'">';
          foreach ($data as $key => $value) {
           
            $html.='<div class="conversation-start">
                    <span>'.$key.'</span>'
                ;
              foreach ($value as $key1 => $value1) {

                if($value1['reply_type']==0){
                    $c="bubble you";
                }else{
                    $c="bubble me";
                }

                $html.='<div class="'.$c.'">
                '.$value1['message'].'</div>';
            }
            $html.='</div>';

          }
         
         
          $html.='</div>';
          $html.='<div id="statusChange" data-type="'.$reopen.'">
                                                        <button  id= "c" class="btn btn-success" onclick=" changeTicket('.$userId.',\''.$ticId.'\','.$reopen.')">'.$action.'</button>
                                                    </div>';
          
       

          die($html);
       
        
    }

    public function actionSendmessage() {

      $userId = (Router::post('userId'));
      $reply_type = (Router::post('reply_type'));
      $message = (Router::post('message'));
      $read_status = (Router::post('read_status'));
      $ticket_id = (Router::post('TicktId'));
      $data=array(
        'user_id'=>$userId,
        'reply_type'=>$reply_type,
        'message'   =>$message,
        'read_status'=>$read_status,
        'ticket_id'=>$ticket_id
      );
     
        $chat=$this->mdl->sendMessage($data);

       return $chat;
         
    }
    public function actionUpdateTicket() {

      $userId = (Router::post('userId'));
      $ticketId = (Router::post('TicketId'));
      $reopen=(Router::post('reopen'));
       $data=array(
        'user_id'=>$userId,
        'ticketId'=>$ticketId,
        'reopen'   =>$reopen
       
      );
       
       if(!empty($this->mdl->UpdateTicket($data))){
        $msg="Ticket status Updated";
        $this->sendMessage('success',$msg);
        return false;
     
       }

         
    }
    

}

