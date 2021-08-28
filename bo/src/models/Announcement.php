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
class Announcement extends Database {

    /**
     *
     * @var Resource
     */
     public function __construct($db = 'db') {
        parent::__construct(Raise::params()[$db]);
        $this->tableName = "announcement";
        $this->adminID   = $_SESSION['INF_adminID'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
    }

    public function getAnnouncementList($data)
    {
        $where = ' WHERE id!=0 ';

        if($data['status']!=""){
            $where .= " AND status = '$data[status]' ";
        }else{
            $where .= " AND status != '2' ";
        }

        if($data['language']!=""){
            
            $where .= " AND lang_id = '$data[language]' ";
        }

        if(!empty($data['datefrom']) && !empty($data['dateto'])){
            $where .= " AND createtime BETWEEN '$data[datefrom]' AND '$data[dateto]' ";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT count(*) FROM announcement $where",'value');
        $userStatus = array(0=>"Published", 1=>"Hidden", 2=>"Removed");
        $this->query("SELECT * FROM announcement $where ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        $result = ['data' => $this->resultset()];
        foreach ($result['data'] as $key => $value) {
            
            $language=$this->callsql("SELECT lang_name FROM language WHERE id='$value[lang_id]'",'value');
            $result['data'][$key]['title'] =$value['title'];
            $result['data'][$key]['id'] =$value['id'] ;
            $result['data'][$key]['language'] =$language ;

                if($value['status']==0 || $value['status']==1 || $value['status']==2){
                    $result['data'][$key]['status'] = $userStatus[$value['status']];
                }else{
                    $result['data'][$key]['status'] = "-";
                }
                $result['data'][$key]['filename'] = '<a target="_blank" href="'.BASEURL.'web/upload/announcement/'.$value['filename'].'">'.$value['filename'].'</a>';
                $result['data'][$key]['datetime'] = date("d-m-Y H:i:s",$value['createtime']);
                $result['data'][$key]['action'] = '<button class="btn btn-info" id="btn'.$value['id'].'" data-lang="'.$value['lang_id'].'" onclick="showModal('.$value['id'].')">Edit</button>
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

    public function getLanguageArray(){

      return  $this->callsql("SELECT * FROM `language` WHERE `status`=1","rows");
    }

    public function getAnnouncement($id){
      return $this->callsql("SELECT filename FROM announcement WHERE id='$id'",'value');
    }

    public function addAnnouncement($data){

           $time = time();
          
            $this->query(" INSERT INTO `announcement` SET `title`='$data[title]',`filename`='$data[filename]',`status`='1',`createtime`='$time',`createid`='$this->adminID',`createip`='$this->IP',`lang_id`='$data[lang_id]',`message`='$data[message]'");
         
            
            $this->execute();

            $announcementId = $this->lastInsertId();

            $act="Added new Announcement ,ID ".$announcementId;
            $this->adminActivityLog($act);


         return $announcementId; 
    }

     public function updateAnnouncement($data){

        $time = time();


          if(!empty($data['filename'])){
             $this->query(" UPDATE `announcement` SET `title`='$data[title]',`filename`='$data[filename]',`status`='$data[status]',`updatetime`='$time',`updateid`='$this->adminID',`updateip`='$this->IP',`lang_id`='$data[lang_id]',`message`='$data[message]' WHERE `id`='$data[id]'");

          }else{
               $this->query(" UPDATE `announcement` SET `title`='$data[title]',`status`='$data[status]',`updatetime`='$time',`updateid`='$this->adminID',`updateip`='$this->IP',`lang_id`='$data[lang_id]',`message`='$data[message]' WHERE `id`='$data[id]'");
          }

        
        if($this->execute()){

           $act="Updated Announcement ,ID ".$data['id'];
          $this->adminActivityLog($act);

           return true; 
        }else
          return false; 
    }

    public function deleteAnnouncement($ID){

      $time=time();
      $this->query("UPDATE `announcement` SET status='2',updatetime='$time',updateid='$this->adminID',updateip='$this->IP' WHERE id='$ID'");
      if($this->execute()){
           
         $activity ="Announcement ID-".$ID." Deleted";
        
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


    



}
