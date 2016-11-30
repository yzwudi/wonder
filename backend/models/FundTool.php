<?php

namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "fund_tool".
 *
 * @property integer $fund_id
 */
class FundTool extends Model
{
    public $fund_id;

    public function rules(){
        return[
            [['fund_id'], 'required'],
            [['fund_id'], 'number'],
        ];
    }

    public function attributeLabels(){
        return [
            'fund_id'=>'基金ID',
        ];
    }
}