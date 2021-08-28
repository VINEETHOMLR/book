<?php

namespace src\controllers;
use inc\Raise;
use inc\Controller;
use src\lib\Router;
use src\inc\transactionArray;
use src\models\CoinSwap;

class ExportCoinSwapController extends Controller {

    public function actionExport(){

        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');

        define('PERPAGE',10000);  

        $export_excel_folder = FILEUPLOADPATH.'export'.DIRECTORY_SEPARATOR;

        error_reporting(0); 

        $model = new CoinSwap();

        $post_id = isset($_POST['id'])?$_POST['id']:"";

        if(empty($post_id)){

            die();
        }

        $time_nw = time();

        

        $data  = $model->callsql("SELECT * FROM export_request_list WHERE id='$post_id' AND report = 1 AND status = 0","row");

        if(empty($data)){
            die();
        }

        $perPage  = 100;

        if(!empty($data)){

            $filter = $this->getWhereQry($post_id,$data['params']);
            $coinCode = $filter['coinCode'];
             
            $his = $model->history($filter);
          
            $totalcount =  count($his);

            if(empty($totalcount)){ 
                $model->callsql("UPDATE export_request_list SET status = '3',progresslevel = '100' WHERE id = '$post_id' ",'');
                die();
            }
            $csv="";


            $csv .= "Date, Username, Swap From, Swap To, Side, Order Amount, Price(USDT), Execute Amount(".strtoupper($coinCode)."), Executed Value(USDT), Fee, Execution Time, Order ID  \n";//Column headers

            $totalPages = ceil($totalcount/$perPage);

            $filename_nw = 'coin_swap_history_'.$post_id.'_'.$time_nw.'.csv';

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
        $date_from = $date_to = "";
        if(!empty($data->from)){
            $date_from =strtotime($data->from." 00:00:00");
        }
        if(!empty($data->to)){
            $date_to = strtotime($data->to." 23:59:59");
        }
        
        $q['datefrom'] =$date_from;
        $q['dateto'] = $date_to;
        $q['username'] = $data->username;
        $q['swapCoin1'] = $data->swapCoin1;
        $q['swapCoin2'] = $data->swapCoin2;
        $q['coinCode'] = $data->coinCode;
        $q['export'] = true;

        return $q;

    }


    public function excelcreation($his){

        
       
        $html = "";
        $perPage = 1000;//PERPAGE;

        foreach ($his['data'] as $val) {
 
            $html.= $val['date'].','.$val['username'].','.$val['coinswapfrom'].','.$val['coinswapto'].','.$val['coinSide'].','.$val['swap_out_amout'].','.$val['swap_in_coin_price'].','.$val['executed_amount'].','.$val['exceuted_value'].','.$val['swap_in_service_fee'].','.$val['exec_time'].','.$val['order_id']."\n"; //Append data to csv

           

        }

        return $html;

    }

}


?>
