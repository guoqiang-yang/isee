<?php

include_once ('../../../global.php');

class App extends App_Admin_Ajax
{
    private $rid;

    protected function getPara()
    {
        $this->rid = Tool_Input::clean('r', 'rid', 'int');
    }

    protected function main()
    {
        $recipeInfo = Recipe_Api::getRecipeInfo($this->rid);

        if (empty($recipeInfo)) {
            throw new Exception('操作菜谱不存在');
        }

        $chgStatus = $recipeInfo['status']==Conf_Base::STATUS_NORMAL? Conf_Base::STATUS_DELETED: Conf_Base::STATUS_NORMAL;
        Recipe_Api::updateRecipe($this->rid, array('status'=>$chgStatus));
    }

    protected function outputBody()
    {
        $response = new Response_Ajax();
        $response->setContent(array('rest' => 0));
        $response->send();
    }
}

$app = new App('pri');
$app->run();