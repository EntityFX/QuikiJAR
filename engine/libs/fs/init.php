<form action="/engine/libs/fs/init.php" method=post enctype=multipart/form-data>
<input type=file name=uploadFile>
<input type=submit value="Send">
</form>
<?php 
require_once "FS.php";

$fss = new FS();
$uploadDir = "/engine/libs/fs/new name 2";
$newPath = "/engine/libs/fs/4";
$path = "/home/timur/www/quki.ru/engine/libs/fs/";

//var_dump($fss->copyElement($oldPath, $newPath, true));
var_dump($fss->upload2($uploadDir, $_FILES));
?>
