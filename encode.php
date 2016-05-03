<?php

if(isset($_POST)){
    $code =  $_POST['code'];
    $code = base64_encode($code);
   
    echo '<?php  echo base64_decode("'.$code.'") ?>';
}

