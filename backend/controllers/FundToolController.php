<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/11/30
 * Time: 21:07
 */

namespace backend\controllers;

use backend\components\FuncHelper;
use backend\models\FundForecastInfo;
use backend\models\FundForecastSearch;
use backend\models\FundTool;
use Yii;
use yii\web\NotFoundHttpException;

class FundToolController extends WonderController
{
    public function actionIndex(){
        $model = new FundTool();
        $str = '';
        $info = '';
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $info = $this->_calculateForecastValue($model->fund_id);
        }
        return $this->render('index', ['model'=>$model, 'str'=>$str, 'info'=>$info]);
    }


    public function actionAddFund(){
        $searchModel = new FundForecastSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new FundForecastInfo();
        if ($model->load(Yii::$app->request->post())){
            $model->name = FuncHelper::getFundNameById(isset($model->fund_id)?$model->fund_id:'');
            if(!$model->name){
                return $this->render('fundForecastIndex', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    'error' => static::buildError([], 1, '基金号对应基金不存在'),
                ]);
            }
            $info = $this->_calculateForecastValue($model->fund_id);
            $model->max_forecast = $info['max'];
            $model->min_forecast = $info['min'];
            $model->avg_forecast = $info['avg'];
            $model->info = $info['info'];
            $model->month = date('Ym', time());
            if($model->save()){
                return $this->redirect(['/fund-tool/add-fund']);
            }else{
                return $this->render('fundForecastIndex', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    'error' => static::buildError([], 1, $model->getErrors()),
                ]);
            }
        }
        return $this->render('fundForecastIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    private function _calculateForecastValue($fundId){
        $month = date('m')==1 ? 12 : date('m')-1;
        $year = date('m')==1 ? date('y')-1 : date('y');
        $dayNum = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $sd = "$year-$month-1";
        $ed = "$year-$month-$dayNum";
        $url = 'http://fund.eastmoney.com/f10/F10DataApi.aspx?type=lsjz&code='.$fundId.'&page=1&per=100&sdate='.$sd.'&edate='.$ed.'&rt=0.19885935480603578';
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
        return $info;
    }

    public function actionDelete($id)
    {

        $this->findModel($id)->delete();

        return $this->redirect(['/fund-tool/add-fund']);
    }

    protected function findModel($id)
    {
        if (($model = FundForecastInfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('请求的基金不存在');
        }
    }
}