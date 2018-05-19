<?php
/**
 * Created by PhpStorm.
 * User: chrispaul
 * Date: 2018/4/11
 * Time: 20:00
 */

namespace app\components;

use yii;
use yii\base\Component;
use yii\helpers\StringHelper;
use yii\base\Exception;

/**
 * 渲染组件
 *
 * Class RenderComponent
 * @package app\components
 */

class RenderComponent extends Component
{

    protected $db;

    protected $gameDb;

    public $limit = 0;

    public function init()
    {
        parent::init();

        $this->db = Yii::$app->db;;
        $this->gameDb = Yii::$app->gameDb;
    }

    /**
     * 渲染 Load: select 注意字段大小写
     *
     * @param array $queryParams
     */
    public function renderLoad(array $queryParams)
    {
        $model = ($queryParams['className'] ?? ($queryParams['model'] ?? ''));
        if ($model == '') $this->returnOK();
        $param = Yii::$app->request->post(($queryParams['formName'] ?? 'form'));

        $row = [];
        if (!empty($param)) {
            list($key, $value) = each($param);
            // enable bind params to void sql inject , should set where like below
            // where：condition = 'id=:id',  bindParam = [':id' => $id ]
            $select = ($queryParams['select']) ?? '*';
            $row = $model::find()->select($select)->where("{$key} = :{$key}", [":{$key}" => $value])->asArray()->one();
        }

        $this->returnOK([
            // 'column' => $model::attributeLabels(),
            'row' => $row
        ]);
    }

    /**
     * 渲染 Save
     *
     * @param array $queryParams
     * @throws Exception
     */
    public function renderSave(array $queryParams)
    {
        $param = Yii::$app->request->post($queryParams['formName']);

        if (!empty($param)) {
            $pk = $queryParams['className']::primaryKey();
            if ($pk != null && is_array($pk)) {
                $pk_id = $pk[0];
                // if set primary key and value then to update model.
                if (!empty($param[$pk_id]) && !empty($param[$pk_id])) {
                    $pk_value = $param[$pk_id];
                    $model = $queryParams['className']::find()->where("{$pk_id} = :{$pk_id}", [":{$pk_id}" => $pk_value])->one();
                } // or add new record .
                else {
                    $model = new $queryParams['className'] ();
                }
                // save model .
                if ($model->load($param, '') && $model->validate()) {
                    if ( !$model->save()) {
                        throw new Exception('save model failed !');
                    }
                } else {
                    throw new Exception(json_encode($model->getFirstErrors()));
                }
            }
        }
        else {
            throw new Exception("{$queryParams['formName']} params is empty !");
        }
        $data = [];
        $this->returnOK($data);
    }

    /**
     * 删除模型
     *
     * @param $pk           int or array
     * @param $className    model class
     */
    public function renderDelete($pk, $className)
    {
        $pk_value = Yii::$app->request->post($pk);
        $pk_value = (array) $pk_value;
        if (!empty($pk_value))
        {
            foreach ($pk_value as $id){
                $id = (int) $id;
                $model = $className::findOne([$pk => $id]);
                if ($model) $model->delete();
            }
        }

        $this->returnOK();
    }


    /**
     * 渲染 Search
     *
     * $params = [
     *  [
     *   'formName' => 'searchForm',
     *   'db' => 'gameDb',
     *   'query' => $query,
     *   'orderBy' => 'orderBy',
     *   'parseFormWhere' => false  // if set be false will not parse Form Where condition
     *   ],
     *   ......
     *  ];
     *
     * @param array $params
     * @param bool $page
     * @param bool $return if return is true
     * @return array
     * @throws Exception
     */
    public function renderSearch(array $params, $page = false, $return = false)
    {
        $queryParams = $data = [];
        // if is single one array
        if(array_key_exists('query', $params)) {
            $queryParams[] = $params;
        }
        // multi array
        else {
            $queryParams = $params;
        }
        // each query
        foreach($queryParams as $key => $qparam)
        {
            $db = $qparam['db'] ?? $this->db;
            $query = $qparam['query'];
            $formName = $qparam['formName'] ?? null;
            $request = Yii::$app->request->post($formName);
            $pagination = ['total' => 0];
            $searchForm = [];
            // validate formModel
            $formModel = $qparam['formModel'] ?? '';
            if ($formModel && $request) $this->validateFormModel($formModel, $request);
            if (!empty($request['form'])) {
                $searchForm = $request['form'];
            }
            else {
                if (!empty($request) && is_array($request)) $searchForm = $request ?? [];
            }
            // parse form where
            $parseFormWhere = isset($qparam['parseFormWhere']) ? $qparam['parseFormWhere'] : true;
            if ($parseFormWhere !== false) {
                $where = $this->parseWhere($searchForm, $query);
                if (!empty($where)) {
                    $query->andWhere($where['condition'], $where['params']);
                }
            }
            // limit
            $total = (int) $query->count(0);
            $this->parseLimit($request);
            // if set page
            if ($page === true) {
                $offset = (int) ($searchForm['_offset'] ?? 0);
                $query->offset($offset);
                $pagination = ['total' => $total, 'offset' => $offset, 'limit' => $this->limit];
            }
            $query->limit($this->limit);
            // if set order by
            $orderBy = (!empty($qparam['orderBy']) ? $qparam['orderBy'] : ($request['orderBy'] ?? ''));
            if ($orderBy) $query->orderBy($orderBy);
            // if active model convert as Array
            if ($query instanceof yii\db\ActiveQuery) $query->asArray();
            // search all results
            $rows = $query->all($db);
            // data array
            $data['table' . (int) ($key + 1)] = [
                'rows' => $rows,
                'total' => $total,
                'pagination' => $pagination,
            ];
            // todo : export table data
        }

        if ($return) return $data;
        // render data to client
        $this->returnOK($data);
    }

    /**
     * 验证表单模型提交数据
     *
     * @param $formModel
     * @param $params
     * @return string
     * @throws Exception
     */
    protected function validateFormModel($formModel, $params)
    {
        if ($formModel == '' || $params == '') return '';
        $model = new $formModel;
        // model validate
        if (!empty($params)) {
            if ($model->load($params, '') && $model->validate()) {

            } else {
                throw new Exception(json_encode($model->getFirstErrors()));
            }
        }
    }

    /**
     * 生成where条件 : condition(条件) 和 params(参数绑定) : condition = 'a=:a and b=:b' , params = [':a' => 1, ':b' => 2]
     *
     * 提交参数数组里面的 key => value：
     *
     * key 有几种情形：
     *  a. key
     *  b. key__min 最小,  key__timestamp__min 字段使用时间戳比较
     *  c. key__max 最大,  key__timestamp__max 字段使用时间戳比较
     *  d. key_value // drop down list select value
     *
     * value 有几种情形：
     *  a. *something*
     *  b. ['in', ['1', '2']]
     *
     * @param $searchForm       表单提交的参数数组
     * @param $query  yii\db\Query
     * @return array|string
     */
    protected function parseWhere($searchForm, $query)
    {
        if (empty($searchForm)) return '';
        $where = [];
        foreach($searchForm as $key=>$value)
        {
            if (empty($value)) continue;
            if (in_array($key, ['_offset', 'page', 'limit', 'orderBy', 'groupBy'])) continue;
            if (!is_array($value)) {
                $value = trim($value);
                // if value is all: continue
                if (strtolower($value) === 'all') continue;
                // like condition
                $start_like = StringHelper::startsWith($value, '*');
                $end_like = StringHelper::endsWith($value, '*');
                // min or max condition
                $start_min = StringHelper::endsWith($key, '__min');
                $end_max = StringHelper::endsWith($key, '__max');
                // end with _value: is drop list select value
                $end_value = StringHelper::endsWith($key, '_value');

                // like
                if ($start_like === true && $end_like === true) {
                    $where['condition'][$key] = " {$key} like :{$key} ";
                    $where['params'][":{$key}"] = "%" . trim($value, '*') . "%";
                }
                else if ($start_like === true) {
                    $where['condition'][$key] = " {$key} like :{$key} ";
                    $where['params'][":{$key}"] = "%" . trim($value, '*');
                }
                else if ($end_like === true) {
                    $where['condition'][$key] = " {$key} like :{$key} ";
                    $where['params'][":{$key}"] = rtrim($value, '*') . "%";
                }
                // min or max
                else if ($start_min === true){
                    $real_key = str_replace('__min', '', $key); // do not use rtrim
                    // if need datetime convert to timestamp
                    if (StringHelper::endsWith($real_key, '__timestamp')) {
                        $real_key = str_replace('__timestamp', '', $real_key);
                        $where['condition'][$key] = " {$real_key} >= :{$key} ";
                        $where['params'][":{$key}"] = strtotime($value);
                    }
                    else {
                        $where['condition'][$key] = " {$real_key} >= :{$key} ";
                        $where['params'][":{$key}"] = $value;
                    }
                }
                else if ($end_max === true){
                    $real_key = str_replace('__max', '', $key);
                    if (StringHelper::endsWith($real_key, '__timestamp')) {
                        $real_key = str_replace('__timestamp', '', $real_key);
                        $where['condition'][$key] = " {$real_key} <= :{$key} ";
                        // todo
                        $where['params'][":{$key}"] = strtotime($value . ' 23:59:59');
                    }
                    else {
                        $where['condition'][$key] = " {$real_key} <= :{$key} ";
                        $where['params'][":{$key}"] = $value;
                    }
                }
                // drop down list select value
                else if ($end_value === true){
                    // limit: assign its value in vii config file stands for top N
                    if ($key === 'limit_value') {
                        $this->limit = (int) $value;
                    }
                    else {
                        $real_key = str_replace('_value', '', $key);
                        // less than 0
                        if ($value === 'less0') {
                            $where['condition'][$real_key] = " {$real_key} < 0 ";
                        }
                        // large than 0
                        else if ($value === 'large0') {
                            $where['condition'][$real_key] = " {$real_key} > 0 ";
                        }
                        else {
                            $where['condition'][$key] = " {$real_key} = :{$key} ";
                            $where['params'][":{$key}"] = $value;
                        }
                    }
                }
                // equal condition
                else {
                    $where['condition'][$key] = " {$key} = :{$key} ";
                    $where['params'][":{$key}"] = $value;
                }
            }
            // range condition
            else if (is_array($value) && count($value) === 2) {
                $operator = $value[0] ?? '';
                $range = $value[1] ?? '';
                if ($operator == '' || $range == '') continue;
                if (! in_array($operator, self::$allowOperator)) continue;
                $string = str_replace(' ', '', implode(',', $range));
                $where['condition'][$key] = " {$key} {$operator} ({$string}) ";
                $where['params'][":{$key}"] = implode(', ', $range);
            }
        }
        if (!empty($where['condition'])) {
            // var_dump($where); die('where1');
            $where = $this->filterWhere($where, $query);
            $where['condition'] = implode(" And ", $where['condition'] );
        }
        //var_dump($where); die('where2');
        return $where;
    }


    public static $endsReplace = [
        '__min', '__max',
        '__timestamp',
        '_value',
    ];

    /**
     * filter where condition and bound params
     *
     * @param array $where
     * @param $query
     * @return array
     */
    protected function filterWhere(array $where, $query)
    {
        $modelClass = $query->modelClass ?? '';
        if ($modelClass != '') {
            $model = new $modelClass;
            foreach ($where['condition'] as $key=>$val)
            {
                // hasProperty: case sensitive
                if (! $model->hasProperty(str_replace(static::$endsReplace, '', $key))) {
                    unset($where['condition'][$key]);
                    unset($where['params'][":{$key}"]);
                }
            }
        }

        return $where;
    }

    /**
     * 解析 limit
     *
     * @param array $request
     */
    protected function parseLimit($request = [])
    {
        $this->limit = ($this->limit !=0) ? $this->limit : ($request['limit'] ?? Yii::$app->params['defaultPageSize']);
        $this->limit = ($this->limit > Yii::$app->params['maxPageSize']) ? Yii::$app->params['maxPageSize'] : $this->limit;
    }

    /**
     * 日期格式转换成时间戳: 正则匹配若为日期则转换
     *
     * @param $value
     * @return false|int
     */
    public static function toTimeStamp($value)
    {
        // $patten = "/^\d{4}\-([1-9]|1[012])\-([1-9]|[12][0-9]|3[01])\s+([0-9]|1[0-9]|2[0-3])\:(0?[0-9]|[1-5][0-9])(\:(0?[0-9]|[1-5][0-9]))?$/";  // 2018-10-10 12:10
        $patten = "/^\d{4}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01])(\s+(0?[0-9]|1[0-9]|2[0-3])\:(0?[0-9]|[1-5][0-9])\:(0?[0-9]|[1-5][0-9]))?$/";
        if (preg_match($patten, $value, $out)) {
            return strtotime($out[0]);
        }

        return $value;
    }
    public static $allowOperator = [
        'in',
        'like',
        '='
    ];
    public static $sqlOperator = [
        'delete',
        'update',
        'drop',
        'alter',
        'add'
    ];

    /**
     * 查询 KindID 对应游戏名称： 附加在 sql select 后
     *
     * @return string
     */
    public function queryKindField()
    {
        $kind = Yii::$app->params['kind'];
        if (!$kind) return '';
        $queryString = " , ( Case ";
        foreach ($kind as $key=>$val)
        {
            $queryString .= " When KindID={$key} Then '$val'  ";
        }
        $queryString .= " END ) As kind ";

        return $queryString;
    }

    /**
     * 查询 C_Type 对应金币变化事件名称： 附加在 sql select 后
     *
     * @return string
     */
    public function queryGoldChangeTypeField()
    {
        $kind = Yii::$app->params['goldChangeType'];
        if (!$kind) return '';
        $queryString = " , ( Case ";
        foreach ($kind as $key=>$val)
        {
            $queryString .= " When C_Type={$key} Then '$val'  ";
        }
        $queryString .= " END ) As changeType ";

        return $queryString;
    }

    public function queryCirculateTypeField()
    {
        $kind = Yii::$app->params['circulateType'];
        if (!$kind) return '';
        $queryString = " , ( Case ";
        foreach ($kind as $key=>$val)
        {
            $queryString .= " When circulate_type={$key} Then '$val'  ";
        }
        $queryString .= " END ) As circulate_type ";

        return $queryString;
    }

    /**
     * 查询 根据金币变化判断输赢： 附加在 sql select 后
     *
     * @return string
     */
    public function queryWinloseTypeField()
    {
        $queryString = " , ( Case 
                When ChangeGold > 0 Then '赢'
                When ChangeGold < 0 Then '输'
                When ChangeGold = 0 Then '平局'
            Else ''
        End) As winlose_type ";

        return $queryString;
    }

    /**
     * 返回配置文件定义字段值对应中文意义： 附加在 select 后
     *
     * @param $field    表字段
     * @return string   case when then sql
     */
    public function queryMeaningField($field)
    {
        $define = (Yii::$app->params[$field]) ?? [];
        if (!$define) return '';
        $queryString = " , ( Case ";
        foreach ($define as $key=>$val)
        {
            $queryString .= " When {$field}={$key} Then '$val'  ";
        }
        $queryString .= " END ) As $field ";

        return $queryString;
    }

    // ----------------------------------------------------------------------------------------------------------------

    public function returnOK($data = null)
    {
        if (!is_array($data)) {
            $data = [];
        }
        $data['ok'] = true;

        $response = Yii::$app->response;
        $response->data = $data;
        $response->send();
    }

}
