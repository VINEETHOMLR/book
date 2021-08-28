<!DOCTYPE html>
<html lang="en">
    
    <?php include("header.php"); ?>
    <?php include("scripts_top.php"); ?>

    <body>
        <div class="main-container" id="container">

           <div class="overlay"></div>
           <div class="search-overlay"></div>
             <?php include("sidenav.php"); ?>
             <?php print_r($this->content) ?>
             <?php include("footer.php"); ?>
        </div> 
    </body>
        <?php include("scripts_bot.php"); ?>
</html>