<?php

include_once ('../../../global.php');

class App extends App_Admin_Ajax
{
    private $id;
    private $detail;

    protected function getPara()
    {
        $this->id = Tool_Input::clean('r', 'id', 'int');
    }

    protected function checkPara()
    {
        if (empty($this->id))
        {
            throw new \Exception('食材ID为空，请核对');
        }
    }

    protected function main()
    {
        $this->detail = Material_Api::getMaterialById($this->id);
        $this->appendShowContent();
    }

    protected function outputBody()
    {
        $this->smarty->assign('detail', $this->detail);
        $html = $this->smarty->fetch('material/aj_material_detail.html');

        $response = new Response_Ajax();
        $response->setContent(array('html'=>$html));
        $response->send();
    }

    private function appendShowContent()
    {
        $showFields = array(
            'intro' => '简介',
            'nutritive_value' => '营养',
            'good' => '功效',
            'bad' => '禁忌',
            'selection' => '选购',
            'preservation' => '存储',
            'cook' => '烹饪',
        );
        $this->detail['show_list'] = array();
        foreach($showFields as $field => $fieldDesc) {
            $this->detail['show_list'][] = array(
                'flag' => $field,
                'name' => $fieldDesc,
                'content' => !empty($this->detail[$field])? $this->detail[$field]: '暂无',
            );

            unset($this->detail[$field]);
        }
    }
}

$app = new App('pri');
$app->run();