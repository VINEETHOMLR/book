<?php
error_reporting(E_ALL);
use inc\Raise;

$this->mainTitle = 'Settings';
$this->subTitle  = 'Service Settings';
$role = $_SESSION['INF_role'];
?>
<style type="text/css">
  svg {
    
    color: #1b55e2;
    width: 20px;
    margin-right: 5px;
  }
  img{
    height: 25px;
    width: 25px;
    margin-right: 5px;
  }

  .center {
 
  position: absolute;
  top: 50%;
  left: 50%;
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}


  /*.widget-content {
    left: 30%;
  }*/
@media only screen and (max-width: 700px) {
  .widget-content {
    left: 0%;
  }
}
 @media only screen and (min-width: 700px) and (max-width: 1000px)  {
  .widget-content {
    left: 10%;
  }
}
</style>
<div id="content" class="main-content">
    <div class="layout-px-spacing">
          <div class="row layout-top-spacing">
              <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
               
                 <div class="statbox widget box box-shadow"style="margin-bottom:10px;">
               		<form id="serviceSettingsForm" method="post" role="form" >
                    <div class="col-md-12 align-self-center">
                        <div class="row">
                            <div class="row col-md-12">
                              <?php foreach($key as $value){?>
                                <div class="form-group col-md-2">
                                  <label><?=ucfirst( substr($value['keyvalue'],0,strpos($value['keyvalue'], "_")));?></label>
                                </div>
                                <?php if($value['data']=='1')$checked="checked";else $checked="";?>
                                 <div class="form-group col-md-1">
                                  <label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                    <input type="checkbox" <?=$checked?>>
                                      <span class="slider round" id="swId" onclick="switchStatus('<?=$value['keyvalue']?>');"></span>
                                  </label>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                 	  </div>
                  	</form>
                 </div>
            </div>
        </div>
    </div>


<script type="text/javascript">

function switchStatus(value)
{ 
  loadingoverlay('info','Please Wait','Please Wait');
        
    $.post('<?=BASEURL;?>Settings/switchStatus/',{value:value},function(response){ 
      newResp  = JSON.parse(response);
      if(newResp['status'] == 'success'){
      openSuccess(newResp['response'])
      }else{ 
      loadingoverlay('error','',newResp['response']);
      }
    });
    return false;
}
</script>          