<?php

use inc\Raise;
$this->mainTitle = 'Downloads';
$this->subTitle  = 'Export'; 

?>

<style type="text/css">
 

  
  .dataTables_filter{
    display: none;
  }
  .m-t-40{
    margin-top: 0px !important;
  }
  .dataTable{ text-align: center; }

  .div_float{
     float: right;
     margin-right: 10px;
  }

@media(max-width:767px){
    .div_float {
        
        padding: 5px;
        margin-right: 0px;
    }
    .full_width{
     width: 100%;
   }
} 
</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
         <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">                  
                    <div class="table-responsive mb-4 mt-4">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                               <thead>
                                  <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1">Request TIme</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Report Name</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Progress Level</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Download</th>
                                </thead>
                                <tbody>
                                    <?php foreach($data['data'] as $key => $val):
                                        if(!empty($val['excel_generated'])){
                                            $excel_generated = $val['excel_generated'];
                                            $link = '<a href="'.WEB_PATH.'upload/export/'.$excel_generated.'" download><button type="button" class="btn btn-primary"  name="'.WEB_PATH.'upload/export/'.$excel_generated.'" style="float:right;">Download</button></a>';
                                        }else{
                                            $link = '-';
                                        }
                                    ?>
                                    <tr role="row" class="odd">
                                      <td><?=date('d-m-Y h:i:s',$val['create_time'])?></td>
                                      <td><?=$report_all[$val['report']]?></td>
                                      <td><?=$statusArr[$val['status']]?></td>
                                      <td><?=empty($val['progress_level'])?0:$val['progress_level']?></td>
                                      <td><?=$link?></td>
                                    </tr>
                                  <?php endforeach;?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>

</div>
<script>
 
$(function () { 
  $('#accordionExample').find('li a').attr("data-active","false");
  $('#export').attr("data-active","true");
})
</script>