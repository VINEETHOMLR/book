<?php

namespace src\controllers;
use inc\Raise;
use inc\Controller;
use src\lib\Router;
use src\inc\transactionArray;
use src\models\Report;

class ExportConsolidateReportController extends Controller {

    public function actionExport(){
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');

        define('PERPAGE',10000);  


        $export_excel_folder = FILEUPLOADPATH.'export'.DIRECTORY_SEPARATOR;

        error_reporting(0); 

        $model = new Report();

        $post_id = isset($_POST['id'])?$_POST['id']:""; 

        if(empty($post_id)){
            die();
        }

        $time_nw = time();

        

        $data  = $model->callsql("SELECT * FROM export_request_list WHERE id='$post_id' AND report = 5 AND status = 0","row");

        if(empty($data)){
            die();
        }

        $perPage  = 100;

        if(!empty($data)){

            $filter = $this->getWhereQry($post_id,$data['params']);
            
            $his = $model->Consolidate($filter);

            $totalcount =  count($his);

            if(empty($totalcount)){ 
                $model->callsql("UPDATE export_request_list SET status = '3',progresslevel = '100' WHERE id = '$post_id' ",'');
                die();
            }
            $csv="";


            $csv .= "Username,  BTC Wallet, USDT Wallet, ETH Wallet \n";//Column headers

            $totalPages = ceil($totalcount/$perPage);

            $filename_nw = 'consolidate_report_'.$post_id.'_'.$time_nw.'.csv';

            $csv_handler = fopen($export_excel_folder.$filename_nw,'w');

            fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));

            fwrite($csv_handler,$csv);

            for($page=1;$page<=$totalPages;$page++){

                $pageStart    = ($page - 1)*$perPage;

                $csv = $this->excelcreation($his);

                $percent_val = ($page/$totalPages) * 100;

                $percent_val = (int)$percent_val;

                if(!empty($csv)){
                    fwrite($csv_handler,$csv);
                }

                $model->callsql("UPDATE export_request_list SET status = 1,progress_level='$percent_val',completed_time = '".time()."' WHERE id='$post_id'",'');
                
            }   

            fclose($csv_handler);

            $model->callsql("UPDATE export_request_list set status = 2,progress_level='100',excel_generated='$filename_nw',completed_time='".time()."' WHERE id='$post_id'",'');
        }
    }

    public function getWhereQry($post_id,$data){

        $data = json_decode($data);

        $q = array();
        
        $q['username'] = $data->username;
        $q['export'] = true;
            

        return $q;

    }


    public function excelcreation($his){

        $html = "";
        $perPage = 1000;//PERPAGE;

        foreach ($his['data'] as $val) {

            $html.= $val['username'].','.$val['btc_wallet'].','.$val['usdt_wallet'].','.$val['eth_wallet']."\n"; //Append data to csv

        }

        return $html;

    }

}


?>
