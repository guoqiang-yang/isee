<?php

include_once ('../../global.php');

class App extends App_Admin_Page
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
        $this->detail['pic_url'] = File_Oss_Api::getImgUrl($this->detail['pic']);
        $this->formatConstitution();

        $this->addCss(array(
            'css/plugins/select2.min.css',
            //'css/plugins/bootstrap_fileupload.css',
        ));
        $this->addFootJs(array(
            'js/plugins/select2.min.js',
            'js/plugins/bootstrap_select.min.js',
            'js/plugins/bootstrap_filestyle.min.js',
            'js/plugins/jquery.bootstrap_touchspin.min.js',
            'js/plugins/bootstrap_maxlength.js',
            'js/plugins/jquery.form_advanced.init.js',
            'js/hls/material.js',
            //'js/plugins/bootstrap_fileupload.js',
            'js/common/fileUploader.js',
            'js/common/uploadPic.js',
        ));
    }

    protected function outputBody()
    {
        $this->smarty->assign('sub_title', '食材编辑');
        $this->smarty->assign('base_config', $this->getBaseConfig());
        $this->smarty->assign('material_detail', $this->detail);
        $this->smarty->display('material/material_edit.html');
    }

    private function getBaseConfig()
    {
        return array(
            'category' => Conf_Material_Old::getTopCategoryInfos(),
        );
    }

    private function formatConstitution()
    {
        $grouped = array();
        $selectedConstitutions = array_merge(Conf_Constitution::parseConstitution($this->detail['constitution_good']),
                                            Conf_Constitution::parseConstitution($this->detail['constitution_bad']));
        $constitutionList = Tool_Array::list2Map($selectedConstitutions, 'name', 'weight');

        foreach(Conf_Constitution::getConstitutions() as $itemName) {
            if (! array_key_exists($itemName, $constitutionList)) {
                $grouped['empty'][]= array(
                    'name' => $itemName,
                    'weight' => 0,
                );
            } else {
                $groupedFlag = $constitutionList[$itemName] < 0? 'bad': 'good';
                $grouped[$groupedFlag][] = array(
                    'name' => $itemName,
                    'weight' => $constitutionList[$itemName],
                );
            }
        }

        $this->detail['constitution'] = $grouped;
    }
}

$app = new App('pri');
$app->run();