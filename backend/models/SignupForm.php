<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/10/26
 * Time: 20:38
 */
namespace backend\models;
use yii\base\Model;
use backend\models\UserBackend;


/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_compare;

    public $created_at;
    public $updated_at;

    /**
     * @inheritdoc
     * 对数据的校验规则
     */
    public function rules()
    {
        return [
            // 对username的值进行两边去空格过滤
            ['username', 'filter', 'filter' => 'trim'],

            // required表示必须的，也就是说表单提交过来的值必须要有, message 是username不满足required规则时给的提示消息
            ['username', 'required', 'message' => '用户名不可以为空'],

            // unique表示唯一性，targetClass表示的数据模型 这里就是说UserBackend模型对应的数据表字段username必须唯一
            ['username', 'unique', 'targetClass' => '\backend\models\UserBackend', 'message' => '用户名已存在.'],

            // string 字符串，这里我们限定的意思就是username至少包含2个字符，最多255个字符
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['username', 'checkName'],
            // 下面的规则基本上都同上，不解释了

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => '邮箱不可以唯恐'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\UserBackend', 'message' => 'email已经被设置了.'],
            [['password', 'password_compare'], 'required', 'message' => '密码不可以为空'],
            [['password', 'password_compare'], 'string', 'min' => 6, 'tooShort' => '密码至少填写6位'],
            ['password_compare', 'compare', 'compareAttribute' => 'password', 'message' => '两次密码不一致'],
            // default 默认在没有数据的时候才会进行赋值
            [['created_at', 'updated_at'], 'default', 'value' => date('Y-m-d H:i:s')],
        ];
    }

    public function checkName($attribute, $params) {
        $name = $this->username;
        if(!preg_match('/^[0-9a-zA-Z_]+$/', $name)){
            $this->addError($attribute, '用户名格式错误');
        }
    }


    /**
     * Signs user up.
     *
     * @return true|false 添加成功或者添加失败
     */
    public function signup()
    {
        // 调用validate方法对表单数据进行验证，验证规则参考上面的rules方法
        if (!$this->validate()) {
            return null;
        }
        $tran = \Yii::$app->db->beginTransaction();
        // 实现数据入库操作
        $user = new UserBackend();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->created_at = $this->created_at;
        $user->updated_at = $this->updated_at;

        // 设置密码，密码肯定要加密，暂时我们还没有实现，看下面我们有实现的代码
        $user->setPassword($this->password);

        // 生成 "remember me" 认证key
        $user->generateAuthKey();

        // save(false)的意思是：不调用UserBackend的rules再做校验并实现数据入库操作
        // 这里这个false如果不加，save底层会调用UserBackend的rules方法再对数据进行一次校验，因为我们上面已经调用Signup的rules校验过了，这里就没必要在用UserBackend的rules校验了
        if(!$user->save(false)){
            $tran->rollback();
            echo '注册失败';
            exit;
        }
        $auth = new AuthAssignment(['item_name'=>'普通用户', 'user_id'=>$user->id]);
        if(!$auth->save(false)){
            $tran->rollback();
            echo '注册失败';
            exit;
        }
        $_SESSION['__id'] = $user->id;
        $tran->commit();
        return true;
    }
}