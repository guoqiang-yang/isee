<?php

include_once('../global.php');
include_once(INCLUDE_PATH . 'vendor/simple_html_dom.php');
class App extends App_Cli
{
    protected function main()
    {
        $this->getPic();
    }

    private function getPic()
    {
        $url = 'https://shp.qpic.cn/mmpay/oU5xbewRJutxV9hCkoJKqrcgUJNww4zweSLzSgABmSYxE0uqrJ8bd4pTbhsvCKjia/0';
        //$url = 'https://shp.qpic.cn/mmpay/v0jjTIZMRlNrYJ5qOb3YyZTxvOMnpLPfM1uT7l266jb1ON8YCaRiaby45e6f7Nia5UDVj9x0EtGpg/0';
        $referer = 'https://pay.weixin.qq.com/index.php/core/home/login?return_url=%2F';
        $content = $this->_downloadByCurl($url, $referer);
        file_put_contents('/tmp/pic.png', $content);
    }

    private function _downloadByCurl($url, $referer, $host='')
    {
        //$agent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4';
        $agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36';
        $headers["Host"] = $host ? $host:'img.haocaisong.cn';
        $headers["Accept"] = 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
        $headers["Accept-Language"] = 'zh-CN,zh;q=0.8,en;q=0.6';
        $headers["Accept-Encoding"] = 'gzip, deflate, sdch';
        $headers["Connection"] = 'keep-alive';
        $headers["max-age"] = '0';

        $this->_trace('get url : %s', $url);
        $content = Tool_Http::get($url, array(), $headers, $referer, $agent);
        return $content;
    }
}

$app = new App();
$app->run();
