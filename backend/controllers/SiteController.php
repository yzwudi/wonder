<?php
namespace backend\controllers;

use backend\models\FundDayInfo;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends WonderController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $fileName = 'uploads/'.date('Ymd', time()).'.txt';
        if(!file_exists($fileName) && date('w') == 2){
            $this->_saveFundDayInfo($fileName);
        }
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    private function _saveFundDayInfo($fileName){
        $url = 'http://fund.eastmoney.com/data/rankhandler.aspx?op=ph&dt=kf&ft=all&rs=&gs=0&sc=3nzf&st=desc&sd='.date('Y-m-d',strtotime('-365 days')).'&ed='.date('Y-m-d',time()).'&qdii=&tabSubtype=,,,,,&pi=1&pn=300&dx=1&v=0.9181909634243504';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $str = str_replace('var rankData = {datas', '{"data"', $output);
        $str = preg_replace('/,allRecords.+/', '}', $str);
        $file = @fopen($fileName, 'w');
        fwrite($file, $str);
        fclose($file);

        $tran = \Yii::$app->db->beginTransaction();
        foreach(json_decode($str, true)['data'] as $key=>$val){
            $fundInfo = new FundDayInfo();
            $info = explode(',', $val);
            $fundInfo->fund_id = $info[0];
            $fundInfo->name = $info[1];
            $fundInfo->name_en = $info[2];
            $fundInfo->date = $info[3];
            $fundInfo->unit_net_value = $info[4];
            $fundInfo->total_net_value = $info[5];
            $fundInfo->day_gr = $info[6];
            $fundInfo->week_gr = $info[7];
            $fundInfo->month_gr = $info[8];
            $fundInfo->three_month_gr = $info[9];
            $fundInfo->six_month_gr = $info[10];
            $fundInfo->year_gr = $info[11];
            $fundInfo->two_year_gr = $info[12];
            $fundInfo->three_year_gr = $info[13];
            $fundInfo->this_year_gr = $info[14];
            $fundInfo->establish_gr = $info[15];
            $fundInfo->self_define = $info[18];
            $fundInfo->poundage = $info[20];
            $fundInfo->create_time = date('Ymd',time());
            if(!$fundInfo->save()){
                $tran->rollBack();
                echo '更新失败:';echo $fundInfo->getErrors();exit;
            };
        }
        $tran->commit();
        unset($str);
        return $this->render('index');
    }
}
