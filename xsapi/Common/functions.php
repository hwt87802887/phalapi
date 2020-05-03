<?php
/**
 * 正则替换图片路径空格
 * @return string val 被替换的字符串
 */
function ipreg_replace($val)
{
  return @preg_replace('/\s/','', $val);
}

/**
 * 正则替换所有html格式
 * @return string val 被替换的字符串
 */

function iclearhtml($content)
{
    $searchaborative = array(
        "/\\<[\\/\\!]*?[^\\<\\>]*?\\>/si",
        "/\\t/",
        "/[\r\n]+/",
        "/(^[\r\n]|[\r\n]\$)+/",
        "/&(quot|#34);/i",
        "/&(amp|#38);/i",
        "/&ldquo;/i",
        "/&rdquo;/i",
        "/&mdash;/i",
        "/&hellip;/i",
        "/&(lt|#60);/i",
        "/&(gt|#62);/i",
        "/&(nbsp|#160|\t);/i",
        "/&(iexcl|#161);/i",
        "/&(cent|#162);/i",
        "/&(pound|#163);/i",
        "/&(copy|#169);/i",
        "/&#(\\d+);/e");
    $replaceaborative = array("", "", "\r\n", "", "\"", "&","“ ","” ","—","…", "<", ">", " ", chr(161), chr(162), chr(163), chr(169), "chr(\\1)");
    $content =  @preg_replace($searchaborative, $replaceaborative, $content);
    $content = str_replace("　","",$content);
    $content = @preg_replace('/\s+/','',$content);
    //$content = @preg_replace("/([\s]{2,})/","\\1",$content); 保留一个
    return $content;
}

/**
 * js跳转
 * @return string val 跳转地址
 */
function ihref($url,$di=0,$msg='')
{
    header("Content-type: text/html; charset=utf-8");
    if($di==1 && $msg!=""){
        echo "<script>alert('".$msg."');location='".$url."';</script>";exit();
    }
    if($di==2 && $msg!="") {
        echo "<script>alert('" . $msg . "');</script>";exit();
    }

    echo "<script>location='".$url."';</script>";exit();
}

/**
 * PHP 将json的stdClass Object转成数组array
 * @return Object
 * */
function object_array($array){
    if(is_object($array)){
        $array = (array)$array;
    }
    if(is_array($array)){
        foreach($array as $key=>$value){
            $array[$key] = object_array($value);
        }
    }
    return $array;
}


/**
 * 检查是否是手机号码
 * @return string val 字符串
 */
function isMobile($mobile) {
    if (!is_numeric($mobile)) {
        return false;
    }
    return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
}

function isQQ($qq) {
    if (!is_numeric($qq)) {
        return false;
    }
    $reg = "#^[1-9][0-9]{4,13}$#";
    return preg_match($reg, $qq) ? true : false;
}

function isTel($tel){
    if($tel==''){
        return false;
    }
    return preg_match('#^(0[0-9]{2,3}-)?([2-9][0-9]{6,7})+(-[0-9]{1,4})?$#', $tel) ? true : false;
}

/**
 * 保留小数点后N位
 * @return string val 被替换的字符串
 */
function inumber_format($val)
{
    return number_format($val,1);
}

/**
 * 获取文件内容
 * @return string $path 文件路径
 */
function ifile_get_contents($path)
{
    $neirong=file_get_contents($path);
    $neirong=str_replace("<? exit();?>","",$neirong);
    $neirong=str_replace("\\","",$neirong);
    $neirong=str_replace('<img','<img width="100%"',$neirong);
    return $neirong;
}

/**
 * 获取文件路径和名称
 * @return string $path 文件路径
 */
function ifileinfo($path){
    if($path=="") return array();
    $pathinfo =explode('/',$path);
    $filename=$pathinfo[count($pathinfo)-1];
    $filepath=str_replace($filename,"",$path);
    $ext=explode('.',$filename);
    return array('name'=>$filename,'path'=>$filepath,'ext'=>$ext);
}
/**
 * 反序列化
 * @return string $serial_str 序列化
 */
function iunserialize($serial_str) {
    $serial_str= @preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );
    $serial_str= str_replace("\r", "", $serial_str);
    return unserialize($serial_str);
}

/**
 * 获取访问设备
 */
function igetdevice()
{
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $type = 'other';
    //分别进行判断
    if(strpos($agent, 'iphone') || strpos($agent, 'ipad')) {
        $type = 'ios';
    }

    if(strpos($agent, 'android')) {
        $type = 'android';
    }
    return $type;
}

//字符截取函数
function isub($string,$start=0,$length,$mode=false,$dot='',$rephtml=0,$char='utf-8'){
    $strlen=strlen($string);
    if($strlen<=$length)
    {
        return $string;
    }

    if($rephtml==0)
    {
        $string = str_replace(array('&nbsp;','&amp;','&quot;','&lt;','&gt;','&#039;'), array(' ','&','"','<','>',"'"), $string);
    }

    $strcut = '';
    if($char== 'utf-8') {

        $n = $tn = $noc = 0;
        while($n < $strlen) {

            $t = ord($string[$n]);
            if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1; $n++; $noc++;
            } elseif(194 <= $t && $t <= 223) {
                $tn = 2; $n += 2; $noc += 2;
            } elseif(224 <= $t && $t < 239) {
                $tn = 3; $n += 3; $noc += 2;
            } elseif(240 <= $t && $t <= 247) {
                $tn = 4; $n += 4; $noc += 2;
            } elseif(248 <= $t && $t <= 251) {
                $tn = 5; $n += 5; $noc += 2;
            } elseif($t == 252 || $t == 253) {
                $tn = 6; $n += 6; $noc += 2;
            } else {
                $n++;
            }

            if($noc >= $length) {
                break;
            }

        }
        if($noc > $length) {
            $n -= $tn;
        }

        $strcut = substr($string, 0, $n);

    } else {
        for($i = 0; $i < $length; $i++) {
            $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
        }
    }

    if($rephtml==0)
    {
        $strcut = str_replace(array('&','"','<','>',"'"), array('&amp;','&quot;','&lt;','&gt;','&#039;'), $strcut);
    }

    return $strcut.$dot;
}





function dhtmlspecialchars($string, $flags = null) {
    if(is_array($string)) {
        foreach($string as $key => $val) {
            $string[$key] = dhtmlspecialchars($val, $flags);
        }
    } else {
        if($flags === null) {
            $string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
            if(strpos($string, '&amp;#') !== false) {
                $string = @preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
            }
        } else {
            if(PHP_VERSION < '5.4.0') {
                $string = htmlspecialchars($string, $flags);
            } else {
                if(strtolower(CHARSET) == 'utf-8') {
                    $charset = 'UTF-8';
                } else {
                    $charset = 'ISO-8859-1';
                }
                $string = htmlspecialchars($string, $flags, $charset);
            }
        }
    }
    return $string;
}


/**
 * 功能和js unescape函数，解码经过escape编码过的数据
 * @param $str
 */
function unescape($str)
{
    $ret = '';
    $len = strlen($str);
    for ($i = 0; $i < $len; $i ++)
    {
        if ($str[$i] == '%' && $str[$i + 1] == 'u')
        {
            $val = hexdec(substr($str, $i + 2, 4));
            if ($val < 0x7f)
                $ret .= chr($val);
            else
                if ($val < 0x800)
                    $ret .= chr(0xc0 | ($val >> 6)) .
                        chr(0x80 | ($val & 0x3f));
                else
                    $ret .= chr(0xe0 | ($val >> 12)) .
                        chr(0x80 | (($val >> 6) & 0x3f)) .
                        chr(0x80 | ($val & 0x3f));
            $i += 5;
        } else
            if ($str[$i] == '%')
            {
                $ret .= urldecode(substr($str, $i, 3));
                $i += 2;
            } else
                $ret .= $str[$i];
    }
    return $ret;
}
/**
 * 功能是js escape php 实现
 * @param $string           the sting want to be escaped
 * @param $in_encoding
 * @param $out_encoding
 */
function escape($string, $in_encoding = 'UTF-8',$out_encoding = 'UCS-2') {
    $return = '';
    if (function_exists('mb_get_info')) {
        for($x = 0; $x < mb_strlen ( $string, $in_encoding ); $x ++) {
            $str = mb_substr ( $string, $x, 1, $in_encoding );
            if (strlen ( $str ) > 1) { // 多字节字符
                $return .= '%u' . strtoupper ( bin2hex ( mb_convert_encoding ( $str, $out_encoding, $in_encoding ) ) );
            } else {
                $return .= '%' . strtoupper ( bin2hex ( $str ) );
            }
        }
    }
    return $return;
}


function yzm($ly,$nd=0)
{
    $jiandan=",的,一,是,在,了,不,和,有,大,这,主,中,人,上,为,们,地,个,用,工,时,要,动,国,产,以,我,到,他,会,作,来,分,生,对,于,学,下,级,就,年,阶,义,发,成,部,民,可,使,性,前";
    $gaonandu=",觊,觎,龃,龉,囹,圄,魍,魉,绔,鳜,耄,饕,餮,躇,偬,倥,谄,媚,偻,裂,釉,蕾,蹀,躞,黠,亵,猥,蟾,惘,趔,趄,窥,觑,肄,秳,茝,疷,淽,絷,懋,袤,岑,铪,龛,氨,犴,獒,翱,鸨,稗,猋,贲,狴";
    $fen=explode(',',$jiandan);
    $fenn=explode(',',$gaonandu);
    $endd=count($fenn);
    $end=count($fen);
    $zi1=rand(1,$end);
    $zi2=rand(1,$end);
    $zi3=rand(1,$end);
    $zi4=rand(1,$end);

    $zhongjian=$fen[$zi1].$fen[$zi2].$fen[$zi3].$fen[$zi4];

    if($nd==1)
    {
        $zi1=rand(1,$endd);
        $zi2=rand(1,$endd);
        $zi3=rand(1,$endd);
        $zi4=rand(1,$endd);
        $zi5=rand(1,$endd);
        $zhongjian=$fenn[$zi1].$fenn[$zi2].$fenn[$zi3].$fenn[$zi4].$fenn[$zi5];
    }elseif($nd==2){
        $zi1=rand(1,$end);
        $zi2=rand(1,$end);
        $zi3=rand(1,$end);
        $zi4=rand(1,$end);
        $zhongjian=$fen[$zi1].$fenn[$zi2].$fen[$zi3].$fenn[$zi4];
    }

    if($nd=="1"){
        $str = $zhongjian;
        $imgWidth = 110;
        $imgHeight = 26;
    }
    else{
        $str = $zhongjian;
        $imgWidth = 90;
        $imgHeight = 26;

    }

    return array($str,$imgWidth,$imgHeight);

}

function yzm2($str,$imgWidth,$imgHeight)
{
    ob_get_clean();
    Header("Content-type: image/PNG");
    $authimg = imagecreate($imgWidth,$imgHeight);
    $bgColor = ImageColorAllocate($authimg,105,215,125);
    $fontfile = "../../xsapi/Public/yzm/simsun.ttc";
    $white=imagecolorallocate($authimg,234,185,95);
    imagearc($authimg, 150, 8, 20, 20, 75, 170, $white);
    imagearc($authimg, 180, 7,50, 30, 75, 175, $white);
    imageline($authimg,20,20,180,30,$white);
    imageline($authimg,20,18,170,50,$white);
    imageline($authimg,25,50,80,50,$white);
    $noise_num = 50;
    $line_num = 20;
    imagecolorallocate($authimg,0xff,0xff,0xff);
    $rectangle_color=imagecolorallocate($authimg,0xAA,0xAA,0xAA);
    $noise_color=imagecolorallocate($authimg,0x00,0x00,0x00);
    $font_color=imagecolorallocate($authimg,0x00,0x00,0x00);

    for($i=0;$i<$noise_num;$i++){
        imagesetpixel($authimg,mt_rand(0,$imgWidth),mt_rand(0,$imgHeight),$noise_color);
    }
    for($i=0;$i<$line_num;$i++){
        imageline($authimg,mt_rand(0,$imgWidth),mt_rand(0,$imgHeight),mt_rand(0,$imgWidth),mt_rand(0,$imgHeight),$line_color);
    }
    $randnum=rand(0,strlen($str)-4);
    if($randnum%2) $randnum+=1;
    ImageTTFText($authimg, 14, 0, 10, 20, $font_color, $fontfile, $str);
    ImagePNG($authimg);
    ImageDestroy($authimg);

}

/**
 * @return string num 数量
 */
function inum($num){
    if($num<10000){
        return $num;
    }else{
        $num = round($num / 10000,1)."万";
        return $num;
    }

}



/*
 *@通过curl方式获取制定的图片到本地
 *@ 完整的图片地址
 *@ 要存储的文件名
 */
function getcurlImg($url = "", $filename = "curl.jpg",$key='') {
    //去除URL连接上面可能的引号
    //$url = preg_replace( '/(?:^['"]+|['"/]+$)/', '', $url );
    $hander = curl_init();
    $fp = fopen($filename,'wb');
    curl_setopt($hander,CURLOPT_URL,$url);
    curl_setopt($hander,CURLOPT_FILE,$fp);
    curl_setopt($hander,CURLOPT_HEADER,0);
    curl_setopt($hander,CURLOPT_FOLLOWLOCATION,1);
    //curl_setopt($hander, CURLOPT_RANGE, '0-300');
    //curl_setopt($hander,CURLOPT_RETURNTRANSFER,false);//以数据流的方式返回数据,当为false是直接显示出来
    curl_setopt($hander,CURLOPT_TIMEOUT,5);
    curl_exec($hander);
    curl_close($hander);
    fclose($fp);

    return getImageInfo($filename,$key);
}


/**
 * 取得图像信息
 *
 * 用法：
 * --------------------------------------
 * $info = getImageInfo('test.jpg');
 * --------------------------------------
 * @param string $image 图像文件名
 *
 * @return mixed
 */
function getImageInfo($image,$key='') {
    $imageInfo = @getimagesize($image);
    if ($imageInfo !== FALSE) {
        $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
        $imageSize = @filesize($image);
        $info = array(
            "width" => $imageInfo[0],
            "height" => $imageInfo[1],
            "type" => $imageType,
            "size" => $imageSize,
            "mime" => $imageInfo['mime']
        );
        if($key!=''){
            return $info[$key];
        }
        return $info;
    } else {
        return FALSE;
    }
}


/**
 * 取得video中的图片
 */
function getImgs($content,$order='ALL'){
    $content = str_replace("\\","",$content);
    $pattern="/<video.*?poster=[\'|\"](.*?(?:[\.gif|\.jpg|\.png|\.bmp|\.jpeg]))[\'|\"].*?[\/]?>/";
    preg_match_all($pattern,$content,$match);
    if(isset($match[1])&&!empty($match[1])){
        if($order==='ALL'){
            return $match[1];
        }
        if(is_numeric($order)&&isset($match[1][$order])){
            return $match[1][$order];
        }
    }
    return '';
}

function getVideo($content){
    $content = str_replace("\\","",$content);
    preg_match_all('/<source[^>]*src\s?=\s?[\'|"]([^\'|"]*)[\'|"]/is', $content, $picarr);
    if(isset($picarr[1][0])){
       return  $picarr[1][0];
    }else{
        return  '';
    }
}

function getImg($content){
    $content = str_replace("\\","",$content);
    preg_match_all('/<img[^>]*src\s?=\s?[\'|"]([^\'|"]*)[\'|"]/is', $content, $picarr);
    if(isset($picarr[1][0])){
        return  $picarr[1][0];
    }else{
        return  '';
    }
}

/**
 * 时间格式化
 */
function format_date($time)
{
    $t = time() - $time;
    $f = array(
        '31536000' => '年',
        '2592000' => '个月',
        '604800' => '星期',
        '86400' => '天',
        '3600' => '小时',
        '60' => '分钟',
        '1' => '秒'
    );
    foreach ($f as $k => $v){
        if (0 != $c = floor($t / (int)$k)){
            return $c . $v . '前';
        }
    }
}

/**
 * 数字格式化
 */
function format_num($num)
{

    $f = array(
        '10000' => '万',
        '1000' => '千',
        '100' => '百',
        '10' => '个'
    );
    foreach ($f as $k => $v){
        if (0 != $c = floor($num / (int)$k)){
            return $c . $v . '字';
        }
    }
}


/**
 * 替换HTML尾标签,为过滤服务
 */
function ireplace_end_tag($str)
{
    if (empty($str)) return false;
    $str = htmlspecialchars($str);
    $str = str_replace( '/', "", $str);
    $str = str_replace("\\", "", $str);
    $str = str_replace("&gt", "", $str);
    $str = str_replace("&lt", "", $str);
    $str = str_replace("<SCRIPT>", "", $str);
    $str = str_replace("</SCRIPT>", "", $str);
    $str = str_replace("<script>", "", $str);
    $str = str_replace("</script>", "", $str);
    $str=str_replace("select","select",$str);
    $str=str_replace("join","join",$str);
    $str=str_replace("union","union",$str);
    $str=str_replace("where","where",$str);
    $str=str_replace("insert","insert",$str);
    $str=str_replace("delete","delete",$str);
    $str=str_replace("update","update",$str);
    $str=str_replace("like","like",$str);
    $str=str_replace("drop","drop",$str);
    $str=str_replace("create","create",$str);
    $str=str_replace("modify","modify",$str);
    $str=str_replace("rename","rename",$str);
    $str=str_replace("alter","alter",$str);
    $str=str_replace("cas","cast",$str);
    $str=str_replace("&","&",$str);
    $str=str_replace(">",">",$str);
    $str=str_replace("<","<",$str);
    $str=str_replace(" ",chr(32),$str);
    $str=str_replace(" ",chr(9),$str);
    $str=str_replace("    ",chr(9),$str);
    $str=str_replace("&",chr(34),$str);
    $str=str_replace("'",chr(39),$str);
    $str=str_replace("<br />",chr(13),$str);
    $str=str_replace("''","'",$str);
    $str=str_replace("css","'",$str);
    $str=str_replace("CSS","'",$str);

    return $str;

}


/**
 * 远程获取数据，GET/POST模式
 * return 远程输出的数据
 */
function getHttpResponse($url,$methods,$post_data = array()){
    //初始化
    $ch = curl_init();
    //设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);//允许链接自动跳转
    if(strtoupper($methods)=="POST"){
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }

    //执行并获取HTML文档内容
    $output = curl_exec($ch);
    //释放curl句柄
    curl_close($ch);

    return $output;
}

/**
 * 获取客户端的ip
 * return IP
 */
function getIP(){

    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
    else
        $ip = "unknown";
    return($ip);
}

/**
 * 随机字符串 长度
 * return 字符串
 */
function getRand($len){
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);
    $output = "";
    for ($i=0; $i<$len; $i++)
    {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}
function getRandNum($len){
    $chars = array(
         "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);
    $output = "";
    for ($i=0; $i<$len; $i++)
    {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

function getRandZm($len){
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);
    $output = "";
    for ($i=0; $i<$len; $i++)
    {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

function getRandGift(){
    $start = 0.1;
    $end = 5.0;
    $rd = round($start + mt_rand() / mt_getrandmax() * ($end - $start),2);
    return $rd;
}

/**
 * 可以统计中文字符串长度的函数
 * @param $str 要计算长度的字符串
 *
 */
function abslength($str)
{
    if(empty($str)){
        return 0;
    }
    if(function_exists('mb_strlen')){
        return mb_strlen($str,'utf-8');
    }
    else {
        preg_match_all("/./u", $str, $ar);
        return count($ar[0]);
    }
}

/**
 * 计算.生肖
 * @param int $year 年份
 * @return str
 */
function get_zodiac($year){
    $animals = array(
        '鼠', '牛', '虎', '兔', '龙', '蛇',
        '马', '羊', '猴', '鸡', '狗', '猪'
    );
    $key = ($year - 1900) % 12;
    return $animals[$key];
}
/**
 * 计算.星座
 * @param int $month 月份
 * @param int $day 日期
 * @return str
 */
function get_constellation($month, $day){
    $signs = array(
        array('20'=>'宝瓶座'), array('19'=>'双鱼座'),
        array('21'=>'白羊座'), array('20'=>'金牛座'),
        array('21'=>'双子座'), array('22'=>'巨蟹座'),
        array('23'=>'狮子座'), array('23'=>'处女座'),
        array('23'=>'天秤座'), array('24'=>'天蝎座'),
        array('22'=>'射手座'), array('22'=>'摩羯座')
    );
    $key = (int)$month - 1;
    list($startSign, $signName) = each($signs[$key]);
    if( $day < $startSign ){
        $key = $month - 2 < 0 ? $month = 11 : $month -= 2;
        list($startSign, $signName) = each($signs[$key]);
    }
    return $signName;
}


// $_Code is utf8 or gb2312
function pinyin($_String, $pix = '', $_Code='utf8')
{
    $_String = strtolower($_String);
    $_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha".
        "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|".
        "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er".
        "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui".
        "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang".
        "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang".
        "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue".
        "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne".
        "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen".
        "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang".
        "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|".
        "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|".
        "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu".
        "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you".
        "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|".
        "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";

    $_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990".
        "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725".
        "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263".
        "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003".
        "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697".
        "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211".
        "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922".
        "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468".
        "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664".
        "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407".
        "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959".
        "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652".
        "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369".
        "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128".
        "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914".
        "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645".
        "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149".
        "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087".
        "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658".
        "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340".
        "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888".
        "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585".
        "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847".
        "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055".
        "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780".
        "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274".
        "|-10270|-10262|-10260|-10256|-10254";
    $_TDataKey = explode('|', $_DataKey);
    $_TDataValue = explode('|', $_DataValue);

    $_Data = (PHP_VERSION>='5.0') ? array_combine($_TDataKey, $_TDataValue) : _Array_Combine($_TDataKey, $_TDataValue);
    arsort($_Data);
    reset($_Data);

    if($_Code != 'gb2312') $_String = _U2_Utf8_Gb($_String);

    $_Res = '';
    for($i=0; $i<strlen($_String); $i++)
    {
        $_P = ord(substr($_String, $i, 1));
        if($_P>160) { $_Q = ord(substr($_String, ++$i, 1)); $_P = $_P*256 + $_Q - 65536; }
        $_Res .= _Pinyin($_P, $_Data).$pix;
    }
    return @preg_replace("/[^a-z0-9".$pix."]*/", '', $_Res);
}

function _Pinyin($_Num, $_Data)
{
    if ($_Num>0 && $_Num<160 ) return chr($_Num);
    elseif($_Num<-20319 || $_Num>-10247) return '';
    else {
        foreach($_Data as $k=>$v){ if($v<=$_Num) break; }
        return $k;
    }
}

function _U2_Utf8_Gb($_C)
{
    $_String = '';
    if($_C < 0x80) $_String .= $_C;
    elseif($_C < 0x800)
    {
        $_String .= chr(0xC0 | $_C>>6);
        $_String .= chr(0x80 | $_C & 0x3F);
    }elseif($_C < 0x10000){
        $_String .= chr(0xE0 | $_C>>12);
        $_String .= chr(0x80 | $_C>>6 & 0x3F);
        $_String .= chr(0x80 | $_C & 0x3F);
    } elseif($_C < 0x200000) {
        $_String .= chr(0xF0 | $_C>>18);
        $_String .= chr(0x80 | $_C>>12 & 0x3F);
        $_String .= chr(0x80 | $_C>>6 & 0x3F);
        $_String .= chr(0x80 | $_C & 0x3F);
    }
    return iconv('UTF-8', 'GB2312', $_String);
}

function _Array_Combine($_Arr1, $_Arr2)
{
    for($i=0; $i<count($_Arr1); $i++) $_Res[$_Arr1[$i]] = $_Arr2[$i];
    return $_Res;
}




function checkpath($path,$name=''){
    $pathall = $path.$name;
    if(!is_dir($pathall))
    {
        mkdir($pathall,0700,true);
        return $name;
    }else{
        if($name!=''){
            $name = getRandZm(6);
            checkpath($path,$name);
        }
    }
}


function removeEmoji($nickname) {

    $nickname = json_decode($nickname);
    $nickname = preg_replace_callback("#(\\\ue[0-9a-f]{3})#i",function($match){return addslashes($match[0]);},$nickname);//解决昵称是表情
    $nickname = json_encode($nickname);
    if($nickname=='""'){
        $nickname = '';
    }

    return $nickname;
}

// 过滤掉emoji表情
function filterEmoji($str)
{
   $str = preg_replace_callback('/./u',function (array $match) {return strlen($match[0]) >= 4 ? '' : $match[0];},$str);
   if($str=='""'){
        $str = '';
   }
  return $str;
}


function BigEndian2Int($byte_word, $signed = false) {
    $int_value = 0;
    $byte_wordlen = strlen($byte_word);
    for ($i = 0; $i < $byte_wordlen; $i++) {
        $int_value += ord($byte_word{$i}) * pow(256, ($byte_wordlen - 1 - $i));
     }
    if ($signed){
        $sign_mask_bit = 0x80 << (8 * ($byte_wordlen - 1));
        if ($int_value & $sign_mask_bit) {
            $int_value = 0 - ($int_value & ($sign_mask_bit - 1));
        }
    }
        return $int_value;
}

//获取视频时长
function getTime($name){
    if(!file_exists($name)){return;}
    $flv_data_length=filesize($name);
    $fp = @fopen($name, 'rb');
    $flv_header = fread($fp, 5);
    fseek($fp, 5, SEEK_SET);
    $frame_size_data_length =BigEndian2Int(fread($fp, 4));
    $flv_header_frame_length = 9;
    if ($frame_size_data_length > $flv_header_frame_length) {
        fseek($fp, $frame_size_data_length - $flv_header_frame_length, SEEK_CUR);
        }
    $duration = 0;
    while ((ftell($fp) + 1) < $flv_data_length) {
        $this_tag_header = fread($fp, 16);
        $data_length = BigEndian2Int(substr($this_tag_header, 5, 3));
        $timestamp = BigEndian2Int(substr($this_tag_header, 8, 3));
        $next_offset = ftell($fp) - 1 + $data_length;
        if ($timestamp > $duration) {
            $duration = $timestamp;
            }
        fseek($fp, $next_offset, SEEK_SET);
        }
    fclose($fp);
    return fn($duration);
}

function fn($time){
    $num = $time;
    $sec = intval($num / 1000);
    $h = intval($sec / 3600);
    $m = intval(($sec % 3600) / 60);
    $s = intval(($sec % 60 ));
    $tm = $h . ':' . $m . ':' . $s ;
    return $tm;
}
//echo getTime("****.flv");//显示数字时间如236722
//echo fn(236722); //显示时间格式0:03:56



// $string： 明文 或 密文  
// $operation：DECODE表示解密,其它表示加密  
// $key： 密匙  
// $expiry：密文有效期  
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙 
    $ckey_length = 4;
    // 密匙
    $key = !$key?md5(DI()->config->get('sys.authkey')):md5($key);
    // 密匙a会参与加解密  
    $keya = md5(substr($key, 0, 16));
    // 密匙b会用来做数据完整性验证  
    $keyb = md5(substr($key, 16, 16));
    // 密匙c用于变化生成的密文  
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    // 参与运算的密匙 
    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性  
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确  
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);


    $result = '';
    $box = range(0, 255);


    $rndkey = array();
    // 产生密匙簿  
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度  
    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // 核心加解密部分  
    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // 从密匙簿得出密匙进行异或，再转成字符  
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if($operation == 'DECODE') {
        // substr($result, 0, 10) == 0 验证数据有效性  
        // substr($result, 0, 10) - time() > 0 验证数据有效性  
        // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性  
        // 验证数据有效性，请看未加密明文的格式 	
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因  
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码  	
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}


function getContentPath($book_id,$filename){
    $txtpath =DI()->config->get('sys.xsapp.txtpath').mb_substr(md5($book_id),0,1).DIRECTORY_SEPARATOR;
    $txtpath = $txtpath.mb_substr($filename,0,1).DIRECTORY_SEPARATOR.$filename;
    return $txtpath;
}


function sub_string($str, $len, $charset="utf-8")
{
    if( !is_numeric($len) or $len <= 0) {//如果截取长度小于等于
        return "";      //返回空
    }
    $sLen = strlen($str);    //获取原始字串长度
    if( $len >= $sLen ) {   //如果截取长度大于总字符串长度
        return $str;     //直接返回当前字符串
    }
    if ( strtolower($charset) == "utf-8" ) { //如果编码为为utf-8
        $len_step = 3;      //则中文字符长度为3
    } else {        //如果不是
        $len_step = 2;      //如果是gb2312或big5编码，则中文字符长度为2
    }
    //执行截取操作
    $len_i = 0;   //初始化计数当前已截取的字符串个数，此值为字符串的个数值
    $substr_len = 0; //初始化应该要截取的总字节数
    for( $i=0; $i < $sLen; $i++ ) { //开始循环
        if ( $len_i >= $len ) break; //总截取$len个字符串后，停止循环
        if( ord(substr($str,$i,1)) > 0xa0 ) { //如果是中文字符串
            $i += $len_step - 1;   //
            $substr_len += $len_step;  //当前总字节数加上相应编码的中文字符长度
        } else {        //如果字符不是中文
            $substr_len ++;     //加1个字节
        }
        $len_i ++;     //已经截取字符串个数增加
    }
    $result_str = substr($str,0,$substr_len ); //获取结果
    return $result_str.'...';    //返回结果
}


function curl_post($url, $data=array())
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_TIMEOUT,3);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);  //重定向
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;

}

function doSock($url, $param=array(),$timeout=1){
    $urlParmas = parse_url($url);
    $host = $urlParmas['host'];
    $path = $urlParmas['path'];
    $port = isset($urlParmas['port'])? $urlParmas['port'] :80;
    $errno = 0;
    $errstr = '';

    $fp = fsockopen($host, $port, $errno, $errstr,$timeout);
    if ($fp) {
        $query = isset($param) ? http_build_query($param) : '';
        $out = "POST " . $path . " HTTP/1.1\r\n";
        $out .= "host:" . $host . "\r\n";
        $out .= "content-length:" . strlen($query) . "\r\n";
        $out .= "content-type:application/x-www-form-urlencoded\r\n";
        $out .= "connection:close\r\n\r\n";
        $out .= $query;
        fputs($fp, $out);
        usleep(1000);
        /*忽略执行结果
            while (!feof($fp)) {
                echo fgets($fp, 128);
        }*/
        fclose($fp);
    }
}


function base64_encode_s($content){
    $content = base64_encode($content);
    $content = str_replace("+","-",$content);
    $content = str_replace("/","*",$content);
    return $content;
}

function base64_decode_s($content){
    $content = str_replace("-","+",$content);
    $content = str_replace("*","/",$content);

    return base64_decode($content);
}

function get_rand($proArr)
{
    
    $result = '';
        //概率数组的总概率精度
    $proSum = array_sum($proArr);
        //概率数组循环
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);
        if ($randNum <= $proCur) {
            $result = $key;
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset ($proArr);
    return $result;
}
?>




