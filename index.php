<!DOCTYPE html> 
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div>
<?php 
    require_once dirname(__FILE__) . '/tth.php';
    
    use JoomLike\Hash;
    
    $file = 'index.html';
    echo "TTH for $file:  ";
    echo TTH::getTTH($file).'<BR>';
    
    $file = 'readme.md';
    echo "TTH for $file:  ";
    echo TTH::getTTH($file).'<BR>';
?>
        </div>
    </body>
</html>
