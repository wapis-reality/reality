<?php
if(!empty($_FILES)) {
    $files = array();
    foreach ($_FILES as $f) {
        $file = $f['name'];
        $file_name = substr($file, 0, strrpos($file, '.')) . "_" . substr(Md5(rand()), 0, 8) . substr($file, strrpos($file, '.') - 1);
        if (move_uploaded_file($f['tmp_name'], "../uploaded/" . $file_name))
            $files[] = $file_name;
    }
    echo json_encode($files);
}
//print_r($_FILES['upload']);
//$file = $_FILES['upload']['name'];
/*$file_name = substr($file, 0, strrpos($file, '.'))."_".substr(Md5(rand()),0,8).substr($file,strrpos($file, '.')-1);
if(move_uploaded_file($_FILES['upload']['tmp_name'],"../uploaded/".$file_name))
    echo $file_name;
else
    echo "error";*/
?>