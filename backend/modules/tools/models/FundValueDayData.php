<?php

namespace backend\modules\tools\models;

use Yii;

/**
 * This is the model class for table "fund_value_day_data".
 *
 * @property integer $id
 * @property string $fund_id
 * @property string $date
 * @property string $value
 * @property string $month
 */
class FundValueDayData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fund_value_day_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fund_id', 'date', 'value', 'month'], 'required'],
            [['date'], 'safe'],
            [['value'], 'number'],
            [['fund_id', 'month'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fund_id' => '基金id',
            'date' => '日期',
            'value' => '净值',
            'month' => '统计月份',
        ];
    }
}
