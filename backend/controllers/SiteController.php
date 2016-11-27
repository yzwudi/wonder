<?php
namespace backend\controllers;

use backend\models\FundBestInfo;
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
                        'actions' => ['login', 'error', 'register'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => ['register'],
                        'allow' => false,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => [],
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
        if(!file_exists($fileName) && date('w') == 0){
            $this->_saveFundDayInfo($fileName);
        }
        return $this->render('index');
    }

    public function actionRegister(){
        $this->layout = false;
        $model = new \backend\models\SignupForm();

        // 如果是post提交且有对提交的数据校验成功（我们在SignupForm的signup方法进行了实现）
        // $model->load() 方法，实质是把post过来的数据赋值给model
        // $model->signup() 方法, 是我们要实现的具体的添加用户操作
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return $this->redirect(['index']);
        }

        // 渲染添加新用户的表单
        return $this->render('register', [
            'model' => $model,
        ]);

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

    /**
     * 获取基金信息
     *
     * @param $fileName
     * @return string
     * @throws \yii\db\Exception
     */
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
        $this->_getBest();
        $tran->commit();
        unset($str);
        return $this->render('index');
    }

    /**
     * 获取最佳基金top20
     *
     * @throws \yii\db\Exception
     */
    public function _getBest()
    {
        $date = FundDayInfo::find()->select(['MAX(create_time)'])->scalar();
        $isExist = FundBestInfo::find()->select(['create_time'])->where(['create_time'=>$date])->asArray()->all();
        if(empty($isExist)){
            $data = FundDayInfo::find()->where(['create_time'=>$date])->asArray()->all();
            $resultData = static::multi_array_sort($data, 'two_year_gr', 'desc');
            $resultData = array_slice($resultData, 0, 150);
            $resultData = static::multi_array_sort($resultData, 'year_gr', 'desc');
            $resultData = array_slice($resultData, 0, 100);
            $resultData = static::multi_array_sort($resultData, 'six_month_gr', 'desc');
            $resultData = array_slice($resultData, 0, 50);
            $resultData = static::multi_array_sort($resultData, 'three_month_gr', 'desc');
            $resultData = array_slice($resultData, 0, 30);
            $resultData = static::multi_array_sort($resultData, 'month_gr', 'desc');
            $resultData = array_slice($resultData, 0, 20);
            $information = [];
            foreach($resultData as $key=>$info){
                $information[] = [$info['fund_id'],$info['name'],$info['name_en'],
                    $info['date'],$info['unit_net_value'],$info['total_net_value'],
                    $info['day_gr'],$info['week_gr'],$info['month_gr'],
                    $info['three_month_gr'],$info['six_month_gr'],$info['year_gr'],
                    $info['two_year_gr'],$info['three_year_gr'],$info['this_year_gr'],
                    $info['establish_gr'],$info['self_define'],$info['poundage'],
                    $info['create_time'],
                ];
            }
            $params = [
                'fund_id','name','name_en',
                'date','unit_net_value','total_net_value',
                'day_gr','week_gr','month_gr',
                'three_month_gr','six_month_gr','year_gr',
                'two_year_gr','three_year_gr','this_year_gr',
                'establish_gr','self_define','poundage',
                'create_time'

            ];
            $tran = Yii::$app->db->beginTransaction();

            $connection = Yii::$app->db;
            $row = $connection->createCommand()->batchinsert(FundBestInfo::tableName(),$params,$information)->execute();

            if($row <= 0){
                $tran->rollBack();
                echo '添加失败';exit;
            }
            $tran->commit();
        }

        echo '添加成功';
    }
}
