<?php
    function gaussian_blur($srcImg,$savepath=null,$savename=null,$blurFactor=3){
        $gdImageResource=image_create_from_ext($srcImg);
        $srcImgObj=blur($gdImageResource,$blurFactor);
        $temp = pathinfo($srcImg);
        $name = $temp['basename'];
        $path = $temp['dirname'];
        $exte = $temp['extension'];
        $savename = $savename ? $savename : $name;
        $savepath = $savepath ? $savepath : $path;
        $savefile = $savepath .'/'. $savename;
        $srcinfo = @getimagesize($srcImg);
        switch ($srcinfo[2]) {
            case 1: imagegif($srcImgObj, $savefile); break;
            case 2: imagejpeg($srcImgObj, $savefile); break;
            case 3: imagepng($srcImgObj, $savefile); break;
            default: return 'fail';
        }

        imagedestroy($srcImgObj);
        return $savefile;

    }

    function blur($gdImageResource, $blurFactor = 3)
    {
        $blurFactor = round($blurFactor);

        $originalWidth = imagesx($gdImageResource);
        $originalHeight = imagesy($gdImageResource);

        $smallestWidth = ceil($originalWidth * pow(0.5, $blurFactor));
        $smallestHeight = ceil($originalHeight * pow(0.5, $blurFactor));
        $prevImage = $gdImageResource;
        $prevWidth = $originalWidth;
        $prevHeight = $originalHeight;
        for($i = 0; $i < $blurFactor; $i += 1)
        {    
            $nextWidth = $smallestWidth * pow(2, $i);
            $nextHeight = $smallestHeight * pow(2, $i);
            $nextImage = imagecreatetruecolor($nextWidth, $nextHeight);
            imagecopyresized($nextImage, $prevImage, 0, 0, 0, 0, 
              $nextWidth, $nextHeight, $prevWidth, $prevHeight);
            imagefilter($nextImage, IMG_FILTER_GAUSSIAN_BLUR);
            $prevImage = $nextImage;
            $prevWidth = $nextWidth;
            $prevHeight = $nextHeight;
        }
        imagecopyresized($gdImageResource, $nextImage, 
        0, 0, 0, 0, $originalWidth, $originalHeight, $nextWidth, $nextHeight);
        imagefilter($gdImageResource, IMG_FILTER_GAUSSIAN_BLUR);
        imagedestroy($prevImage);
        return $gdImageResource;
    }
    function image_create_from_ext($imgfile)
    {
        $info = getimagesize($imgfile);
        $im = null;
        switch ($info[2]) {
        case 1: $im=imagecreatefromgif($imgfile); break;
        case 2: $im=imagecreatefromjpeg($imgfile); break;
        case 3: $im=imagecreatefrompng($imgfile); break;
        }
        return $im;
    }
?>