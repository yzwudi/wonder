<?php

namespace backend\models;

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
}
