<?php

// convert Zoo Rec Endnote dump to RIS

require_once(dirname(__FILE__) . '/endnote.php');
require_once(dirname(__FILE__) . '/utils.php');

function convert($reference)
{
	echo reference_to_ris($reference);
}


$filename = '';
if ($argc < 2)
{
	echo "Usage: import.php <RIS file> <mode>\n";
	exit(1);
}
else
{
	$filename = $argv[1];
}


$file = @fopen($filename, "r") or die("couldn't open $filename");
fclose($file);

import_endnote_file($filename, 'convert');


?>