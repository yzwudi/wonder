<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "console_error_log".
 *
 * @property integer $id
 * @property string $info
 * @property string $create_time
 * @property integer $archive
 */
class ConsoleErrorLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'console_error_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['info'], 'required'],
            [['create_time'], 'safe'],
            [['archive'], 'integer'],
            [['info'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'info' => Yii::t('app', '错误信息'),
            'create_time' => Yii::t('app', '记录时间'),
            'archive' => Yii::t('app', '归档'),
        ];
    }
}
