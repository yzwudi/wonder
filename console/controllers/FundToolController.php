<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/12/4
 * Time: 21:03
 */

namespace console\controllers;

use backend\components\FuncHelper;
use backend\models\ConsoleErrorLog;
use backend\models\FundForecastInfo;
use backend\models\IndexManage;
use yii;

class FundToolController extends BaseController {
    //获取每日大盘数据
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
                $log = new ConsoleErrorLog([
                    'info' => '获取指数信息失败',
                    'archive' => 0,
                    'create_time' => date('Y-m-d H:i:s', time()),
                ]);
                if(!$log->save()){
                    $errors = $log->getErrors();
                    \Yii::error("错误日志记录失败;错误信息为".is_array($errors) ? array_shift($errors) : $errors."。", "application.Tools.Get.Composite.Index");
                    return self::EXIT_CODE_ERROR;
                }
                return self::EXIT_CODE_ERROR;
            }
            $tran->commit();
            return self::EXIT_CODE_NORMAL;
        }catch (\Exception $e) {
            \Yii::error("Get Composite Index Info failed:" . $e->getMessage(), "application.FundTool.GetCompositeIndex");
            $log = new ConsoleErrorLog([
                'info' => '获取指数信息失败',
                'archive' => 0,
                'create_time' => date('Y-m-d H:i:s', time()),
            ]);
            if(!$log->save()){
                $errors = $log->getErrors();
                \Yii::error("错误日志记录失败;错误信息为".is_array($errors) ? array_shift($errors) : $errors."。", "application.Tools.Get.Composite.Index");
                return self::EXIT_CODE_ERROR;
            }
            return self::EXIT_CODE_ERROR;
        }
    }

    public function actionGetForecastInfo(){
        $code = $this->_getForecastInfo();
        if($code == self::EXIT_CODE_ERROR){
            $log = new ConsoleErrorLog([
                'info' => '获取基金估值失败',
                'archive' => 0,
                'create_time' => date('Y-m-d H:i:s', time()),
            ]);
            if(!$log->save()){
                $errors = $log->getErrors();
                \Yii::error("错误日志记录失败;错误信息为".is_array($errors) ? array_shift($errors) : $errors."。", "application.Tools.Get.Forecast.Info");
                return self::EXIT_CODE_ERROR;
            }
        }
        return $code;
    }

    public function _getForecastInfo(){
        $ids = FundForecastInfo::find()->select(['id'])->asArray()->column();
        $month = date('Ym', time());
        foreach($ids as $id){
            try{
                $fund = FundForecastInfo::findOne($id);
                if($fund){
                    $value = FuncHelper::getFundValueAndName($fund->fund_id);
                    if(!$value){
                        \Yii::error("基金$fund->fund_id 获取净值失败！id为$fund->id;", "application.Tools.Get.Forecast.Info");
                        return self::EXIT_CODE_ERROR;
                    }
                    $fund->current_value = $value;
                    if($fund->month != $month){
                        $info = FundForecastInfo::calculateForecastValue($fund->fund_id);
                        if(!$info){
                            \Yii::error("基金$fund->fund_id 获取信息失败！id为$fund->id;", "application.Tools.Get.Forecast.Info");
                            return self::EXIT_CODE_ERROR;
                        }
                        $fund->max_forecast = $info['max'];
                        $fund->min_forecast = $info['min'];
                        $fund->avg_forecast = $info['avg'];
                        $fund->info = $info['info'];
                        $fund->month = $month;
                        $name = FuncHelper::getFundNameById(isset($fund->fund_id)?$fund->fund_id:'');
                        if(!$name){
                            \Yii::error("基金$fund->fund_id 获取基金名称失败！id为$fund->id;", "application.Tools.Get.Forecast.Info");
                            return self::EXIT_CODE_ERROR;
                        }
                        $fund->name = $name;
                    }
                    $info = explode(',', $fund->info)[0];
                    if($value < $fund->avg_forecast){
                        $fund->info = $info.',当前净值低于买卖价'.round(($fund->avg_forecast-$value)/$fund->avg_forecast*100, 2).'%,可以考虑购买 !';
                    }else{
                        $fund->info = $info.',当前净值高于买卖价'.round(($value-$fund->avg_forecast)/$value*100, 2).'%。';
                    }
                    if(!$fund->save()){
                        $errors = $fund->getErrors();
                        \Yii::error("基金$fund->fund_id 保存失败!id为$fund->id;错误信息为".is_array($errors) ? array_shift($errors) : $errors."。", "application.Tools.Get.Forecast.Info");
                        return self::EXIT_CODE_ERROR;
                    }
                }
            }catch(\Exception $e){
                \Yii::error("Get Forecast Info Failed:" . $e->getMessage(), "application.Tools.Get.Forecast.Info");
                return self::EXIT_CODE_ERROR;
            }
        }
        return self::EXIT_CODE_NORMAL;
    }
}
