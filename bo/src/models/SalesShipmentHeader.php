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
class SalesShipmentHeader extends Database {

    /**
     *
     * @var Resource
     */
     public function __construct($db = 'db') {
        parent::__construct(Raise::params()[$db]);
        $this->tableName = "sales_shipment_header";
        $this->adminID   = $_SESSION['INF_adminID'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
    }

    public function getDetails($id)
    {

      return $this->callsql("SELECT * FROM $this->tableName where id='$id'",'row');

    }

    public function getShipmentList($data)
    {
        $where = ' WHERE id!=0 AND status!=3';

        if($data['status']!=""){
            $where .= " AND status = '$data[status]' ";
        }

        if($data['delivery_status']!=""){
            $where .= " AND delivery_status = '$data[delivery_status]' ";
        }
        if($data['assigned_driver_id']!=""){
            $where .= " AND assigned_driver_id = '$data[assigned_driver_id]' ";
        }

        if($data['salesshipmentno']!=""){
            $where .= " AND salesshipmentno LIKE '%$data[salesshipmentno]%' ";
        }

        




        if(!empty($data['datefrom']) && !empty($data['dateto'])){
            $where .= " AND createtime BETWEEN '$data[datefrom]' AND '$data[dateto]' ";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT count(*) FROM $this->tableName $where",'value');
        $this->query("SELECT * FROM $this->tableName $where ORDER BY id ASC LIMIT $pagecount,$this->perPage");
        $deliveryStatusArray = array('1'=>'Delivered','2'=>'Not Delivered');
        $result = ['data' => $this->resultset()];


        foreach ($result['data'] as $key => $value) {
      
           

            $checked = $value['status'] == '1' ? 'checked' : '';
            $driver_details = [];
            if(!empty($value['assigned_driver_id'])) {
                
                $driver_details = $this->callsql("SELECT driver_name,vehicle_number FROM driver_user WHERE id=$value[assigned_driver_id]","row");

            }
            $result['data'][$key]['salesshipmentno'] = '<a class="badge outline-badge-primary" onclick="showDetails('.$value['id'].')">'.$value['salesshipmentno'].'</a>';
            $result['data'][$key]['delivery_status'] =$deliveryStatusArray[$value['delivery_status']];
           /* $result['data'][$key]['driver_name'] =!empty($driver_details) ? $driver_details['driver_name'].' / '.$driver_details['vehicle_number'] :'<button class="btn btn-info" id="btn'.$value['id'].'"  onclick="assignModal('.$value['id'].')">Assign</button>';*/


            $result['data'][$key]['driver_name'] =!empty($driver_details) ? $driver_details['driver_name'].' / '.$driver_details['vehicle_number'] :'';


            $result['data'][$key]['delivery_details'] = $value['delivery_status'] == '1' ? '<button class="btn btn-info" id="btn'.$value['id'].'" onclick="showModal('.$value['id'].')">Show</button>':'-';
            $result['data'][$key]['status'] = '<label class="switch s-primary mb-0">
                                                   <input type="checkbox" '.$checked.'><span class="slider round" onclick="switchStatus('.$value["id"].','.$value['status'].');"></span>
                                              </label>';

            $result['data'][$key]['assigned_date'] = !empty($value['assigned_date']) ? date('d-m-Y',$value['assigned_date']) : '-'; 

            /*$language=$this->callsql("SELECT lang_name FROM language WHERE id='$value[lang_id]'",'value');
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
                                                 <button class="btn btn-info" onclick="deleteThis('.$value['id'].')">Delete</button>';*/
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

    public function UpdateSlotStatus($id,$status){

      $this->query("UPDATE $this->tableName SET `status` = '$status' WHERE `id`='$id'");
      $this->execute();

      return true;
    }

    public function assignDriver($id,$assigned_driver_id)
    {
      
      $time = time(); 
      $assigned_by = $_SESSION['INF_adminID'];
      $this->query("UPDATE $this->tableName SET `assigned_driver_id` = '$assigned_driver_id',`assigned_date`='$time',`assigned_by`='$assigned_by' WHERE `id`='$id'");
      if($this->execute()){
        return true;
      }

      return false;

    }


    



}
