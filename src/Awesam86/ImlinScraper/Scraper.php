<?php
/**
 * ImlinScraper
 *
 * @see       https://github.com/awesam86/imlinscraper/
 *
 * @author    Osamu Suga
 * @copyright Copyright (c) 2018 Osamu Suga
 * @license   http://opensource.org/licenses/mit-license.php This software is released under the MIT License.
 */
namespace Awesam86\ImlinScraper;

class Scraper{
	/** @var String | String[] $url */
	public $url;
	/** @var String $ua */
	public $ua;
	/** @var DOMXPath | DOMXPath[] $xpath */
	public $xpath;
	/** @var String | String[] $host */
	protected $host;

	/**
	 * @param String | String[] $url
	 * @param String            $ua
	 */
	public function __construct($url = NULL,$ua = NULL){
		$this->ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36';
		if(!empty($url) || !empty($ua)){
    		$this->init($url,$ua);
    	}
	}

	/**
	 * �����󥹥��󥹉����γ��ڻ�
	 * @param String | String[] $url
	 * @param String            $ua
	 */
	protected function init($url = NULL,$ua = NULL){
		if(!empty($ua)) $this->ua = $ua;
        if(!empty($url)){
        	$this->url = $url;
        	if(is_array($url)){
        		$this->host = array();
        		foreach ($url as $u) {
        			$p_url = parse_url($u);
        			$this->host[] = isset($p_url['host']) ? $p_url['host'] : '';
        			$html = $this->getCurl($u);
    				$this->xpath[] = $this->XPath($html);
        		}
        	}else{
        		$p_url = parse_url($url);
        		$this->host = isset($p_url['host']) ? $p_url['host'] : '';
        		$html = $this->getCurl($url);
    			$this->xpath = $this->XPath($html);
        	}
        }
	}

	/**
	 * curl�ǥک`������ȡ�ä���
	 * @param  String $url
	 * @return String $html
	 */
	protected function getCurl($url){
		$curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_USERAGENT,$this->ua);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION,TRUE);
        $html = curl_exec($curl);
        curl_close($curl);
        return $html;
	}

	/**
	 * XPath�ǽ����Ǥ���褦��$html���Q����
	 * @param  String   $html
	 * @return DOMXPath $xpath
	 */
	protected function XPath($html){
		$dom = new \DOMDocument();
		//html�����Ф��i���z�ࣨhtml���`�꤬������ϥ���`������Τ�@��Ĥ��룩
		@$dom->loadHTML($html);
		$xpath = new \DOMXPath($dom);
		return $xpath;
	}

	/**
	 * aҪ�ؤ��������Ф˸�{����
	 * @param  DOMNodeList  $link_node
	 * @param  Boolean      $external_link
	 * @param  String       $host     
	 * @return Array{href:String,text:String} $linksDataArray
	 */
	private function setLinksData($link_node,$external_link,$host){
		$linksDataArray = array();
		$cnt = 0;
		foreach ($link_node as $key => $v) {
    		$href = $v->getAttribute('href');
    		if($external_link){
    			preg_match('/https?:\/\/(?!'.$host.').*/',$href,$src);
    		}
    		if( !$external_link || (!empty($src) && count($src) > 0) ){
    			$linksDataArray[$cnt]['href'] = $href;
    			$linksDataArray[$cnt]['text'] = trim($v->nodeValue);
    			$cnt++;
    		}
    	}
    	return $linksDataArray;
	}

    /**
	 * imgҪ�ؤ�������������
	 * @param  String | String[] $url
	 * @param  String            $ua
	 * @param  String            $path XPath�ǻ��������������H��������O�����륫������XPath
	 * @return Array{src:String,alt:String} $imgsData
	 * @throws InvalidArgumentException $this->url���դΈ���
	 */
    public function GetImagesData($url = NULL,$ua = NULL,$path = NULL){
    	if(!empty($url) || !empty($ua)){
    		$this->init($url,$ua);
    	}
    	if(empty($this->url)){
    		throw new \InvalidArgumentException('$url is empty');
    	}
    	$imgsData = array();
    	if(empty($path)) $path = '';
    	if(!empty($this->xpath)){
    		if(is_array($this->xpath)){
	    		$xpaths = $this->xpath;
	    		foreach ($xpaths as $xpath_key => $xpath) {
	    			$img_node = $xpath->query('//'.$path.'img');
			    	foreach ($img_node as $key => $v) {
		    			$imgsData[$xpath_key][$key]['src'] = $v->getAttribute('src');
		    			$imgsData[$xpath_key][$key]['alt'] = $v->getAttribute('alt');
			    	}
	    		}
	    	}else{
	    		$img_node = $this->xpath->query('//'.$path.'img');
		    	foreach ($img_node as $key => $v) {
		    		$imgsData[$key]['src'] = $v->getAttribute('src');
		    		$imgsData[$key]['alt'] = $v->getAttribute('alt');
		    	}
	    	}
    	}
        return $imgsData;
    }

    /**
	 * aҪ�ؤ�������������
	 * @param  String | String[]  $url
	 * @param  String             $ua
	 * @param  String             $path XPath�ǥ�������������H��������O�����륫������XPath
	 * @param  Boolean            $external_link
	 * @return Array{href:String,text:String} $linksData
	 * @throws InvalidArgumentException $this->url���դΈ���
	 */
    public function GetLinksData($url = NULL,$ua = NULL,$path = NULL,$external_link = false){
    	if(!empty($url) || !empty($ua)){
    		$this->init($url,$ua);
    	}
    	if(empty($this->url)){
    		throw new \InvalidArgumentException('$url is empty');
    	}
    	$linksData = array();
    	if(empty($path)) $path = '';
    	if(!empty($this->xpath)){
    		if(is_array($this->xpath)){
	    		$xpaths = $this->xpath;
	    		foreach ($xpaths as $xpath_key => $xpath) {
			    	$link_node = $xpath->query('//'.$path.'a');
			    	$linksData[$xpath_key] = array();
			    	$linksData[$xpath_key] = $this->setLinksData($link_node,$external_link,$this->host[$xpath_key]);
		    	}
	    	}else{
		    	$link_node = $this->xpath->query('//'.$path.'a');
		    	$linksData = $this->setLinksData($link_node,$external_link,$this->host);
	    	}
    	}
        return $linksData;
    }
	
}