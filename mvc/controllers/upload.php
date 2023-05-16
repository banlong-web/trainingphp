<?php

class Upload
{
    public function __construct()
    {
        # code...
    }
    function uploadImg()
    {
        $targetDir = UPLOAD_ROOT;
        $allowTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $error = '';
        $productFileName = $_FILES['product_img']['name'];
        $productFeaturedFile = $targetDir . basename($_FILES['product_img']['name']);
        $imageProductType = pathinfo($productFeaturedFile,PATHINFO_EXTENSION);
        if(in_array($imageProductType, $allowTypes)){
            move_uploaded_file($_FILES["product_img"]["tmp_name"], $productFeaturedFile);
        } else {
            $error = "File extension error! Please selected the allowed file type only!";
        }
        $output = [
            'pathName'  => URL_BASE.$productFileName,
            'error'     => $error
        ];
        echo json_encode($output);
    }
    public function uploadGallery()
    {
        $countfiles = count($_FILES['product_gallery']['name']);
        $targetDir = UPLOAD_ROOT;
        $allowTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $error = '';
        for($index = 0;$index < $countfiles;$index++){
            if(isset($_FILES['product_gallery']['name'][$index]) && $_FILES['product_gallery']['name'][$index] != ''){
                $fileName = $_FILES['product_gallery']['name'][$index];
                $targetFilePath = $targetDir . $fileName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                if(in_array($fileType, $allowTypes)){
                    move_uploaded_file($_FILES['product_gallery']['tmp_name'][$index],$targetFilePath);
                    $files_arr[] = URL_BASE.$fileName;
                } else {
                    $error = "File extension error! Please selected the allowed file type only!";
                }
            }
        }
        $output = [
            'pathName'  => $files_arr,
            'error'     => $error
        ];
        echo json_encode($output);
    }	
}