<?php

namespace backend\models;

use backend\components\FuncHelper;
use Yii;

/**
 * This is the model class for table "fund_forecast_info".
 *
 * @property integer $id
 * @property string $fund_id
 * @property string $name
 * @property double $max_forecast
 * @property double $min_forecast
 * @property double $avg_forecast
 * @property string $month
 * @property string $info
 * @property double $current_value
 * @property string $update_date
 *
 */
class FundForecastInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fund_forecast_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fund_id', 'name', 'max_forecast', 'min_forecast', 'avg_forecast', 'month'], 'required'],
            [['fund_id', 'update_date'], 'string'],
            [['fund_id'], 'unique'],
            [['max_forecast', 'min_forecast', 'avg_forecast', 'current_value'], 'number'],
            [['name'], 'string', 'max' => 64],
            [['month'], 'string', 'max' => 16],
            [['info'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fund_id' => '基金ID',
            'name' => '基金名称',
            'max_forecast' => date('m').'预估最高价',
            'min_forecast' => date('m').'预估最低价',
            'avg_forecast' => date('m').'预估买卖价',
            'month' => 'Month',
            'info' => '提示',
            'update_date' => '更新时间',
            'current_value' => '当前净值',
        ];
    }

    public static function buildSearch($searchParams){
        $key = false;
        $params = ['FundForecastSearch'=>[]];
        foreach($searchParams as $key => $val){
            if($val){
                $params['FundForecastSearch'][$key] = $val;
                $key = true;
            }
        }
        if($key){
            Yii::$app->request->setQueryParams($params);
        }
    }

    public static function calculateForecastValue($fundId){
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
