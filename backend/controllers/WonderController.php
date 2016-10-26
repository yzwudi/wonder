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

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['info',],
                        'roles'   => ['?']
                    ],
                    [
                        'allow'   => true,
                        'roles'   => ['@']
                    ],

                ],
            ],
        ];
    }

}