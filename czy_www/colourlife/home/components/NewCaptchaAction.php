<?php

class NewCaptchaAction extends CCaptchaAction
{
    public static $codeSet = '3456789ABCDEFGHJKLMNPQRTUVWXY';

    /**
     * Renders the CAPTCHA image based on the code using GD library.
     * @param string $code the verification code
     * @since 1.1.13
     */
    protected function renderImageGD($code)
    {
        $image = imagecreatetruecolor($this->width,$this->height);

        $backColor = imagecolorallocate($image,
            (int)($this->backColor % 0x1000000 / 0x10000),
            (int)($this->backColor % 0x10000 / 0x100),
            $this->backColor % 0x100);
        imagefilledrectangle($image,0,0,$this->width,$this->height,$backColor);
        imagecolordeallocate($image,$backColor);

        if($this->transparent)
            imagecolortransparent($image,$backColor);

        $foreColor = imagecolorallocate($image,
            (int)($this->foreColor % 0x1000000 / 0x10000),
            (int)($this->foreColor % 0x10000 / 0x100),
            $this->foreColor % 0x100);

        if($this->fontFile === null)
            $this->fontFile = dirname(__FILE__) . '/newline.ttf';

        //干扰线
//        for ($i=0; $i<10; $i++) {
//            $x1 = rand(-10,$this->width+10);
//            $y1 = rand(-10,$this->height+10);
//            $x2 = rand(-10,$this->width+10);
//            $y2 = rand(-10,$this->height+10);
//            imageline($image,$x1,$y1,$x2,$y2,imagecolorallocate($image,rand(50, 180),rand(50, 180),rand(50, 180)));
//        }
        //干扰数字
        for($i = 0; $i < 10; $i++){
            //杂点颜色
            $noiseColor = imagecolorallocate(
                $image,
                mt_rand(150,225),
                mt_rand(150,225),
                mt_rand(150,225)
            );
            for($j = 0; $j < 5; $j++) {
                // 绘杂点
                imagestring(
                    $image,
                    5,
                    mt_rand(-10, $this->width),
                    mt_rand(-10, $this->height),
                    self::$codeSet[mt_rand(0, 28)], // 杂点文本为随机的字母或数字
                    $noiseColor
                );
            }
        }
        //干扰曲线
        $A = mt_rand(1, $this->height/2);                  // 振幅   
        $b = mt_rand(-$this->height/4, $this->height/4);   // Y轴方向偏移量   
        $f = mt_rand(-$this->height/4, $this->height/4);   // X轴方向偏移量   
        $T = mt_rand($this->height*1.5, $this->width*2);  // 周期   
        $w = (2* M_PI)/$T;

        $px1 = 0;  // 曲线横坐标起始位置   
        $px2 = mt_rand($this->width/2, $this->width * 0.667);  // 曲线横坐标结束位置              
        for ($px=$px1; $px<=$px2; $px=$px+ 0.9) {
            if ($w!=0) {
                $py = $A * sin($w*$px + $f)+ $b + $this->height/2;  // y = Asin(ωx+φ) + b   
                $i = (int) ((26 - 6)/4);
                while ($i > 0) {
                    imagesetpixel($image, $px + $i, $py + $i, $foreColor);
                    //这里画像素点比imagettftext和imagestring性能要好很多                     
                    $i--;
                }
            }
        }

        $A = mt_rand(1, $this->height/2);                  // 振幅           
        $f = mt_rand(-$this->height/4, $this->height/4);   // X轴方向偏移量   
        $T = mt_rand($this->height*1.5, $this->width*2);  // 周期   
        $w = (2* M_PI)/$T;
        $b = $py - $A * sin($w*$px + $f) - $this->height/2;
        $px1 = $px2;
        $px2 = $this->width;
        for ($px=$px1; $px<=$px2; $px=$px+ 0.9) {
            if ($w!=0) {
                $py = $A * sin($w*$px + $f)+ $b + $this->height/2;  // y = Asin(ωx+φ) + b   
                $i = (int) ((26 - 8)/4);
                while ($i > 0) {
                    imagesetpixel($image, $px + $i, $py + $i, $foreColor);
                    //这里(while)循环画像素点比imagettftext和imagestring用字体大小一次画出
                    //的（不用while循环）性能要好很多       
                    $i--;
                }
            }
        }

        $length = strlen($code);
        $box = imagettfbbox(30,0,$this->fontFile,$code);
        $w = $box[4] - $box[0] + $this->offset * ($length - 1);
        $h = $box[1] - $box[5];
        $scale = min(($this->width - $this->padding * 2) / $w,($this->height - $this->padding * 2) / $h);
        $x = 10;
        $y = round($this->height * 27 / 40);
        for($i = 0; $i < $length; ++$i)
        {
            $fontSize = (int)(rand(26,32) * $scale * 0.9);
            $angle = rand(-10,10);
            $letter = $code[$i];
            $box = imagettftext($image,$fontSize,$angle,$x,$y,$foreColor,$this->fontFile,$letter);
            $x = $box[2] + $this->offset;
        }



        imagecolordeallocate($image,$foreColor);

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Transfer-Encoding: binary');
        header("Content-Type: image/png");
        imagepng($image);
        imagedestroy($image);
    }

    /**
     * Renders the CAPTCHA image based on the code using ImageMagick library.
     * @param string $code the verification code
     * @since 1.1.13
     */
    protected function renderImageImagick($code)
    {
        $backColor=$this->transparent ? new ImagickPixel('transparent') : new ImagickPixel(sprintf('#%06x',$this->backColor));
        $foreColor=new ImagickPixel(sprintf('#%06x',$this->foreColor));

        $image=new Imagick();
        $image->newImage($this->width,$this->height,$backColor);

        if($this->fontFile===null)
            $this->fontFile=dirname(__FILE__).'/SpicyRice.ttf';

        $draw=new ImagickDraw();
        $draw->setFont($this->fontFile);
        $draw->setFontSize(30);
        $fontMetrics=$image->queryFontMetrics($draw,$code);

        $length=strlen($code);
        $w=(int)($fontMetrics['textWidth'])-8+$this->offset*($length-1);
        $h=(int)($fontMetrics['textHeight'])-8;
        $scale=min(($this->width-$this->padding*2)/$w,($this->height-$this->padding*2)/$h);
        $x=10;
        $y=round($this->height*27/40);
        for($i=0; $i<$length; ++$i)
        {
            $draw=new ImagickDraw();
            $draw->setFont($this->fontFile);
            $draw->setFontSize((int)(rand(26,32)*$scale*0.8));
            $draw->setFillColor($foreColor);
            $image->annotateImage($draw,$x,$y,rand(-10,10),$code[$i]);
            $fontMetrics=$image->queryFontMetrics($draw,$code[$i]);
            $x+=(int)($fontMetrics['textWidth'])+$this->offset;
        }

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Transfer-Encoding: binary');
        header("Content-Type: image/png");
        $image->setImageFormat('png');
        echo $image;
    }
    public function run()
	{
		if(isset($_GET[self::REFRESH_GET_VAR]))  // AJAX request for regenerating code
		{
			$code=$this->getVerifyCode(true);
			echo CJSON::encode(array(
				'hash1'=>$this->generateValidationHash($code),
				'hash2'=>$this->generateValidationHash(strtolower($code)),
				// we add a random 'v' parameter so that FireFox can refresh the image
				// when src attribute of image tag is changed
				'url'=>$this->getController()->createUrl($this->getId(),array('v' => uniqid())),
			));
		}
		else
			$this->renderImage($this->getVerifyCode(true));
		Yii::app()->end();
	}
}

