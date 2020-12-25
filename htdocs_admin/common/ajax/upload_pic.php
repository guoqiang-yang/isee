<?php
include_once('../../../global.php');

class App extends App_Admin_Ajax
{
    const LOGO_MAX_FILESIZE = 5242880;    //5M
    private $pic;
    private $path;
    private $imageinfo;

    protected function getPara()
    {
        $this->pic = Tool_Input::clean('f', 'pic', TYPE_FILE);
        $this->path = trim(Tool_Input::clean('r', 'path', TYPE_STR));
    }

    protected function checkPara()
    {
        if (UPLOAD_ERR_OK != $this->pic['error'])
        {
            throw new Exception('common:upload pic error');
        }

        $imageinfo = Tool_Image::getImageInfo($this->pic['tmp_name']);
        if ($imageinfo === FALSE || !$imageinfo['width'] || !$imageinfo['height'])
        {
            throw new Exception('common:upload pic error');
        }
        $this->imageinfo = $imageinfo;

        if ($this->path == 'undefined')
        {
            $this->path = OSS_PIC_PATH;
        }
    }

    protected function main()
    {
        $name = $this->pic['name'];
        $content = file_get_contents($this->pic['tmp_name']);
        $width = $this->imageinfo['width'];
        $height = $this->imageinfo['height'];

        $path = '';
        if (!empty($this->path))
        {
            $path .= trim($this->path, DS) . DS;
        }

        $pictag = $this->savePic($name, $content, $width, $height, $path);

        $response = new Response_Ajax();
        $picUrl = File_Oss_Api::getImgUrl($pictag);
        if(Conf_Base::isTestEnv()) {
            $picUrl = str_replace(File_Oss_Config::OSS_SHI_DIR_DF, File_Oss_Config::OSS_TEST_DIR_DF, $picUrl);
        }
        $response->setContent(array('pictag' => $pictag, 'picurl' => $picUrl));
        $response->send();
        exit;
    }


    private function savePic($name, $content, $width, $height, $path)
    {
        if (empty($name) || empty($content)) {
            throw new Exception('上传失败，图片不存在');
        }

        $pictureDao = new Data_Dao('t_picture');
        $pid = $pictureDao->add(array(
            'width' => $width,
            'height' => $height,
            'srcinfo' => json_encode(array()),
        ));
        $picTag = Data_Pic::savePic($pid, $name, $content, $path);
        $pictureDao->update($pid, array('pictag' => $picTag));

        return $picTag;
    }
}

$app = new App('pri');
$app->run();