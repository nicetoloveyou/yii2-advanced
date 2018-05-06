<?php

namespace frontend\controllers;

use yii;
use app\models\Customer;
use app\models\TestUser;
use yii\web\UploadedFile;
use app\helpers\SimpleStringHelper;
use yii\helpers\VarDumper;
//use \frontend\components\Tools;
//use \yii\db\Connection;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\FormatConverter;

use components\Tools;
use components\Car;
use yii\db\Query;
use yii\db\ActiveQuery;

class ToolsController extends \yii\web\Controller
{

    /**
     * Search 
     */
    public function actionSearch()
    {


        die();
        echo Yii::getVersion();

        // exit;
        $orderBy = ['id' => SORT_DESC, 'status'=> SORT_ASC];
        // $condition = ['id' => ':id', 'status' => ':status']; // 不支持数组形式
        $condition = 'id=:id And status=:status';
        $id = 1;
        $status = 10;
        $params = [':id' => $id, ':status' => $status];

        $offset = 0;
        $limit = 100;
        
        $query = TestUser::find()
        ->where($condition, $params)
        // ->addParams([':status' => $status])
        ->offset($offset)
        ->limit($limit)
        ->orderBy($orderBy);        
        $data = $query->asArray()->all();        
        var_dump($data);

        exit;
        // ----------------------------------------------------------------------
        
        //$model = TestUser::find()->one();
        $query2 = new Query();
        $cmd = $query2->select('*')->from('user')->where($condition, $params);
        $result = $cmd->all();
        //VarDumper::dump($cmd, 999, true);
        
        // ----------------------------------------------------------------------
        
        $sql = 'select * from user where id=:id limit 1';
        $cmd2 = Yii::$app->db->createCommand($sql)->bindValues([':id' => 1]);
        $result2 = $cmd2->queryAll();
        
        VarDumper::dump($result2, 999, true);
    }
    
    public static function parseCondition($string)
    {
        $string = '&id=100?usename=aaaa';

    }
    
    
    public function actionTestObject()
    {
        $car = new Car(10000, 20);
        $car->price = 1;
        //$car->card= 1010088;
        VarDumper::dump(empty($car->price), 9, true);
        
    }
    
    public function actionHelper()
    {
        $tools = new Tools([], '1.0.0', 'Jim');
        //$tools->author = 'author ...';
        $tools->version = '1.0.000';
        
        // var_dump(empty($tools->author), isset($tools->author));
        
        
        exit;
        $array = [
            [
                'name' => 'username', 
                'rule' => 'string', 
                'label' => 'userName', 
                'min_length' => 10, 
                'max_length' => 20,
                'required' => true,
                'placeholder' => 'Please enter your password',
            ]
            ,
            'b' => 'cccccc'       
        ];
        //var_dump(Url:: current());
        //echo Url::ensureScheme('http://www.digpage.com/index.html', 'https'); //(['site/index', 'a'=>'b', 'c' => 100]);
//         $customer = TestUser::find(['id' => 1])->one(); //->asArray()->one()
//         $arr = ArrayHelper::toArray($customer);
        
        //$r = ArrayHelper::isSubset(['cccccc'], $array);
        $r = Html::tag('img', 'liiiiiiiiiiiii', ['src'=>'./logo.png', 'alt'=>'logo', 'width'=> 100]);
        VarDumper::dump( $r, 999, true);
        
        exit;
       //  VarDumper::dump( ArrayHelper::index($array, 'required'),                       999, true );
        
        $required = ArrayHelper::getColumn($array, function($elment){
            if(empty($elment['placeholder'])) return $elment['name'];
        });
            VarDumper::dump( implode(',', $required),                     999, true );
//        VarDumper::dump( ArrayHelper::index($array, 'label'),                       999, true );
        // VarDumper::dump( ArrayHelper::remove($array, 'detail') , 999, true );
        // VarDumper::dump( $array );
        
        exit;
        $string = SimpleStringHelper::toImplode(',', ['a']);
        VarDumper::dump( $string, 999, true );
    }
    
    /**
     * !CodeTemplates.overridecomment.nonjd!
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
        return [
           'httpcache' => [
                'class' => 'yii\filters\HttpCache',
                'only' => ['index'],
                'lastModified' => function(){
                    return time();
                },
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    //'index'  => ['get'],
                    'test'  => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['access', 'local'], //指定需要检测的操作, except 用于排除
                'rules' => [
                    [
                        'actions' => ['access'],
                        'allow' => true,
                        //`?`: matches a guest user (not authenticated yet)
                        //* - `@`: matches an authenticated user
                        //'roles' => [], // all
                    ],
                    [
                        'actions' => ['local'], 
                        'allow' => true, 
                        'ips' => ['*']
                    ],
                ],
            ],
        ];
    }
    
//     public function actions()
//     {
//         return [
//             'error' => [
//                 'class' => 'yii\web\ErrorAction',
//             ],
//         ];
//     }

//     /**
//      * 自定义错误
//      * @return  <string, string>
//      */
//     public function actionError()
//     {
//         $exception = Yii::$app->errorHandler->exception;
//         if ($exception !== null) {
//             return $this->render('error', ['exception' => $exception]);
//         }
//     }
    /**
     * test
     */
    public function actionView($id)
    {
        var_dump($id);
        echo 'test';
    }
    /**
     * test
     */
    public function actionTest()
    {
        return $this->redirect('/tools/access', 301);
        echo 'test';
    }
    /**
     * test
     */
    public function actionAccess()
    {
        echo 'access';
    }
    /**
     * test
     * request response exception 类的用法
     * Yii::$app->request->get('id') / post()
     */
    public function actionLocal($id)
    {
        $arr = ['a'=>'b'];
        var_dump(each($arr)); die();
        list($a, $b) = ['aaaaa', 'bbbbbb'];
        //var_dump($a,$b);
        
//       //response
//         echo \yii\helpers\Json::encode([
//             ['id' => 100, 'city' => '成都'],
//             ['id' => 222, 'city' => 'lake'],
//         ]);
//         Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
//         Yii::$app->response->send();
//         //Yii::$app->response->clear();
//         Yii::$app->end();

//         Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
//         return [
//             'message' => 'hello world',
//             'code' => 100,
//         ];
        
//         //exception
//         throw new \yii\web\NotFoundHttpException('Not Found Exception', 404);       
//         throw new \frontend\components\MyException(403, 'MyException 404 Exception');
        
        var_dump($id, Yii::$app->request->get(), Yii::$app->request->method);
        
        exit;
        echo 'ips';
    }
    
    public function actionRedirect()
    {
        return $this->redirect('/tools/access', 301);
    }
    
    public function actionDownload()
    {
        $filepath = Yii::getAlias('@frontend/files');
        return Yii::$app->response->sendfile($filepath . '/file.txt'); // 直接 @frontend/files是不行的
    }
    /**
     * @see http://www.yiichina.com/doc/guide/2.0/runtime-sessions-cookies
     */
    public function actionSession()
    {
        Yii::$app->session->open();
        Yii::$app->session->set('city', 'Chengdu');
        $city = Yii::$app->session->get('city');
        Yii::$app->session->remove('city');
        
        $captcha = [
            'number' => '',
            'lifetime' => 3600
        ];
        Yii::$app->session->set('captcha', $captcha);
        $captcha = Yii::$app->session->get('captcha');
        //Yii::$app->session->close();
        //Yii::$app->session->destroy();
        var_dump($city, $captcha);
    }
    /**
     * cookie使用
     * @see http://www.yiichina.com/doc/guide/2.0/runtime-sessions-cookies
     */
    public function actionCookie()
    {
        // 读取 Cookies
        // 从 "request" 组件中获取 cookie 集合(yii\web\CookieCollection)
        $cookies = Yii::$app->request->cookies;
        $language = $cookies->getValue('language', 'en');
        var_dump($cookies);
        // 发送 Cookies
        // 从 "response" 组件中获取 cookie 集合(yii\web\CookieCollection)
//         $cookie_response = Yii::$app->response->cookies;
//         $cookie_response->add(new \yii\web\Cookie([
//             'name' => 'language',
//             'value' => 'zh-CN',
//         ]));
        
    }
    /**
     * 日志使用
     */
    public function actionLog()
    {
        // main 文件log配置要设置levels对应的日志记录级别
        echo date('Y-m-d H:i:s', time());
        $message = 'yiisoft/yii2-faker: 提供了使用 Faker 的支持，为你生成模拟数据。';
        //Yii::trace($message);
        //Yii::info($message, 'order_category');
        //Yii::error($message, 'log_warning');
        Yii::beginProfile('block1', 'test');
        $model = \app\models\TestUser::findOne(['id' => 1]);
        Yii::endProfile('block1');
    }
    /**
     * 对象初始化
     * @see \yii\base\Object::init()
     */
    public function init()
    {
        parent::init();
        //事件绑定处理器: 记住绑定和触发一定要在同一个类里面
        $this->on(\frontend\components\Order::EVENT_ORDER_UPDATE, ['frontend\components\OrderEvent', 'notify']);
    }
    /**
     * 事件使用 event
     * @see http://www.yiichina.com/doc/guide/2.0/concept-events
     */
    public function actionEvent()
    {
        $order = Yii::createObject([
            'class' => 'frontend\components\Order',
        ]);
        //更新操作
        $order->update();
        //触发更新操作事件
        $event = new \frontend\components\OrderEvent();
        $event->userId = 33333366;
        $this->trigger(\frontend\components\Order::EVENT_ORDER_UPDATE, $event);
        
        echo 'end event';
    }
    
    public function actionBehavior()
    {
        $user = \app\models\TestUser::findOne(['id' => 2]);
        $user->email = 'jimiy@163.com';
        $user->save();
        $name = '杨采妮';        
        echo $user->updated_at;
        
        //echo Yii::t('part', 'order');
        //echo Yii::t('part', 'Your name is {name}', ['name' => $name]);
    }
    /**
     * db 数据库类使用测试
     * @see http://www.yiichina.com/doc/guide/2.0/db-dao
     */
    public function actionDb()
    {
        $db = Yii::$app->db;
//         // DAO方式
//         $create_table = $db->createCommand()->createTable('post', [
//             'id' => 'pk',
//             'title' => 'string',
//             'text' => 'text',
//         ]);
        // dao queryBuilder  activeRecord
        
        // dao
        // $schema = $db->getTableSchema('migration');
        // 在执行语句前你将占位符绑定到 $id 变量， 然后在之后的每次执行前改变变量的值（这通常是用循环来完成的）。 
        // 以这种方式执行查询比为每个不同的参数值执行一次新的查询要高效得多得多。
        $command = Yii::$app->db->createCommand('Select * from user where id=:id')->bindParam(':id', $id);
        $id = 1;
        $user = $command->queryOne();
        //var_dump($user);
        
//         // queryBuilder
//         $query = new yii\db\Query();
//         //$query->select(["CONCAT(first_name, ' ', last_name) AS full_name", 'email']); 
//         $countQuery = (new yii\db\Query())->select('count(id)')->from('user');
//         $user = $query
//             ->select(['*'])
//             ->distinct('id')
//             ->from('user u')
//             //->where('id=:id And username=:u', [':id' => $id, ':u' => 'ok'])
//             ->where('id=:id And username=:u')
//             ->addParams([':id' => $id, ':u' => 'ok'])
//             ->andWhere('status !=""')
//             ->indexBy('id')
//             ->limit(1)
//             ->offset(0)
//             ->one();
//         var_dump($user);
        
//         // filterWhere 的用法
//         // batch 批处理查询: 当使用all()查询的时候会非常耗性能，所以使用batch可以减少内存压力
//         $query = (new yii\db\Query())->from('user')->orderBy('id Asc');
//         $data = [];
//         foreach($query->batch(100) as $rows) //每次查询100行 降低内存的占用率
//         {
//             $data = array_merge($data, $rows); 
//         }
//         var_dump($data);
        
        
        
        //activeRecord 一个model类对应一张表
        //$user = \app\models\TestUser::findOne(['id' => 2]);
        //var_dump($user->createdAtText);
        //$user = \app\models\TestUser::findBySql('Select * from user')->asArray()->all();
        //$user = \app\models\TestUser::find()->asArray()->all(); //Note: asArray()放在all前面
        //var_dump($user);
        
        //--------------------------------------------------------
        
    }
    /**
     * 事务测试
     * @throws Exception
     */
    public function actionDransaction()
    {
        $user = User::findOne(['id' => 2]);
        $transaction = User::getDb()->beginTransaction();
        try{
            $user->email = 'DemaonKdouly@163.com';
            $user->save();
            $transaction->commit();
            echo 'commited ... ';
        }
        catch(\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
//         catch(\Throwable $e) {
//             $transaction->rollBack();
//             throw $e;
//         }
    }
    
    /**
     * redis测试
     * @see http://www.yiichina.com/doc/guide/2.0/yii2-redis
     */
    public function actionRedis()
    {
//         $zs = Yii::$app->cache->get('zs');
//         $message = "i have recieve it now !";
//         $result = Yii::$app->cache->set('message', $message);
//         if($result) {
//             $message = Yii::$app->cache->get('message');
//             var_dump($message);
//         }
//         var_dump(Yii::$app->cache->get('zs'));
//         var_dump(Yii::$app->cache->set('zs', 'wowowowoow', 100));
//         var_dump(Yii::$app->cache->get('zs'));
//         // $redis = Yii::$app->cache->redis; 能读到未序列化的数据
        
//         // 用redis来存储session
//         Yii::$app->session->open();
//         Yii::$app->session->set('city', 'Chengdu');
//         $city = Yii::$app->session->get('city');
//         var_dump($city);

        //使用redis active record
//         $customer = new Customer();
//         $customer->name = 'marko';
//         $customer->age = 18;
//         $customer->phone = 13888888888;
//         $customer->status = 1;
//         $ret = $customer->save();
        $customer = Customer::find()->asArray()->all();
        var_dump($customer);
    }
    
    /**
     * 表单使用测试:
     *  1) rule验证
     *  2) 文件上传
     *  3) Alert::widget 提示使用
     *  @see http://www.yiichina.com/doc/guide/2.0/input-file-upload
     */
    public function actionForm()
    {
        //$model = new Customer();
        $model = Customer::findOne(['id' => 1]);
        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            // 可以用 imageFileName = $model->imageFile->name 保存文件名称
            // 多文件请用 $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            if ($model->validate()) {
                if ($model->uploadImageFile()) {
                    // 文件上传成功
                    //return;
                    Yii::$app->session->open();
                    Yii::$app->session->setFlash('uploaded', '文件上传成功');
                }
            }
            else{
                var_dump($model->imageFile, $model->errors);exit;
            }
        }
        //var_dump(Yii::$app->request);exit;
        return $this->render('form', [
            'model' => $model,
        ]);
    }
    
    /**
     * 模型使用：
     * @see http://www.yiichina.com/doc/guide/2.0/input-multiple-models
     */
    public function actionModel()
    {
        $user = TestUser::findOne(['id' => 1]);
        $user = new TestUser();
        $user->save();
    }
    
    /**
     * fomratter 格式化使用：
     * @see http://www.yiichina.com/doc/guide/2.0/output-formatting
     */
    public function actionFormatter()
    {
        $formatter = Yii::$app->formatter;
        $date = $formatter->asDate(time(), 'full');
        //$currency = $formatter->asCurrency(100);
        $percesion = $formatter->asDecimal(100.36454545, 2);
        $percent = $formatter->asPercent(0.125, 2);
        var_dump($date, $percesion, $percent);
    }
    
    /**
     * Page 分页使用：
     * yii\data\Pagination
     * @see http://www.yiichina.com/doc/guide/2.0/output-pagination
     */
    public function actionPage()
    {
        
    }
    
    /**
     * Sort 排序使用：
     * yii\data\Sort
     * @see http://www.yiichina.com/doc/guide/2.0/output-sorting
     */
    public function actionSort()
    {
    
    }
    
    /**
     * dataProvider 数据提供者使用：
     * yii\data\Sort
     * @see http://www.yiichina.com/doc/guide/2.0/output-data-providers
     */
    public function actionProvider()
    {
        // ActiveProvider
        // SqlDataProvider
        // ArrayProvider
    }
    /**
     * Widgets 数据小部件使用：ListView DetailView GridView
     * yii\data\Sort
     * @see http://www.yiichina.com/doc/guide/2.0/output-data-widgets
     */
    public function actionWidgets()
    {
        
    }
    /**
     * CSS JS Asset 资源包注册使用
     * yii\web\Asset
     * @see http://www.yiichina.com/doc/guide/2.0/output-client-scripts
     */
    public function actionAssets()
    {
    
    }
    /**
     * 用户认证
     * yii\web\user
     * @see http://www.yiichina.com/doc/guide/2.0/security-authentication
     */
    public function actionAuthen()
    {
        var_dump(Yii::$app->user); 
    }
    /**
     * 用户授权
     * yii
     * @see http://www.yiichina.com/doc/guide/2.0/security-authorization
     */
    public function actionAuthorize()
    {
        var_dump(Yii::$app->user);
    }
    /**
     * 安全:
     *  1) SQL  sql注入
     *  2) XSS  脚本 恶意代码
     *  3) CSRF 伪造请求，盗用用户身份 , 在第三方的网站上 挂目标链接进行非法操作. 
     *     防御基本方法：验证referer、使用token、自定义属性http头部并验证
     * 
     * yii
     * @see http://www.yiichina.com/doc/guide/2.0/security-best-practices
     */
    public function actionSecurity()
    {
        //echo htmlspecialchars_decode(htmlspecialchars('<a href="http://www.baidu.com">Baidu.com</a>'));
        //echo  htmlentities(('<a href="http://www.baidu.com">Baidu.com</a>'));
        //echo  htmlentities(html_entity_decode('<a href="http://www.baidu.com">Baidu.com</a>'));
        //echo \yii\helpers\Html::encode('<a href="http://www.baidu.com">Baidu.com</a>');
        echo \yii\helpers\HtmlPurifier::process("<script>alert('Hello!');</script>hello");
    }
    
    /**
     * 缓存
     * yii
     * @see http://www.yiichina.com/doc/guide/2.0/caching-data
     */
    public function actionCache()
    {
        
    }
    /**
     * extension第三方库
     * yii
     * @see http://www.yiichina.com/doc/guide/2.0/tutorial-yii-integration
     */
    public function actionExtension()
    {
    
    }
    /**
     * 性能优化
     *  优化你的 PHP 环境
                禁用调试模式
                使用缓存技术
                开启 Schema 缓存
                合并和压缩资源文件
                优化会话存储
                优化数据库
                使用普通数组
                优化 Composer 自动加载
                处理离线数据
                性能分析
        Prepare application for scaling
     * 
     * @see http://www.yiichina.com/doc/guide/2.0/tutorial-performance-tuning
     */
    public function actionTuning()
    {
    
    }
    /**
     * 国际化：
     * @see http://www.yiichina.com/doc/guide/2.0/tutorial-i18n
     */
    public function actionI18n()
    {
        echo Yii::t('part', 'china');
        //$username = '阿福';
        echo Yii::t('part', 'name');
    }
    
    /**
     * 默认显示
     * @return  <string, string>
     */
    public function actionIndex()
    {
        //$tools = Yii::createObject('\frontend\components\Tools');
        $tools = Yii::createObject([
            'class' => '\components\Tools', //@components/Tools 这样不行 ???
            'version' => '2.0',
            'author' => 'yiichina',
        ]);
        echo Yii::getAlias('@components/Tools.php');
        echo '<br/>';
        //echo \yii\helpers\Json::encode([['id' => 1, 'name' => 'nick'], ['id' => 2, 'name' => '中国']]);
        //var_dump(dirname(dirname(__DIR__)), $tools, $tools->executeCrond());
        //$data = Yii::$app->db->createCommand('select * from user limit 1')->queryAll();
        //$query = new \yii\db\Query();
        //$data = $query->select('*')->from('user')->where('id=1')->limit(1)->createCommand()->queryAll();
        //var_dump($data);exit;
        $message = '<div>Hello World</div>';
        $model = \app\models\TestUser::findOne(['id' => 1]); //当大量查询时 用->asArray() 降低消耗时间
//         $a = ['config' => ['db' => 'mysql', 'host' => 'localhost']];
//         $b = ['config' => ['user' => 'root']];
//         $c = yii\helpers\ArrayHelper::merge($a, $b);
//         var_dump($c);

        //var_dump($model->attributes);exit;
        
        return $this->render('index', ['data' => 'tools controller', 'model' => $model, 'message' => $message]);
    }
    
    
}
