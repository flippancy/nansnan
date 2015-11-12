<?php
function randomkeys($length){
 $output='';
 for($a=0;$a<$length;$a++){
  $output.=chr(mt_rand(33,126));//生成php随机数
 }
 return $output;
}
?>