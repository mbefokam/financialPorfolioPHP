<?php
// create a file pointer connected to a memory stream
$output = fopen('php://memory');
$googleSite = ("https://www.google.com/finance/info?q=NSE:ATULAUTO&f=etl");

// output the column headings
fputcsv($output, $googleSite);

$googleParse = file("https://www.google.com/finance/info?q=NSE:ATULAUTO&f=etl");
$googleParse[0] = "";
$googleParse[1] = "";
$googleParse[2] = "";


$garbageToReplace = array('"id": ',
                          ',"t" : ',
                          ',"e" : ',
                          ',"l" : ',
                          ',"l_fix" : ',
                          ',"l_cur" : ',
                          ',"s": ',
                          ',"ltt":',
                          ',"lt" : ',
                          ',"lt_dts" : ',
                          ',"c" : ',
                          ',"c_fix" : ',
                          ',"cp" : ',
                          ',"cp_fix" : ',
                          ',"ccol" : ',
                          ',"pcls_fix" : ',
                          '"');


$googleParse[3] = str_replace($garbageToReplace, "", $googleParse[3]);
$googleParse[4] = str_replace($garbageToReplace, "", $googleParse[4]);
$googleParse[5] = str_replace($garbageToReplace, "", $googleParse[5]);
$googleParse[6] = str_replace($garbageToReplace, "", $googleParse[6]);
$googleParse[7] = str_replace($garbageToReplace, "", $googleParse[7]);
$googleParse[8] = str_replace($garbageToReplace, "", $googleParse[8]);
$googleParse[9] = str_replace($garbageToReplace, "", $googleParse[9]);
$googleParse[10] = str_replace($garbageToReplace, "", $googleParse[10]);
$googleParse[11] = str_replace($garbageToReplace, "", $googleParse[11]);
$googleParse[12] = str_replace($garbageToReplace, "", $googleParse[12]);
$googleParse[13] = str_replace($garbageToReplace, "", $googleParse[13]);
$googleParse[14] = str_replace($garbageToReplace, "", $googleParse[14]);
$googleParse[15] = str_replace($garbageToReplace, "", $googleParse[15]);
$googleParse[16] = str_replace($garbageToReplace, "", $googleParse[16]);
$googleParse[17] = str_replace($garbageToReplace, "", $googleParse[17]);
$googleParse[18] = str_replace($garbageToReplace, "", $googleParse[18]);

$googleParse[19] = "";
$googleParse[20] = "";

print_r($googleParse);
?>