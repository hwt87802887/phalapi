<?
class Cache_Lite
{
    private $cache_path;
    private $cache_expire;

    public function __construct($exp_time=3600,$path="../../cache/")
    {
        $this->cache_expire=$exp_time;
        $this->cache_path=$path;
    }

    //获取文件名
    private function fileName($key)
    {
        return $this->cache_path.md5($key).$key;
    }

    //设置缓存时间
    public function setexptime($exp_time=3600){
        $this->cache_expire = $exp_time;
    }

    //写入缓存文件
    public function set($key, $data)
    {
        $values = serialize($data);
        $filename = $this->fileName($key);
        $file = fopen($filename, 'w');
        if ($file){
            fwrite($file, $values);
            fclose($file);
        }
    }


    //获取缓存文件
    public function get($key)
    {
        $filename = $this->fileName($key);
        if (!file_exists($filename) || !is_readable($filename)){
            return false;
        }
        if(time() < (filemtime($filename) + $this->cache_expire) ) {
            $file = fopen($filename, "r");
            if ($file) {
                $data = fread($file, filesize($filename));
                fclose($file);
                return unserialize($data);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}