<?php

/**
 * @author 
 * @desc To describe an example of Model
 */

namespace src\models;

use src\lib\Database;
use inc\Raise;

/**
 * @author 
 */
class Game extends Database {

    /**
     *
     * @var Resource
     */
     public function __construct($db = 'db') {
        parent::__construct(Raise::params()[$db]);
        $this->tableName = "game_list";
        $this->adminID   = $_SESSION['INF_adminID'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
    }

    public function getGameList($data)
    {
        $where = ' WHERE id!=0 ';

        if($data['status']!=""){
            $where .= " AND status = '$data[status]' ";
        }else{
            $where .= " AND status != '3' ";
        }

        if($data['name']!=""){
            
            $where .= " AND name LIKE '%$data[name]%' ";
        }

         if($data['type']!=""){
            $where .= " AND is_hot_game = '$data[type]' ";
        }else{
          if($data['type']== "0"){
            $where .= " AND is_hot_game = '0 ";

          }

        }

        if(!empty($data['datefrom']) && !empty($data['dateto'])){
            $where .= " AND created_at BETWEEN '$data[datefrom]' AND '$data[dateto]' ";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT count(*) FROM game_list $where",'value');
        $status = array(1=>"Enabled", 2=>"Disabled", 3=>"Deleted");
        $type = array(0=>"Normal Game", 1=>"Hot Game");
        $this->query("SELECT * FROM game_list $where ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        $result = ['data' => $this->resultset()];
        foreach ($result['data'] as $key => $value) {
            
            $result['data'][$key]['name'] =$value['name'];
            $result['data'][$key]['id'] =$value['id'] ;
            $result['data'][$key]['status'] = $status[$value['status']];
            $result['data'][$key]['type'] = $type[$value['is_hot_game']];
                
                $result['data'][$key]['image'] = '<a target="_blank" href="'.BASEURL.'/web/upload/game/'.$value['image_url'].'" class="btn btn-info">View Image</a>';
                $result['data'][$key]['datetime'] = date("d-m-Y H:i:s",$value['created_at']);
                $result['data'][$key]['action'] = '<button class="btn btn-info" id="btn'.$value['id'].'" onclick="showModal('.$value['id'].')">Edit</button>
                                                 <button class="btn btn-info" onclick="deleteThis('.$value['id'].')">Delete</button>';
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        return $result;
    }

    public function getGame($id){
      return $this->callsql("SELECT image_url FROM game_list WHERE id='$id'",'value');
    }

    public function addGame($data){

           $time = time();
          
            $this->query(" INSERT INTO `game_list` SET `name`='$data[name]',`image_url`='$data[image_url]',`status`='1',`game_code`='$data[game_code]',`game_vendor`='$data[game_vendor]',`order_num`='$data[order_num]',`is_hot_game`='$data[is_hot_game]',`created_at`='$time',`created_by`='$this->adminID',`created_ip`='$this->IP'");
         
            
            $this->execute();

            $gameId = $this->lastInsertId();

            $act="Added new Game ,ID ".$gameId;
            $this->adminActivityLog($act);


         return $gameId; 
    }

     public function updateGame($data){

        $time = time();


          if(!empty($data['image_url'])){
             $this->query(" UPDATE `game_list` SET `name`='$data[name]',`image_url`='$data[image_url]',`status`='$data[status]',`game_code`='$data[game_code]',`game_vendor`='$data[game_vendor]',`order_num`='$data[order_num]',`is_hot_game`='$data[is_hot_game]',`updated_at`='$time',`updated_by`='$this->adminID',`updated_ip`='$this->IP' WHERE `id`='$data[id]'");

          }else{
               $this->query(" UPDATE `game_list` SET `name`='$data[name]',`status`='$data[status]',`game_code`='$data[game_code]',`game_vendor`='$data[game_vendor]',`order_num`='$data[order_num]',`is_hot_game`='$data[is_hot_game]',`updated_at`='$time',`updated_by`='$this->adminID',`updated_ip`='$this->IP' WHERE `id`='$data[id]'");
          }

        
        if($this->execute()){

           $act="Updated Game ,ID ".$data['id'];
          $this->adminActivityLog($act);

           return true; 
        }else
          return false; 
    }

    public function deleteGame($ID){

      $time=time();
      $this->query("UPDATE `game_list` SET status='3',deleted_at='$time',deleted_by='$this->adminID',deleted_ip='$this->IP' WHERE id='$ID'");
      if($this->execute()){
           
         $activity ="Game ID-".$ID." Deleted";
        
         $this->adminActivityLog($activity);
         return true;
      }else
         return false;
   }

    public function adminActivityLog($activity){


        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
  
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , created_at= '$time' , created_ip='$ip' ";

        $this->query($stmt);
        $this->execute();

        return true;
    }

    public function validateOrderNo($orderno,$id){

      if(empty($id)){
        $check = $this->callsql("SELECT id FROM `game_list` WHERE `status`!=3 AND order_num ='$orderno'","value");
      }else{
        $check = $this->callsql("SELECT id FROM `game_list` WHERE `status`!=3 AND order_num ='$orderno' AND id!='$id'","value");
      }
        if(empty($check)){
          return true;
        }else{
          return false;
        }
    }

    public function validateName($name,$id){

      if(empty($id)){
        $check = $this->callsql("SELECT id FROM `game_list` WHERE `status`!=3 AND name ='$name'","value");
      }else{
        $check = $this->callsql("SELECT id FROM `game_list` WHERE `status`!=3 AND name ='$name' AND id!='$id'","value");
      }
        if(empty($check)){
          return true;
        }else{
          return false;
        }
    }
    



}
