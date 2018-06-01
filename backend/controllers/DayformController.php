<?php
namespace app\modules\statistic\controllers\channel;

/**
 * 渠道统计
 *
 * Created by vii
 * User: admin
 * Date Time: 2018-05-23 10:11:44
 */

use app\models\report\ReportDay;
use app\models\webadmin\Channel;
use app\models\webadmin\ChannelPackage;
use Yii;
use app\lib\AdminControllerBase;
use app\modules\statistic\models\channel\forms\Dayform;
use yii\helpers\StringHelper;
use yii\db\ActiveRecord;

class DayformController extends DayformControllerBase
{

    public function init()
    {
        parent::init();

        $this->callback = [$this, 'process'];
    }

    public function actionTest()
    {
        $query = \app\models\webadmin\Channel::find()->select('channel_id, channel_id as value, channel as label');
        $params = [
            'query' => $query,
        ];
        //die();
        Yii::$app->render->renderSearch($params, false, false, $this->callback);
    }

    public function process(& $rows)
    {
        foreach($rows as $key=>$val)
        {
            $rows[$key]['ext'] = '2223434';
        }
    }




} // --- end controller ---


