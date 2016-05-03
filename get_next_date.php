<?php
function check_time(){
    $current_date = date('Hi');
  if($current_date >=1130){
      $check_date = 2;
  }else{
      $check_date = 1;
  }
  return $check_date;
}
  
?>
