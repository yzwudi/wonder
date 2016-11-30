<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/11/30
 * Time: 21:07
 */

namespace backend\controllers;

use backend\models\FundTool;
use Yii;

class FundToolController extends WonderController
{
    public function actionIndex(){
        $model = new FundTool();
        $str = '';
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $url = 'http://fund.eastmoney.com/f10/F10DataApi.aspx?type=lsjz&code='.$model->fund_id.'&page=1&per=100&sdate='.date('Y-m-d',strtotime('-30 days')).'&edate='.date('Y-m-d').'&rt=0.19885935480603578';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            $str = str_replace('var apidata={ content:"', '', $output);
            $str = str_replace('w782 comm lsjz', 'table', $str);
            $str = preg_replace('/",records.+/', '', $str);

        }
        return $this->render('index', ['model'=>$model, 'str'=>$str]);
    }
}