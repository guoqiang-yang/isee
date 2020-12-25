<?php

include_once ('../../global.php');

class App extends App_Admin_Page
{
    private $rid;
    private $sid;
    private $keyword;
    private $start;
    private $num = 20;
    private $total;

    private $listName;
    private $recipeList;

    protected function getPara()
    {
        $this->sid = Tool_Input::clean('r', 'sid', 'int');
        $this->rid = Tool_Input::clean('r', 'rid', 'int');
        $this->keyword = Tool_Input::clean('r', 'keyword', 'str');
        $this->start = Tool_Input::clean('r', 'start', 'int');
    }

    protected function checkPara()
    {
        if (empty($this->sid) && empty($this->rid) && empty($this->keyword))
        {
            throw new Exception('至少要选一个参数');
        }
    }

    protected function main()
    {
        if (!empty($this->rid))
        {
            $this->recipeList = $this->_getSameMaterialsRecipes($this->rid);
        }
        elseif (!empty($this->sid))
        {
            $this->recipeList = $this->_getSimilarRecipes($this->sid);
        }
        elseif (is_numeric($this->keyword))
        {
            $this->recipeList = $this->_getByConf($this->keyword);
        }
        else
        {
            $this->recipeList = $this->_getByKeyword($this->keyword);
        }
        $this->_filterNoShowRecipes($this->recipeList);

        $this->addCss(array(
            'css/plugins/rwd_table.min.css',
        ));
        $this->addFootJs(array(
            'js/plugins/rwd_table.min.js',
            'js/common/modals.js',
        ));
    }

    private function _getSameMaterialsRecipes($rid)
    {
        $rids = $this->_getSameMaterialsRecipeIds($rid);
        $recipes = $this->_getRecipesByRid($rids);
        $this->total = count($recipes);
        return $recipes;
    }

    private function _getRecipesByRid($rids)
    {
        $dao = new Data_Dao('t_recipe');
        $recipes = $dao->getList($rids);
        foreach ($recipes as $_idx => $_recipe) {
            if ($_recipe['status']) unset($recipes[$_idx]);
        }
        $recipes = array_values($recipes);
        Recipe_Tool::formatRecipesForView($recipes, true);
        return $recipes;
    }

    private function _getByKeyword($keyword)
    {
        $dao = new Data_Dao('t_recipe');
        $keywords = explode(',', $keyword);
        $where = '1=1';
        foreach ($keywords as $_keyword) {
            $where .= sprintf(" and name like '%%%s%%'", $_keyword);
        }
        $this->total = $dao->getTotal($where);
        $recipes = $dao->limit($this->start, $this->num)->order('status','asc')->getListWhere($where);
        $recipes = array_values($recipes);
        Recipe_Tool::formatRecipesForView($recipes, true);
        return $recipes;
    }

    private function _getByConf($keyword)
    {
        $dao = new Data_Dao('t_recipe');
        $where = 'status=0';
        switch ($keyword) {
            case 1: $where = 'status=0';break;
            case 2: $where = 'status=0 and category=""';break;
            case 3: $where = 'status=99 and popularity_rank>=8 and category<>""';break;
            case 4: $where = 'status=98 and popularity_rank=10';break;
            default: assert(false);
        }
        $this->total = $dao->getTotal($where);
        $recipes = $dao->limit($this->start, $this->num)->getListWhere($where);
        $recipes = array_values($recipes);
        Recipe_Tool::formatRecipesForView($recipes, true);
        return $recipes;
    }

    private function _getSameMaterialsRecipeIds($rid)
    {
        //获取食材
        $materials = Recipe_Api::getMaterialsOfRecipe($rid);
        asort($materials);
        $material = $materials[key($materials)];

        //获取一条recipe链
        $dao = new Data_Dao('t_recipe_material');
        $recipes = $dao->getListWhere(array('material'=>$material, 'mtype'=>1, 'status'=>0));

        //获取recipes
        $resultRids = array();
        $allRecipeMaterials = Recipe_Material::getAllRecipeMaterialIndexes();
        foreach ($recipes as $_recipe) {
            $_rid = $_recipe['rid'];
            if (empty($allRecipeMaterials[$_rid])) continue;

            $_materials = $allRecipeMaterials[$_rid];
            asort($_materials);
            $_materials = array_values($_materials);

            if ($_materials == $materials) {
                $resultRids[] = $_rid;
            }
        }

        return $resultRids;
    }

    private function _getSimilarRecipes($sid)
    {
        $dao = new Data_Dao('t_similar_recipes');
        $task = $dao->get($sid);
        $ids1 = array_filter(explode(',', $task['rids1']));
        $ids2 = array_filter(explode(',', $task['rids2']));
        $rids = array_merge($ids1, $ids2);
        $this->listName = $task['name'];

        $recipes = $this->_getRecipesByRid($rids);
        $this->total = count($recipes);
        return $recipes;
    }

    private function _filterNoShowRecipes(array &$recipes)
    {
        //统一获取食材
        $_noShowCate = array('炒米饭','三明治','糕点','月饼','月饼','意大利面','汉堡','比萨','面片','疙瘩','炒饭','烩饭','炒饼','炒饭','米线','猫耳朵' ,'面包','寿司','烧麦','锅贴','火烧','小笼包' ,'肉夹馍' ,'肉龙');
        foreach ($recipes as $_idx => $recipe) {
            $category = $recipe['category'];
            if (in_array($category, $_noShowCate)) {
                unset($recipes[$_idx]);
                continue;
            }
        }
        $recipes = array_values($recipes);
    }

    protected function outputBody()
    {
        $urlPrefix = '/recipe/similar_recipe_list.php?sid='.$this->sid.'&rid='.$this->rid.'&keyword='.$this->keyword;
        $pageHtml = Str_Html::getSimplePage2($this->start, $this->num, $this->total, $urlPrefix);

        $this->smarty->assign('page_html', $pageHtml);

        $this->smarty->assign('sub_title', '相似菜谱');
        $this->smarty->assign('recipe_list', $this->recipeList);
        $this->smarty->assign('categoryList', Conf_Recipe_Category::getAllCategories());
        $this->smarty->assign('list_name', $this->listName);
        $this->smarty->display('recipe/similar_recipe_list.html');
    }
}

$app = new App('pri');
$app->run();