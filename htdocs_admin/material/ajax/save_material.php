<?php

include_once ('../../../global.php');


class App extends App_Admin_Ajax
{
    private $id;
    private $saveParams;

    protected function getPara()
    {
        $this->id = Tool_Input::clean('r', 'id', 'int');
        $this->saveParams = array(
            'alias' => Tool_Input::clean('r', 'alias', 'str'),
            'dgl_category' => Tool_Input::clean('r', 'dgl_category', 'str'),
            'meridian' => Tool_Input::clean('r', 'meridian', 'str'),
            'season' => Tool_Input::clean('r', 'season', 'str'),
            'type' => Tool_Input::clean('r', 'type', 'int'),
            'category' => Tool_Input::clean('r', 'category', 'str'),
            'nature' => Tool_Input::clean('r', 'nature', 'int'),
            'intro' => Tool_Input::clean('r', 'intro', 'str'),
            'nutritive_value' => Tool_Input::clean('r', 'nutritive_value', 'str'),
            'good' => Tool_Input::clean('r', 'good', 'str'),
            'bad' => Tool_Input::clean('r', 'bad', 'str'),
            'selection' => Tool_Input::clean('r', 'selection', 'str'),
            'preservation' => Tool_Input::clean('r', 'preservation', 'str'),
            'cook' => Tool_Input::clean('r', 'cook', 'str'),
            'people_good' => Tool_Input::clean('r', 'people_good', 'str'),
            'people_bad' => Tool_Input::clean('r', 'people_bad', 'str'),
            'constitution_good' => json_decode(Tool_Input::clean('r', 'constitution_good', 'str'), true),
            'constitution_bad' => json_decode(Tool_Input::clean('r', 'constitution_bad', 'str'), true),
        );

        $pic = Tool_Input::clean('r', 'pic', 'str');
        !empty($pic) && $this->saveParams['pic'] = $pic;
    }

    protected function checkPara()
    {
        $unEmptyFields = array('intro', 'category', 'constitution_good', 'constitution_bad');
        foreach($unEmptyFields as $field) {
            if (empty($this->saveParams[$field])) {
                throw new Exception('Params Error: isEmpty: '. $field);
            }
        }

        if (empty($this->id)) {
            throw new Exception('Params Error');
        }

        if (!Conf_Material_Old::getTopCategoryOf($this->saveParams['category'])) {
            throw new Exception('Category is UnDefined');
        }

        $selectedConstitutions = array_merge(
            Tool_Array::getFields($this->saveParams['constitution_good'], 'name'),
            Tool_Array::getFields($this->saveParams['constitution_bad'], 'name'));
        $diffConstitutions = array_diff($selectedConstitutions, Conf_Constitution::getConstitutions());
        if (!empty($diffConstitutions)) {
            throw new Exception('Constitution is UnDefined: '. implode(',', $diffConstitutions));
        }
    }

    protected function main()
    {
        $this->saveParams['constitution_good'] = Conf_Constitution::genConstitution($this->saveParams['constitution_good']);
        $this->saveParams['constitution_bad'] = Conf_Constitution::genConstitution($this->saveParams['constitution_bad']);

        Material_Api::updateByMaterialId($this->id, $this->saveParams);
    }

    protected function outputBody()
    {
        $response = new Response_Ajax();
        $response->setContent(array('rest' => 0));
        $response->send();
    }
}

$app = new App();
$app->run();