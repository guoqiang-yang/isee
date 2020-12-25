<?php
include_once('../../global.php');
class App extends App_Cli
{
    protected function main()
    {
        $this->_testSetConstitution();
    }

    private function _testSetConstitution()
    {
        $uid = 1004;
        $main = '平和质';//'平和质','气虚质','阳虚质','阴虚质','痰湿质','湿热质','血瘀质','气郁质','特禀质'
        $secondary = array();
        $scores = array($main=>100);

        //保存体质
        $constitutionArr = array('main' => $main, 'secondary' => $secondary, 'vector' => $scores);
        $uc = User_Constitution::getInstance();
        $res = $uc->save($uid, $constitutionArr);

        $materialScores = Recommend_Material_User_Vector::compute($constitutionArr);
        $ret = Recommend_Material_User_Vector::save($uid, $materialScores);
        $this->_trace('User_Material_Archive::saveArchive, ret = %d', intval($ret));

        //计算菜谱得分
        $recipeScores = Recommend_Recipe_User_Vector::compute($uid);
        $ret = Recommend_Recipe_User_Vector::save($uid, $recipeScores);
        $this->_trace('User_Recipe_Archive::saveArchive, ret = %d', intval($ret));
    }
}

$app = new App();
$app->run();
