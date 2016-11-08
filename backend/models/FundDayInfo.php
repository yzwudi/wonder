<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "fund_day_info".
 *
 * @property integer $id
 * @property integer $fund_id
 * @property string $name
 * @property string $name_en
 * @property string $date
 * @property double $unit_net_value
 * @property double $total_net_value
 * @property double $day_gr
 * @property double $week_gr
 * @property double $month_gr
 * @property double $three_month_gr
 * @property double $six_month_gr
 * @property double $year_gr
 * @property double $two_year_gr
 * @property double $three_year_gr
 * @property double $this_year_gr
 * @property double $establish_gr
 * @property double $self_define
 * @property double $poundage
 * @property string $create_time
 */
class FundDayInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fund_day_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fund_id', 'name', 'name_en', 'date'], 'required'],
            [['date', 'create_time'], 'safe'],
            [['unit_net_value', 'total_net_value', 'day_gr', 'week_gr', 'month_gr', 'three_month_gr', 'six_month_gr', 'year_gr', 'two_year_gr', 'three_year_gr', 'this_year_gr', 'establish_gr', 'self_define'], 'number'],
            [['name'], 'string', 'max' => 64],
            [['name_en', 'poundage', 'fund_id'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fund_id' => '基金号',
            'name' => '名称',
            'name_en' => '英文缩写',
            'date' => '基金统计时间',
            'unit_net_value' => 'Unit Net Value',
            'total_net_value' => 'Total Net Value',
            'day_gr' => 'Day Gr',
            'week_gr' => '近一周',
            'month_gr' => '近一月',
            'three_month_gr' => '近三月',
            'six_month_gr' => '近六月',
            'year_gr' => '近一年',
            'two_year_gr' => 'Two Year Gr',
            'three_year_gr' => '三年来',
            'this_year_gr' => '今年来',
            'establish_gr' => 'Establish Gr',
            'self_define' => 'Self Define',
            'poundage' => 'Poundage',
            'create_time' => 'Create Time',
        ];
    }
}
