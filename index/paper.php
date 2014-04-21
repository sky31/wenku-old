<?php

header('Connection: close');
header('Content-Type: application/x-shockwave-flash');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');


$pn = $_GET['pn'] + 1;

readfile('./static/swf/test/b'.$pn.'.swf');