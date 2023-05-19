<?php
function sendReply($code, $msg){
http_response_code($code);
echo $msg;
exit();
}
?>