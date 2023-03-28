<?php
namespace GulzarAli\SiteMap;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SiteMap{
    /**
     * @param string array of urls ['your-domain.com','your-domain.com/contact-us','your-domain.com/about-us']
     */
    private $urls = [];

    /**
     * @param string App Url: https://your-domain.com
     */
    private $base_url = '';

    /**
     * @param string storage object local/public/s3 etc
     */
    private $storage = 'local';

    /**
     * @param string sitemap output formate xml/text
     */
    private $formate = 'text';
    
    /**
     * @param string File name
     */
    private $file_name = '';

    public function __construct($base_url='')
    {
        if(!empty($base_url)){
            $this->base_url = $this->removeSlashes($base_url);
        }
        else{
            $this->base_url = $this->removeSlashes(url('/'));
        }
    }

    /**
     * @param string page path with preceding '/'. i-e: /, /contact-us, /about-us..
     * @return object $this
     */
    public function add($path){
        $this->appendUrlToUrlsArray($path);
        return $this;
    }

    public function getUrls(){
        return $this->urls;
    }

    /**
     * Adding the url to urls array
     * @param string url path. i-e: /, /contact-us, /about-us..
     * @return object $this
     */
    private function appendUrlToUrlsArray($url){
        $formated_url = $this->getFullUrlPath($url);
        if(!in_array($formated_url,$this->urls)){
            $this->urls[] = $formated_url;
        }
        return $this;
    }

     /**
     * @param string $url: url path. i-e: /, /contact-us, /about-us..
     * @return string formated url: i-e: https://your-domain.com,https://your-domain.com/contact-us,https://your-domain.com/about-us
     */
    private function getFullUrlPath($url){
        return $this->base_url.$url;
    }

    /**
     * @param string url string
     * @return string string after removing the slashes from start and end
     */
    protected function removeSlashes($str){
        return trim($str, '/');
    }

    /**
     * @param string disc storage object name: local, public, s3
     * @param object $this
     */
    public function storage($storage){
        $this->storage = $storage;
        return $this;
    }

    /**
     * set the out formate of the sitemap
     * @param string $formate The file format to use ('text' or 'xml')
     */
    public function formate($formate){
        $this->formate = $formate;
        return $this;
    }

    /**
     * set file name
     * @param string file-name.txt
     */
    public function fileName($file_name){
        $this->file_name = $file_name;
        return $this;
    }
    public function resetFileName(){
        $this->file_name = '';
        return $this;
    }

    /**
     * Render the sitemap
     * @param string file name: sitemap.txt
     */
    public function render(){
        
        if(empty($this->file_name)){
            $this->setDefaultFileName();
        }
        Log::info('rendering: '.$this->file_name);
        $data = $this->getFileContents();
        Storage::disk($this->storage)->put($this->file_name, $data);
        return $this;
    }

    public function getFileFullPath(){
        return url($this->file_name);
    }

    /**
     * submit the sitemap URL to google search engin for crawling
     */
    public function submitGoogle(){
        $sitemapUrl = $this->getFileFullPath();
        $client = new Client();
        try {
            $client->post('https://www.google.com/ping?sitemap=' . $sitemapUrl);
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $this;
    }
    
    /**
     * Set default file name if not provided by the client
     * @param string $this->format The file format to use ('text' or 'xml')
     */
    private function setDefaultFileName(){
        if($this->formate == 'text'){
            $this->file_name = 'sitemap.txt';
        }
        else{
            $this->file_name = 'sitemap.xml';
        }
        return $this;
    }
    private function getFileContents(){
        if($this->formate == 'text'){
            return implode("\n", $this->urls);
        }
        else{
            return $this->getXmlFileContents();
        }
    }

    /**
     * Create xml object
     * @param array $this->urls
     */
    private function getXmlFileContents(){
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');
        foreach ($this->urls as $url) {
            $urlNode = $xml->addChild('url');
            $urlNode->addChild('loc', htmlspecialchars($url));
            $urlNode->addChild('lastmod', date('Y-m-d'));
        }
        return $xml->asXML();
    }
}