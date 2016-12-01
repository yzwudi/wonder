<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/11/30
 * Time: 21:07
 */

namespace backend\controllers;

use backend\components\FuncHelper;
use backend\models\FundTool;
use Yii;

class FundToolController extends WonderController
{
    public function actionIndex(){
        $model = new FundTool();
        $str = '';
        $info = '';
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $month = date('m')==1 ? 12 : date('m')-1;
            $year = date('m')==1 ? date('y')-1 : date('y');
            $dayNum = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $sd = "$year-$month-1";
            $ed = "$year-$month-$dayNum";
            $url = 'http://fund.eastmoney.com/f10/F10DataApi.aspx?type=lsjz&code='.$model->fund_id.'&page=1&per=100&sdate='.$sd.'&edate='.$ed.'&rt=0.19885935480603578';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            $str = str_replace('var apidata={ content:"', '', $output);
            $str = str_replace('w782 comm lsjz', 'table', $str);
            $str = preg_replace('/",records.+/', '', $str);
            $datas = FuncHelper::getArrayFromHtmlTable($str);
            $values = [];
            foreach($datas as $data){
                if(!empty($data) && isset($data[1])){
                    $values[] = $data[1];
                }
            }
            $str = max($values).'/'.min($values).'/'.round(array_sum($values)/count($values),4);
            if($values[count($values)-1] > round(array_sum($values)/count($values),4)){
                $keyHeight = 1.02;
                $keyLow = 1;
                $str = '上月月末估值大于上月平均值';
            }else{
                $keyHeight = 1;
                $keyLow = 0.98;
                $str = '上月月末估值小于上月平均值';
            }
            $info = [
                'max' => round(max($values) * $keyHeight, 4),
                'min' => round(min($values) * $keyLow, 4),
                'avg' => round(array_sum($values)/count($values),4),
                'info' => $str,
            ];
        }
        return $this->render('index', ['model'=>$model, 'str'=>$str, 'info'=>$info]);
    }
}