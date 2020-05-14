<?php
use Carbon\Carbon;

function upload(object $file, string $uploadPath,string $oldFile =null)
{
    //$files it can be array incase of multi files, and it can be object in case of single file
    //delete old files
    $path="";//return $path of file
    $file_path = public_path($oldFile);
    if($file_path){
        if(file_exists($oldFile))
        {
            unlink($file_path);
        }
    }//end oldfiles
    //upload file

    if(gettype($file) == 'object'){
        $file_name = random_int(10,9999).strtotime(Carbon::now());
        $extension = $file->getClientOriginalExtension();
        $fileNameToStore= $file_name.'.'.$extension;
        $path= $file->move($uploadPath, $fileNameToStore);
        $path=$path->getPathname();
    }
    return $path;
}

function avatarPath()
{
    return 'storage/avatars/';
}
