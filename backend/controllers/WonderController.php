<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/10/26
 * Time: 21:32
 */

namespace backend\controllers;

use yii;
use yii\filters\AccessControl;

class WonderController extends yii\web\Controller {

    public $errorMsg = '';

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 数组排序
     * @param array $multi_array 需要排序的数组
     * @param string $sort_key 按照哪个字段排序
     * @param string $sort 正序/倒序
     * @return boolean|array
     */
    public static function multi_array_sort($multi_array, $sort_key, $sort = 'asc')
    {
        if (!empty($multi_array) && is_array($multi_array)) {
            foreach ($multi_array as $row_array) {
                if (is_array($row_array)) {
                    $key_array[] = $row_array[$sort_key];
                } else {
                    return [];
                }
            }
        } else {
            return [];
        }
        $sort = $sort == 'asc' ? SORT_ASC : SORT_DESC;
        array_multisort($key_array, $sort, $multi_array);
        return $multi_array;
    }

    public static function buildError($data, $code, $meg){
        while (is_array($meg)) {
            $meg = array_shift($meg);
        }

        $ret = [
            'data' => $data,
            'code' => (int)$code,
            'msg' => $meg,
        ];
        $view = YII::$app->view;
        $view->params['errorMsg'] = $ret;
        return $ret;
    }

}