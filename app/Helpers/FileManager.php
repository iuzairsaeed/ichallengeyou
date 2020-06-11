<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\File; 

function uploadFile(object $file, string $uploadPath, string $oldFile = null)
{
    // $files it can be array incase of multi files, and it can be object in case of single file

    $fileNameToStore = "";
    $file_path = public_path($oldFile);

    if($file_path){
        if(file_exists($oldFile)){
            unlink($file_path);
        }
    }

    if(gettype($file) == 'object'){
        $fileNameToStore = $file->hashName();
        if (config('app.env') == 'testing'){
            $path = $file->move('storage/framework/testing/disks/storage/', $fileNameToStore);
        }else{
            $path = $file->move($uploadPath, $fileNameToStore);
        }
    }

    return $fileNameToStore;
}

function deleteFile(string $fileName, string $uploadPath)
{
    $file_path = public_path($fileName);
    if($file_path){
        if(file_exists($file_path)){
            unlink($file_path);
        }
    }
}

function avatarsPath()
{
    return 'storage/avatars/';
}

function challengesPath()
{
    return 'storage/challenges/';
}

function SubmitChallengesPath()
{
    return 'storage/submited/';
}