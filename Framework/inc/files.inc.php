<?php


function uploadImage($fieldName,$uploadPath = "/",$allowedExtensions = "jpg"){
    $DP = new DataProcessor();
    $returnStatus = "";
$allowedString =     $allowedExtensions;
    $allowedExtensions = explode("|", $allowedExtensions);
    $returnStatus= 'Extension Invalid. Allowed Extensions: '.$allowedString;
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $image =$_FILES[$fieldName]["name"];
        $uploadedfile = $_FILES[$fieldName]['tmp_name'];

        if ($image){
            $filename = stripslashes($_FILES[$fieldName]['name']);
            $extension = getExtension($filename);
            $extension = strtolower($extension);
            if (!in_array($extension, $allowedExtensions))
            {
                $returnStatus=  'Unknown Image extension ';
            }else{
                $size=filesize($_FILES[$fieldName]['tmp_name']);

                if ($size > IMAGE_MAX_SIZE*1024  ||  $size == false)
                {
                    $returnStatus= "You have exceeded the size limit";
                    return $returnStatus;
                }

                if($extension=="jpg" || $extension=="jpeg" )
                {
                    $uploadedfile = $_FILES[$fieldName]['tmp_name'];
                    $src = imagecreatefromjpeg($uploadedfile);
                }
                else if($extension=="png")
                {
                    $uploadedfile = $_FILES[$fieldName]['tmp_name'];
                    $src = imagecreatefrompng($uploadedfile);
                }
                else
                {
                    $src = imagecreatefromgif($uploadedfile);
                }
                $maxSquare = 0;
                list($width,$height)=getimagesize($uploadedfile);
                if($width > $height){
                    $maxSquare = $height;
                }else{
                    $maxSquare = $width;
                }
                $squareImage=imagecreatetruecolor($maxSquare,$maxSquare);
                $originalUploadImage=imagecreatetruecolor($width,$height);
                
                $cutX = 0 + (($width-$maxSquare)/2);
                $cutY = 0 + (($height-$maxSquare)/2);
                //echo "<br /><br />Original: W:$width - H:$height<br />";
                //echo "New: W:$cutX - H:$cutY<br />";
                //echo "Max Square Dimensions: $maxSquare<br />";

                imagecopyresampled  ( $squareImage  , $src  , 0  , 0  , $cutX  , $cutY  , $maxSquare  , $maxSquare  , $maxSquare   , $maxSquare  );
                
                $imagesSizes = array(150,100,75,50);
                $finalNameFile = date("YmdGis").$DP->createPassword(10).".".$extension;
                $filename = $uploadPath. $finalNameFile;
                imagejpeg($src,$filename,100);
                foreach($imagesSizes as $s){
                    $newwidth=$s;

                    $newheight=($height/$width)*$newwidth;

                    $tmp=imagecreatetruecolor($s,$s);

                    imagecopyresampled($tmp,$squareImage,0,0,0,0,$s,$s,$maxSquare,$maxSquare);
                    if($s != 500){
                        $filename = $uploadPath.$s."/". $finalNameFile;
                    }else{
                        $filename = $uploadPath. $finalNameFile;
                    }
                    imagejpeg($tmp,$filename,100);
                    imagedestroy($tmp);
                }
                
                imagedestroy($src);
                $returnStatus ="file:".$finalNameFile;
            }
            
        }
    }
    return $returnStatus;
}
function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }
function getFolderFiles($folder,$allowedExtensions){
    if(!is_array($allowedExtensions)){
        return false;
    }
    
    if ($handle = opendir($folder)) {
        $returnArray = array();
        while (false !== ($file = readdir($handle))) {
            //echo "$file<br />";
            if(in_array(getExtension($file), $allowedExtensions)){
                $returnArray[] = $file;
            }
        }
        return $returnArray;
    }else{
        return false;
    }
}
 ?>
