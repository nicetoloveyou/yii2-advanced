<?php
/**
 * Created by PhpStorm.
 * User: chrispaul
 * Date: 2018/4/19
 * Time: 12:58
 *
 * 自定义分页类: 覆盖 Pagination 原有一些方法， 主要用于为前端数据提供分页
 *
 */

namespace app\modules\sysmanage\components;

use yii;
use yii\data\Pagination;

class PPagination extends Pagination
{

    public $pageParam = 'page';

    public $pageSizeParam = 'offset';

    private $_page;

    public function getPage($recalculate = false)
    {
        $this->setPage($value = 1);
        return $this->_page;
    }

    public function setPage($value, $validatePage = false)
    {
        $this->_page = Yii::$app->request->post('page');
    }

    /**
     * @return int the offset of the data. This may be used to set the
     * OFFSET value for a SQL statement for fetching the current page of data.
     */
    public function getOffset()
    {
        return Yii::$app->request->post('_offset');
    }

    /**
     * @return int the limit of the data. This may be used to set the
     * LIMIT value for a SQL statement for fetching the current page of data.
     * Note that if the page size is infinite, a value -1 will be returned.
     */
    public function getLimit()
    {
        return Yii::$app->params['defaultPageSize'];
    }
}
