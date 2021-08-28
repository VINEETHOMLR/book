<?php

/**
 * @author 
 * @desc <Core>Auto Generated model
 */

namespace src\models;

use src\lib\Database;
use inc\Raise;
use src\lib\Router;
//use src\lib\RRedis;

class Driver extends Database {

    /**
     * Constructor of the model
     */
    public function __construct($db = 'db') {
        parent::__construct(Raise::params()[$db]);
        $this->tableName = "driver_user";
        $this->adminID   = $_SESSION['INF_adminID'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;

    }

     public function getUserList($data){

        $where = ' WHERE id!=0 AND status!=3';

        if(!empty($data['driver_name'])){


            $where .= " AND driver_name LIKE '$data[driver_name]%' ";
            
        }

        if(!empty($data['vehicle_number'])){


            $where .= " AND vehicle_number LIKE '$data[vehicle_number]%' ";
            
        }
        
        if($data['status']!=""){
            $where .= " AND status = '$data[status]' ";
        }

      
        /*if(!empty($data['datefrom']) && !empty($data['dateto'])){
            $where .= " AND created_at BETWEEN '$data[datefrom]' AND '$data[dateto]' ";
        }*/

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT COUNT(id) FROM $this->tableName $where ","value");

        $result['data'] = $this->callsql("SELECT * FROM $this->tableName $where ORDER BY id DESC LIMIT $pagecount,$this->perPage","rows");
        $statusArray = array('1'=>'Active','2'=>'Inactive','3'=>'Deleted');

        foreach ($result['data'] as $key => $value) {

            $checked = $value['status'] == '1' ? 'checked' : '';
           
            $result['data'][$key]['id'] = $value['id'];
            $result['data'][$key]['created_at'] = date("d-m-Y H:i:s",$value['created_at']);
            $result['data'][$key]['name'] = '<a class="badge outline-badge-primary" href="'.BASEURL.'Driver/Account/?driver='.$value['id'].'">'.$value['driver_name'].'</a>';
           // $result['data'][$key]['status'] = $statusArray[$value['status']];
            $result['data'][$key]['status'] = '<label class="switch s-primary mb-0">
                                                   <input type="checkbox" '.$checked.'><span class="slider round" onclick="switchStatus('.$value["id"].','.$value['status'].');"></span>
                                              </label>';
            $result['data'][$key]['action'] = '<a onclick="DeleteDriver('.$value['id'].')" data-toggle="tooltip" data-placement="top" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>';                                  
           
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        
        return $result;
    }


    public function getuserdetails($id){

          $user['data'] = $this->callsql("SELECT * FROM driver_user WHERE id='$id'",'row');
          /*$user['info'] = $this->callsql("SELECT * FROM driver_profile WHERE driver_id='$id'",'row'); 
          */return $user;
    }
    public function updateUser($data){

        $time=time();


       $this->query("UPDATE driver_user SET  driver_name='$data[driver_name]',vehicle_number='$data[vehicle_number]',phone='$data[phone]' WHERE id='$data[edit]'");

        if($this->execute()){

          $id = $data['edit'];
          $activity="Edited Details of Driver ID ".$id;
          $this->adminActivityLog($activity);

          return true;
        }else
          return false;
    }
     public function adminActivityLog($activity){

        $time=time();

        $this->query("INSERT INTO admin_activity_log SET admin_id ='$this->adminID' , action ='$activity' , created_at= '$time' , created_ip='$this->IP' ");
        $this->execute();

        return true;
    }
    public function addDriver($data){

           $time = time();
          
            $this->query(" INSERT INTO `driver_user` SET `driver_name`='$data[driver_name]',`vehicle_number`='$data[vehicle_number]',`phone`='$data[phone]',`status`='$data[status]',`password`='$data[password]',`created_at`='$time'");
            
            $this->execute();

            $driverId = $this->lastInsertId();
            $act="Added new Driver ,ID ".$driverId;
            $this->adminActivityLog($act);


         return $driverId; 
    }

    public function UpdateSlotStatus($id,$status){
      
      $time = time();
      $this->query("UPDATE `driver_user` SET `status` = '$status',updated_at = '$time' WHERE `id`='$id'");
      $this->execute();

      return true;
    }

    public function deleteDriver($id){
         
         $time = time();
         $this->query("UPDATE `driver_user` SET `status` = '3',deleted_at = '$time' WHERE `id`='$id'");
         $this->execute();

         return true;  
    }

    public function getDriverList($filter=[])
    {
        $where = " where id!=0 AND status!=3";  

        $driverList = $this->callsql("SELECT * FROM $this->tableName $where ORDER BY id DESC ","rows");

        if(!empty($driverList)) {
            
            return $driverList;
        }
        return [];



    }



    /*public function getVehicleList(){

      return $this->callsql("SELECT * FROM vechile_type WHERE status='1'",'rows'); 

    }

    public function getVehicleListEdit(){

      return $this->callsql("SELECT * FROM vechile_type",'rows'); 

    }

    public function getLevelListEdit(){

      return $this->callsql("SELECT * FROM driver_vip",'rows'); 

    }

    public function getUpcomingTripData($driver){

      return $this->callsql("SELECT * FROM `trips_jobs` WHERE driver_id='$driver' AND status='0' ORDER BY start_time DESC",'rows');
    }

     public function getTripHisData($driver){
      $fromDate = strtotime(date("Y-m-d 23:59:59",strtotime("-7 days")));
      $endDate  = time();

      return $this->callsql("SELECT * FROM `trips_jobs` WHERE driver_id='$driver' AND status='2' AND start_time BETWEEN $fromDate AND $endDate ORDER BY start_time DESC",'rows');
    }

     public function getSum($driver){

      $total['paid'] = $this->callsql("SELECT IFNULL(SUM(amount),0) FROM `trips_jobs` WHERE driver_id='$driver' AND status=2 AND paid=1",'value');
      $total['notpaid'] = $this->callsql("SELECT IFNULL(SUM(amount),0) FROM `trips_jobs` WHERE driver_id='$driver' AND status=2 AND paid=0",'value');
      $total['nooftransfer'] = $this->callsql("SELECT count(id) as total FROM `trips_jobs` WHERE driver_id='$driver' AND status=2 AND transfer=1",'value');

      return $total;
    }*/

}
