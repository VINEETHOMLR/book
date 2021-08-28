

<?php

use inc\Raise;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>SPCI</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico"/>

    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="<?= BASEURL ?>web/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= BASEURL ?>web/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="<?= BASEURL ?>web/assets/css/authentication/form-2.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?= BASEURL ?>web/assets/css/forms/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL ?>web/assets/css/forms/switches.css">
    <link rel="stylesheet" type="text/css" href="<?=BASEURL;?>web/assets/css/components/custom-sweetalert.css"/>

</head>
<body class="form">
    

    <title><?= $this->subTtitle ?></title>

    <?php include("scripts_top.php"); ?>
    <?php print_r($this->content) ?>

</body>
</html>


<script type="text/javascript">

    $(document).keypress(function(event){  
        var keycode = (event.keyCode ? event.keyCode : event.which); 
        if(keycode == '13'){
           $('#login').trigger('click');   
        }
    });

    $(document).ready(function(){ 

        $('#login').click(function(){ 

            var user = $('#user').val();
            var pass= $('#pass').val();
 
            $.post('<?= BASEURL;?>Index/LoginCheck/', {"username":user,"password":pass }, function(response) { 
               newResp = JSON.parse(response);
                if(newResp['status']=="success"){

                       swal({
                        title: "<?= Raise::t('login', 'suc'); ?>",
                        text: newResp['response'],
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: 'btn-success',
                        confirmButtonText: '<?= Raise::t('login', 'ok'); ?>',
                        
                    }).then(function (result) {
                        $(location).prop('href', '<?=BASEURL;?>Shipment/Index/');
                    })

                }else{
                     swal('<?= Raise::t('login', 'error_txt'); ?>', newResp['response'], "error");
                       return false;
                }
             }); 
        });
    });

</script>

</body>

</html>



