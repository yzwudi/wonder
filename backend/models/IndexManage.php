<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "index_manage".
 *
 * @property integer $id
 * @property string $index_name
 * @property double $index
 * @property double $pe
 * @property string $yield_rate
 * @property double $pb
 * @property double $dividend_rate
 * @property string $date
 */
class IndexManage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'index_manage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['index_name', 'index', 'pe', 'yield_rate', 'pb', 'dividend_rate', 'date'], 'required'],
            [['index', 'pe', 'pb', 'dividend_rate'], 'number'],
            [['index_name'], 'string', 'max' => 32],
            [['yield_rate', 'date'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'index_name' => Yii::t('app', '指数名称'),
            'index' => Yii::t('app', '指数值'),
            'pe' => Yii::t('app', '市盈率'),
            'yield_rate' => Yii::t('app', '盈利收益率'),
            'pb' => Yii::t('app', '市净率'),
            'dividend_rate' => Yii::t('app', '股息率'),
            'date' => Yii::t('app', '日期'),
        ];
    }
}
