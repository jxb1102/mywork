<?php
/**
 * 
 *
 * @author zzqss <zzqss@zzqss.com>
 */
 namespace Think;
class TaoClient{
	/**
	 * Debug state
	 *
	 * @var boolean
	 */
	private $debug;
	/**
	 * The server URL
	 *
	 * @var string
	 */
	private $url = 'http://api.ebaycms.com/goodsapi.php';
	/**
	 * The request id
	 *
	 * @var integer
	 */
	private $id;
	/**
	 * If true, notifications are performed instead of requests
	 *
	 * @var boolean
	 */
	private $notification = false;
	private $_userParam = array();	
	private $_systemInfo = array();	
    //缓存路径
    private $_CachePath;

    //缓存时间
    private $_cachetime = 12;
    private $_ClearCache = '1 8 * *';//每天8点1分清理
	private $_method = 'other';
	
	/**
	 * Takes the connection parameters
	 *
	 * @param string $url
	 * @param boolean $debug
	 */
	public function __construct($url='',$debug = false) {
		global $_G,$_KEY;
		// server URL
		if(!empty($url))$this->url = $url;
		// proxy
		empty($proxy) ? $this->proxy = '' : $this->proxy = $proxy;
		// debug state
		empty($debug) ? $this->debug = false : $this->debug = true;
		// message id
		$this->id = 1;
		//设置默认缓存目录
		if(empty($this->_CachePath))$this->_CachePath = ROOT_PATH.'/data/taocache/';
		//设置默认传送过去信息
		$this->_systemInfo = array(
			'ip'=>gethostbyname($_SERVER['SERVER_NAME']),
			'domain'=>$_SERVER['HTTP_HOST'],
			'agent'=>$_SERVER['HTTP_USER_AGENT'],
			'os'=>PHP_OS,
			'phpvar'=>phpversion(),
			'key'=>$_KEY['key'],
			'cfg_taobao_appkey'=>$_G['cfg_taobao_appkey'],
			'cfg_taobao_appsecret'=>$_G['cfg_taobao_appsecret']
		);
	}
	/**
	 * Sets the notification state of the object. In this state, notifications are performed, instead of requests.
	 *
	 * @param boolean $notification
	 */
	public function setRPCNotification($notification) {
		empty($notification) ?
							$this->notification = false
							:
							$this->notification = true;
	}
    public function __set ($name, $value)
    {
        $this->_userParam[$name] = $value;
    }
	/**
	 * Performs a jsonRCP request and gets the results as an array
	 *
	 * @param string $method
	 * @param array $params
	 * @return array
	 */
	public function __call($method,$params) {
		
		// check
		if (!is_scalar($method)) {
			throw new Exception('Method name has no scalar value');
		}
		
		// check
		if (is_array($params)) {
			// no keys
			$params = array_values($params);
		} else {
			throw new Exception('Params must be given as array');
		}
		
		// sets notification or request task
		if ($this->notification) {
			$currentId = NULL;
		} else {
			$currentId = $this->id;
		}
		$this->_method = $method;
		
		// prepares the request
		$request = array(
						'method' => $method,
						'params' => $params,
						'userParams' => $this->_userParam,
						'systemInfo' => $this->_systemInfo,
						'id' => $currentId
						);
		$request = json_encode($request);
		$this->debug && $this->debug.='***** Request *****'."\n".$request."\n".'***** End Of request *****'."\n\n";
		
		// performs the HTTP POST
		$opts = array ('http' => array (
							'method'  => 'POST',
							'timeout'=>20,
							'header'  => 'Content-type: application/json',
							'content' => $request
							));
		$cacheid = md5($request);
		$jsonresponse =  $this->getCacheData($cacheid);
		if (! $jsonresponse) {
			$context  = stream_context_create($opts);
			if ($fp = fopen($this->url, 'r', false, $context)) {
				$response = $jsonresponse =  '';
				while($row = fgets($fp)) {
					$response.= trim($row)."\n";
				}
				$this->debug && $this->debug.='***** Server response *****'."\n".$response.'***** End of server response *****'."\n";
				$jsonresponse = $response;
				$response = json_decode($response,true);
			} else {
				if ($this->debug) {
					throw new Exception('Unable to connect to '.$this->url);
				}
				$response = array('id'=>1,'result' => array());
			}
			
			// debug output
			if ($this->debug) {
				echo nl2br($this->debug);
			}
			 
			// final checks and return
			if (!$this->notification) {
				// check
				if ($response['id'] != $currentId) {
					throw new Exception('Incorrect response id (request id: '.$currentId.', response id: '.$response['id'].')');
				}
				if (!is_null($response['error'])) {
					throw new Exception('Request error: '.$response['error']);
				}
				
				$this->saveCacheData($cacheid, $jsonresponse);
				return $response['result'];
				
			} else {
				return true;
			}
		}else{
			$response = json_decode($jsonresponse,true);
			return $response['result'];
		}
	}
    public function saveCacheData ($id, $result)
    {
        $idkey = substr($id,0,2);
        if ($this->_cachetime) {
            if (! is_dir($this->_CachePath)) {
                mkdir($this->_CachePath);
            }
			$cachedir = isset($this->_userParam['method'])?$this->_userParam['method']:$this->_method;
            if (! is_dir($this->_CachePath . $cachedir)) {
                mkdir($this->_CachePath . $cachedir);
            }
            if (! is_dir($this->_CachePath . $cachedir.'/'.$idkey)) {
                mkdir($this->_CachePath . $cachedir.'/'.$idkey);
            }
            $filepath = $this->_CachePath . $cachedir.'/'.$idkey;
            if (is_dir($filepath)) {
                $filename = $filepath . '/' . $id . '.cache';
                @file_put_contents($filename, $result);
            }
        }
    }
    public function clearCache ($id = null)
    {
		$cachedir = isset($this->_userParam['method'])?$this->_userParam['method']:$this->_method;
        if ($id) {
            $filename = $this->_CachePath . $cachedir . '/' . $id . '.cache';
            unlink($filename);
        } else {
            $dir = $this->_CachePath . $cachedir . '/';
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if (is_dir($dir . $file)) {
                            continue;
                        }
						if(strpos($file,".cache") !== false)
						{
							unlink($dir . $file);
						}
                    }
                    closedir($dh);
                }
            }
        }
    }
	private function checkClearTime()
	{
		$CacheParam = explode(" ",$this->_ClearCache);

		if(!$this->_ClearCache || count($CacheParam) !== 4)
		{
			return false;
		}
		if($CacheParam[3] != "*")
		{
			$CacheParam[3] = explode(",",$CacheParam[3]);

			if(!in_array(date('m'),$CacheParam[3]))
			{
				return false;
			}
		}

		if($CacheParam[2] != "*")
		{
			$CacheParam[2] = explode(",",$CacheParam[2]);

			if(!in_array(date('d'),$CacheParam[2]))
			{
				return false;
			}
		}
		if($CacheParam[1] != "*")
		{
			$CacheParam[1] = explode(",",$CacheParam[1]);

			if(!in_array(date('H'),$CacheParam[1]))
			{
				return false;
			}
		}

		if($CacheParam[0] != "*")
		{
			$CacheParam[0] = explode(",",$CacheParam[0]);

			if(!in_array(date('i'),$CacheParam[0]))
			{
				return false;
			}
		}

		$cachetag = $this->_CachePath."autoclear.tag";

         if (file_exists($cachetag)) {
                $filetime = date('U', filemtime($cachetag));
				if(date("d") == date("d",$filetime))
				{
					return false;
				}
		}
		file_put_contents($cachetag,date("Y-m-d H:i:s"));
		return true;
	}
	//自动清理缓存
	public function autoClearCache($path ='')
	{
		$path = $path ? $path : $this->_CachePath;

		if(empty($path))
		{
			return false;
		}

		if($this->_cachetime)
		{
			if(!is_dir($path))
			{
				return false;
			}
			
			if($fdir = opendir($path))
			{
				$old_cwd = getcwd();
				chdir($path);
				$path = getcwd().'/';
				while(($file = readdir($fdir)) !== false)
				{
					if(in_array($file,array('.','..')))
					{
						continue;
					}
					if(is_dir($path.$file))
					{
						$this->autoClearCache($path.'/'.$file.'/'); 
					}else{
						if(strpos($file,".cache") !== false)
						{
							$filetime = date('U', filemtime($path.$file));
							$cachetime = $this->_cachetime * 60 * 60;
							if ($this->_cachetime != 0 && (time() - $filetime) > $cachetime) {
									@unlink($path.$file);
							}
						}
					}
				}				
				closedir($fdir);
				chdir($old_cwd);
			}
		}

	}
    public function getCacheData ($id)
    {
		$cachedir = isset($this->_userParam['method'])?$this->_userParam['method']:$this->_method;
		if($this->checkClearTime())
		{
			$this->autoClearCache();
		}
        $idkey = substr($id,0,2);
        $filename = $this->_CachePath . $cachedir . '/' . $idkey .'/'. $id . '.cache';
        if ($this->_cachetime) {
            if (file_exists($filename)) {
                $filetime = date('U', filemtime($filename));
                $cachetime = $this->_cachetime * 60 * 60;
                if ($this->_cachetime != 0 && (time() - $filetime) > $cachetime) {
                    return false;
                }
                return @file_get_contents($filename);
            }
        }
        return false;
    }

}
?>