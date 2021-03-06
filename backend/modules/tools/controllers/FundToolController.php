<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/11/30
 * Time: 21:07
 */

namespace backend\modules\tools\controllers;

use backend\components\FuncHelper;
use backend\controllers\WonderController;
use backend\models\FundForecastInfo;
use backend\models\FundForecastSearch;
use backend\models\FundTool;
use backend\models\IndexManage;
use backend\modules\tools\models\FundValueDayData;
use Yii;
use yii\web\NotFoundHttpException;

class FundToolController extends WonderController
{
    public function actionIndex(){
        $model = new FundTool();
        $str = '';
        $info = '';
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $info = FundForecastInfo::calculateForecastValue($model->fund_id);
        }
        return $this->render('index', ['model'=>$model, 'str'=>$str, 'info'=>$info]);
    }


    public function actionAddFund(){
        $searchModel = new FundForecastSearch();
        $searchId = static::getParam('searchId', '');
        $searchName= static::getParam('searchName', '');
        FundForecastInfo::buildSearch(['fund_id'=>$searchId, 'name'=>$searchName, 'month'=>date('Ym', time())]);
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
            $info = FundForecastInfo::calculateForecastValue($model->fund_id, true);
            if(!$info){
                return $this->render('fundForecastIndex', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    'error' => static::buildError([], 1, '暂无数据，无法添加！'),
                ]);
            }
            $model->max_forecast = $info['max'];
            $model->min_forecast = $info['min'];
            $model->avg_forecast = $info['avg'];
            $model->info = $info['info'];
            $model->month = date('Ym', time());
            $value = FuncHelper::getFundValueAndName($model->fund_id);
            if(!$value){
                return $this->render('fundForecastIndex', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    'error' => static::buildError([], 1, '获取净值失败！'),
                ]);
            }
            $model->current_value = $value;
            if($value < $model->avg_forecast){
                $model->info = $model->info.',当前净值低于买卖价'.round(($model->avg_forecast-$value)/$model->avg_forecast*100, 2).'%,可以考虑购买 !';
            }else{
                $model->info = $model->info.',当前净值高于买卖价'.round(($value-$model->avg_forecast)/$value*100, 2).'%。';
            }
            if($model->save()){
                return $this->redirect(['/tools/fund-tool/add-fund']);
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
            'searchId' => $searchId,
            'searchName' => $searchName,
        ]);
    }

    public function actionFundForecastDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['/tools/fund-tool/add-fund']);
    }

    protected function findModel($id)
    {
        if (($model = FundForecastInfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('请求的基金不存在');
        }
    }

    public function actionCompositeIndex(){
        $date = date('Y-m-d');
        if(isset($_POST['IndexManage']['date'])){
            $date = $_POST['IndexManage']['date'];
        }else{
            $date = self::getParam('date', $date);
        }
        if(strtotime($date) > time()){
            static::buildError([], 1, '查询时间不能大于今日');
        }
        $list = IndexManage::findAll(['date'=>$date]);
        if(!$list){
            static::buildError([], 1, '当前日期暂无内容');
        }
        $model = new IndexManage();
        return $this->render('indexManageIndex', [
            'indexList' => $list,
            'model' => $model,
            'date' => $date,
        ]);
    }

    public function actionForecastDetail($fund_id){
        $dayData = FundValueDayData::find()->select(['date', 'value'])
            ->where(['month'=>date('ym', time()), 'fund_id'=>$fund_id])
            ->orderBy(['date'=>SORT_ASC])
            ->asArray()->all();
        $date = array_column($dayData, 'date');
        $values = array_column($dayData, 'value');
        $count = count($values);
        $fund = FundForecastInfo::findOne(['fund_id'=>$fund_id]);
        $currentValue = $fund->current_value;
        $minValue = $fund->min_forecast;
        $maxValue = $fund->max_forecast;
        $current = ['当前净值'];
        $max = ['最大预估净值'];
        $min = ['最小预估净值'];
        for($i=0; $i<$count; $i++){
            $current[] = $currentValue;
            $max[] = $maxValue;
            $min[] = $minValue;
        }
        return $this->render('forecastDetail', ['date'=>$date, 'values'=>$values, 'current'=>$current, 'max'=>$max, 'min'=>$min]);
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
        if(!$values){
            return '';
        }
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
}