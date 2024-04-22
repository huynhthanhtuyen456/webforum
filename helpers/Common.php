<?php
/**
* Return an array of posts with the user id in the $list
*/
namespace MVC\Helpers;


class Common
{   
    public static function upload_file()
    {
        if (isset($_FILES['image'])){
            //there is 1 file to be uploaded
            $folder = '/uploads/';		//You MUST create a folder in your SERVER Directory
            $tmpfile = $_FILES['image']['tmp_name'];
            $filename = basename($_FILES['image']['name']);
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . $folder;	//the file inside your web root folder
            $target_file = $target_dir . $filename;

            $result_upload_file = move_uploaded_file($tmpfile, $target_file);
            if($result_upload_file) return $folder.$filename;
        }
        return;
    }
}
?>