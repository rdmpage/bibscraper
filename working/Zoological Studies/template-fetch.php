<?php

// Template to fecth URLs

require_once('../../lib.php');

$urls = array(
'http://zoolstud.sinica.edu.tw/331.html',
'http://zoolstud.sinica.edu.tw/332.html',
'http://zoolstud.sinica.edu.tw/333.html',
'http://zoolstud.sinica.edu.tw/334.html',
'http://zoolstud.sinica.edu.tw/341.html',
'http://zoolstud.sinica.edu.tw/342.html',
'http://zoolstud.sinica.edu.tw/343.html',
'http://zoolstud.sinica.edu.tw/344.html',
'http://zoolstud.sinica.edu.tw/34s.html',
'http://zoolstud.sinica.edu.tw/351.html',
'http://zoolstud.sinica.edu.tw/352.html',
'http://zoolstud.sinica.edu.tw/353.html',
'http://zoolstud.sinica.edu.tw/354.html',
'http://zoolstud.sinica.edu.tw/361.html',
'http://zoolstud.sinica.edu.tw/362.html',
'http://zoolstud.sinica.edu.tw/363.html',
'http://zoolstud.sinica.edu.tw/364.html',
'http://zoolstud.sinica.edu.tw/371.html',
'http://zoolstud.sinica.edu.tw/372.html',
'http://zoolstud.sinica.edu.tw/373.html',
'http://zoolstud.sinica.edu.tw/374.html',
'http://zoolstud.sinica.edu.tw/381.html',
'http://zoolstud.sinica.edu.tw/382.html',
'http://zoolstud.sinica.edu.tw/383.html',
'http://zoolstud.sinica.edu.tw/384.html',
'http://zoolstud.sinica.edu.tw/391.html',
'http://zoolstud.sinica.edu.tw/392.html',
'http://zoolstud.sinica.edu.tw/393.html',
'http://zoolstud.sinica.edu.tw/394.html',
'http://zoolstud.sinica.edu.tw/401.html',
'http://zoolstud.sinica.edu.tw/402.html',
'http://zoolstud.sinica.edu.tw/403.html',
'http://zoolstud.sinica.edu.tw/404.html',
'http://zoolstud.sinica.edu.tw/411.html',
'http://zoolstud.sinica.edu.tw/412.html',
'http://zoolstud.sinica.edu.tw/413.html',
'http://zoolstud.sinica.edu.tw/414.html',
'http://zoolstud.sinica.edu.tw/421.html',
'http://zoolstud.sinica.edu.tw/422.html',
'http://zoolstud.sinica.edu.tw/423.html',
'http://zoolstud.sinica.edu.tw/424.html',
'http://zoolstud.sinica.edu.tw/431.html',
'http://zoolstud.sinica.edu.tw/432.html',
'http://zoolstud.sinica.edu.tw/433.html',
'http://zoolstud.sinica.edu.tw/434.html',
'http://zoolstud.sinica.edu.tw/441.html',
'http://zoolstud.sinica.edu.tw/442.html',
'http://zoolstud.sinica.edu.tw/443.html',
'http://zoolstud.sinica.edu.tw/444.html',
'http://zoolstud.sinica.edu.tw/451.html',
'http://zoolstud.sinica.edu.tw/452.html',
'http://zoolstud.sinica.edu.tw/453.html',
'http://zoolstud.sinica.edu.tw/454.html',
'http://zoolstud.sinica.edu.tw/461.html',
'http://zoolstud.sinica.edu.tw/462.html',
'http://zoolstud.sinica.edu.tw/463.html',
'http://zoolstud.sinica.edu.tw/464.html',
'http://zoolstud.sinica.edu.tw/465.html',
'http://zoolstud.sinica.edu.tw/466.html',
'http://zoolstud.sinica.edu.tw/471.html',
'http://zoolstud.sinica.edu.tw/472.html',
'http://zoolstud.sinica.edu.tw/473.html',
'http://zoolstud.sinica.edu.tw/474.html',
'http://zoolstud.sinica.edu.tw/475.html',
'http://zoolstud.sinica.edu.tw/476.html',
'http://zoolstud.sinica.edu.tw/481.html',
'http://zoolstud.sinica.edu.tw/482.html',
'http://zoolstud.sinica.edu.tw/483.html',
'http://zoolstud.sinica.edu.tw/484.html',
'http://zoolstud.sinica.edu.tw/485.html',
'http://zoolstud.sinica.edu.tw/486.html',
'http://zoolstud.sinica.edu.tw/491.html',
'http://zoolstud.sinica.edu.tw/492.html',
'http://zoolstud.sinica.edu.tw/493.html',
'http://zoolstud.sinica.edu.tw/494.html',
'http://zoolstud.sinica.edu.tw/495.html',
'http://zoolstud.sinica.edu.tw/496.html',
'http://zoolstud.sinica.edu.tw/501.html',
'http://zoolstud.sinica.edu.tw/502.html',
'http://zoolstud.sinica.edu.tw/503.html',
'http://zoolstud.sinica.edu.tw/504.html',
'http://zoolstud.sinica.edu.tw/505.html',
'http://zoolstud.sinica.edu.tw/506.html',
'http://zoolstud.sinica.edu.tw/511.html',
'http://zoolstud.sinica.edu.tw/512.html',
'http://zoolstud.sinica.edu.tw/513.html',
'http://zoolstud.sinica.edu.tw/514.html',
'http://zoolstud.sinica.edu.tw/515.html',
'http://zoolstud.sinica.edu.tw/516.html',
'http://zoolstud.sinica.edu.tw/517.html',
'http://zoolstud.sinica.edu.tw/518.html',
'http://zoolstud.sinica.edu.tw/52.htm',
'http://zoolstud.sinica.edu.tw/53.htm',
'http://zoolstud.sinica.edu.tw/54.htm',
'http://zoolstud.sinica.edu.tw/55.htm',
'http://zoolstud.sinica.edu.tw/55.htm',
'http://zoolstud.sinica.edu.tw/56.htm');

$basedir = 'html';

foreach ($urls as $url)
{
	$html = get($url);
	
	$filename = str_replace('http://zoolstud.sinica.edu.tw/', '', $url);
	
	file_put_contents(dirname(__FILE__) . '/' . $basedir . '/' . $filename, $html);

}

?>