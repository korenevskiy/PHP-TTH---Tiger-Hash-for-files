<?php
error_reporting( E_ALL );
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//http://joomlike.tmweb.ru/joomlas/index.php
define('JOOMLIKE',TRUE);
echo '+'.(file_exists(dirname(__FILE__) . '/translator.php')).'+';
require_once dirname(__FILE__) . '/translator.php';

//$result = TTranclator::StartScan(dirname(__FILE__) . '/joomla_3.5.1/');
$path = dirname(__FILE__) . '/joomla_3.5.1';
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div><?= "path ".$path;?>
            <pre><?php 
$file = 'Joomla_3.5.1-Stable-Full_Package.zip';//68e57faa4b59db3781a35ac858ce1a672e84f513e3cc3244 5.3 //37db594baa7fe568671ace58c85aa3814432cce313f5842e 5.6
echo phpversion().'<BR>';
$h = hash("tiger192,3", $file, 0);
//$h=array_reverse($h);
//foreach ($h as $i)     echo (string)$i;
echo $h.'<BR>';
echo $file.'<BR>';
 echo TTH::getTTH( $file).'<BR>';//DG7EJ5EZQHHTZDITWSPEY4DELAJYYMO46IJ3LMQ  //4FQOTN2CNX4XESRJKFT7HABLJZ42RWQJG2I5HSA -Правильный
/**                               Занесение файлов в базу
//$result = TTranslator::StartScan(dirname(__FILE__) . '/joomla_test');
$result = TTranslator::StartScan($path);
echo '<BR>'.print_r($result,true);
 */
            
            
//    [path]    => /home/e/exoffice/joomlike.tmweb.ru/public_html/joomlas
//    [dirs]    => 1617
//    [files]   => 2474
//    [links]   => 0
//    [message] => 00000000000000000
?>
            </pre></div>
    </body>
</html>
