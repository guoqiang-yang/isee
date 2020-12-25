<?php

include_once ('../../../global.php');

class App extends App_Admin_Ajax
{
    private $id;
    private $steps;

    protected function getPara()
    {
        $this->id = Tool_Input::clean('r', 'id', 'int');
        $this->steps = Tool_Input::clean('r', 'steps', 'str');
    }

    protected function checkPara()
    {
        if (empty($this->steps) || empty($this->id)) {
            throw new \Exception('Sorry, Params Error');
        }

        $tmpSteps = json_decode($this->steps, true);
        foreach($tmpSteps as $val) {
            if (empty($val['img']) || empty($val['op'])) {
                throw new \Exception('Sorry, Detail Params Error');
            }
        }
    }

    protected function main()
    {
        $recipeInfo = Recipe_Api::getRecipeInfo($this->id);
        if (empty($recipeInfo)) {
            throw new \Exception('Sorry, Recipe is Unexist');
        }

        //更新
        if ($recipeInfo['step'] != $this->steps) {
            Recipe_Api::updateRecipe($this->id, array('step' => $this->steps));
        }
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