<?php

namespace console\controllers;

use yii;
use yii\console\Controller;


class BaseController extends Controller {
    private $startTime;
    private $endTime;

    public function beforeAction($action){
        $this->startTime = microtime(true);
        if(parent::beforeAction($action)){
            Yii::beginProfile($this->id."/".$action->id,"application.console");
            Yii::info($this->id."/".$action->id . " start ","application.console");
        }

        return true;
    }


    public function afterAction($action, $result){
        Yii::endProfile($this->id."/".$action->id,"application.console");
        $this->endTime = microtime(true);
        $interval = number_format($this->endTime - $this->startTime,3);
        Yii::info($this->id."/".$action->id . " finished with status: ".$result." Elapsed: ".$interval . "s","application.console");

        return $result;
    }

}
