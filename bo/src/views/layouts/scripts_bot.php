<?php

  use inc\Raise;
?>


<script type="text/javascript">

    $(document).ready(function(){

        $("#language a").on("click", function(){
            var langId = $(this).attr("data-id");
            if(langId !=''){
                $.post('<?=BASEURL;?>Index/Language',{'language':langId},function(response){
                    location.reload();
                })
            }
        });
    });


    function loadingoverlay(type, title, desc)
    { 
        if (type == 'success')
        {
            swal("Success", desc, "success");
            currentLanguage = $(this).val();

            $(".languageChange").each(function () {
                if (currentLanguage == 'english')
                {
                    enVal = $(this).data('english');
                    $(this).html(enVal);
                }
               

            });
        } else if (type == 'error')
        {
            swal(title, desc, "error");
        } else {
            swal({
                title: title,
                text: desc,
                showConfirmButton: false
            });
        }

    }

    function hideoverlay()
    {
        swal.close();
    } 

    function openSuccess(title,url=''){

        swal({
            title: "Success",
            text: title,
            type: "success",
            showCancelButton: false,
            confirmButtonClass: 'btn-success waves-effect waves-light',
            confirmButtonText: 'Okay',
            closeOnConfirm: false,
        }).then(function(isConfirm) {
            if(url==''){
              location.reload();
            }else{
              $(location).prop('href', url);
            }            
        });
    }

    function logOut(){ 

            $.post('<?=BASEURL;?>Index/Logout/',{'logout':true}, function (response) { 
                newResp = JSON.parse(response);
            if(newResp['status'] == 'success'){ 
                 swal({
                        title: "<?= Raise::t('login', 'suc'); ?>",
                        text: "<?= Raise::t('login', 'logout_suc'); ?>",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: 'btn-success',
                        confirmButtonText: '<?= Raise::t('login', 'ok'); ?>',
                    
                    }).then(function (result) {
                        $(location).prop('href', '<?=BASEURL;?>');
                    })
            }
            else{
                loadingoverlay('error',"<?=Raise::t('app','error_dash');?>",newResp['response']);
            }

                return false;
            });
        return false;
    }

function setdate(fullDate,get=false){
   var dd = fullDate.getDate();
   var mm = fullDate.getMonth()+1;
   var yy = fullDate.getFullYear();

   dd = (dd < 10)?('0'+dd):dd;
   mm = (mm < 10)?('0'+mm):mm;

   if(get){
     return dd+ "-" + mm + "-" + yy;   
   }

   var todayFrom = dd + "-" + mm + "-" + yy ; 

   $('#datefrom').val(todayFrom);
   $('#dateto').val(todayFrom);
}

$(function (){

    $('.date-today').click(function(){ 
        var fullDate = new Date();
        setdate(fullDate);
    });

    $('.date-yesterday').click(function(){
        var today = new Date();
        var fullDate = new Date(today);
        fullDate.setDate(today.getDate() - 1);
        setdate(fullDate);
    });

    $('.date-seven').click(function(){
        var today = new Date();
        var fullDate = new Date(today);
        fullDate.setDate(today.getDate() - 7);

        var todayFrom = setdate(fullDate,true) ;

        var fullDatea = new Date(today);

        fullDatea.setDate(today.getDate() - 1);

        var todayTo = setdate(fullDatea,true); 

         $('#datefrom').val(todayFrom); 
         $('#dateto').val(todayTo);
    });
});

function selfrom()
    {
        document.getElementById("dateto").required = true;
        minDate=$('#datefrom').val();
        $("#dateto").required = true;
        $('#dateto').prop('min', minDate);
    }
    function selto()
    {
        document.getElementById("datefrom").required = true;
        maxDate=$('#dateto').val();
       $("#datefrom").required = true;
       $('#datefrom').prop('max', maxDate);
    }

</script>

