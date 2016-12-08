<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/12/4
 * Time: 21:03
 */

namespace console\controllers;

use backend\models\IndexManage;
use yii;

class FundToolController extends BaseController {
    public function actionGetCompositeIndex(){
        if(IndexManage::findOne(['date' => date('Y-m-d', time())])){
            return self::EXIT_CODE_NORMAL;
        }
        try{
            $url = 'http://www.csindex.com.cn/sseportal/ps/zhs/hqjt/csi/show_zsbx.js';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            $output = (string)iconv("GB2312", "UTF-8", $output);
            $output = str_replace('"', '', $output);
            $output = preg_replace('/\s/',"",$output);
            $output = explode('/', trim(preg_replace('/varzsbx[0-9]+=/', '/', $output), '/'));
            array_shift($output);
            $indexes = [];
            foreach($output as $key=>$val){
                if($key%9 == 0){
                    $name = $val;
                    $indexes[$name]['name'] = $val;
                }
                if($key%9 == 1){
                    $indexes[$name]['index'] = $val;
                }
            }

            $url = 'http://www.csindex.com.cn/sseportal/ps/zhs/hqjt/csi/show_zsgz.js';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            $output = (string)iconv("GB2312", "UTF-8", $output);
            $output = str_replace('"', '', $output);
            $output = preg_replace('/\s/',"",$output);
            $output = explode('/', trim(preg_replace('/varzsgz[0-9]+=/', '/', $output), '/'));
            array_shift($output);
            foreach($output as $key=>$val){
                if($key%9 == 0){
                    $name = $val;
                }
                if($key%9 == 1){
                    $indexes[$name]['pe'] = $val;
                    $indexes[$name]['yield_rate'] = round(100/$val, 2).'%';
                }
                if($key%9 == 3){
                    $indexes[$name]['pb'] = $val;
                }
                if($key%9 == 8){
                    $indexes[$name]['dividend_rate'] = $val;
                    $indexes[$name]['date'] = date('Y-m-d', time());
                }
            }

            $tran = Yii::$app->db->beginTransaction();
            $connection = Yii::$app->db;
            $row = $connection->createCommand()->batchinsert(IndexManage::tableName(),[
                'index_name','index','pe','yield_rate','pb','dividend_rate','date'
            ],$indexes)->execute();

            if($row <= 0){
                $tran->rollBack();
                \Yii::error('Get Composite Index Failed', 'application.fund-tool.get-composite-index');
                return self::EXIT_CODE_ERROR;
            }
            $tran->commit();
            return self::EXIT_CODE_NORMAL;
        }catch (\Exception $e) {
            \Yii::error("Get Composite Index Info failed:" . $e->getMessage(), "application.FundTool.GetCompositeIndex");
            return self::EXIT_CODE_ERROR;
        }


    }
}