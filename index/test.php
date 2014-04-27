<?php
//  "MSIE 10.0"  
// "MSIE 6.0" "MSIE 7.0" "MSIE 8.0" "MSIE 9.0"

$str = "是d哈哈第三届李开复";
//echo strlen($a);
echo mb_substr($str, 0, 5,'utf-8');



exit();
echo($_SERVER['HTTP_USER_AGENT']);
?>
<br /><br />
<script>
var sUserAgent = window.navigator.userAgent
document.write(sUserAgent);
</script>