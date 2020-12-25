<?php

include_once ('../../../global.php');

class App extends App_Admin_Ajax
{
    private $id;
    private $recipeInfo;

    protected function getPara()
    {
        $this->id = Tool_Input::clean('r', 'id', 'int');
        $this->recipeInfo = array(
            'name' => Tool_Input::clean('r', 'name', 'str'),
            'tags' => json_decode(Tool_Input::clean('r', 'tags', 'str'), true),
            'category' => Tool_Input::clean('r', 'category', 'str'),
            'calories' => Tool_Input::clean('r', 'calories', 'int'),
            'constitution' => json_decode(Tool_Input::clean('r', 'constitution', 'str'), true),
            'intro' => Tool_Input::clean('r', 'intro', 'str'),
        );
        $pic = Tool_Input::clean('r', 'pic', 'str');
        !empty($pic) && $this->recipeInfo['pic'] = $pic;
    }

    protected function checkPara()
    {
        $unEmptyFields = array('name', 'category', 'intro');
        foreach($unEmptyFields as $field) {
            if (empty($this->recipeInfo[$field])) {
                throw new Exception('Params Error isEmpty: '. $field);
            }
        }

        if (empty($this->id)) {
            throw new Exception('Params Error');
        }

        if (!in_array($this->recipeInfo['category'], Conf_Recipe_Category::getAllCategories())) {
            throw new Exception('Category is UnDefined');
        }

        $selectedConstitutions = Tool_Array::getFields($this->recipeInfo['constitution'], 'name');
        $diffConstitutions = array_diff($selectedConstitutions, Conf_Constitution::getConstitutions());
        if (!empty($diffConstitutions)) {
            throw new Exception('Constitution is UnDefined: '. implode(',', $diffConstitutions));
        }
    }

    protected function main()
    {
        $this->recipeInfo['tags'] = implode(',', $this->recipeInfo['tags']);
        $this->recipeInfo['constitution'] = Conf_Constitution::genConstitution($this->recipeInfo['constitution']);

        Recipe_Api::updateRecipe($this->id, $this->recipeInfo);
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
