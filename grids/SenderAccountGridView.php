<?php

namespace humhub\modules\xcoin\grids;

use Yii;
use humhub\libs\Html;
use humhub\widgets\GridView;
use yii\data\ActiveDataProvider;
use humhub\modules\xcoin\helpers\TransactionHelper;
use humhub\modules\space\widgets\Image as SpaceImage;
use humhub\modules\user\widgets\Image as UserImage;
use humhub\modules\xcoin\grids\AccountColumn;
use humhub\modules\xcoin\helpers\AccountHelper;
use humhub\libs\ActionColumn;
use humhub\modules\user\models\User;

/**
 * Description of LatestTransactionsGridView
 *
 * @author Luke
 */
class SenderAccountGridView extends GridView
{

    public $contentContainer;
    public $nextRoute;
    public $requireAsset;
    public $disableAccount;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $contentContainer = $this->contentContainer;
        if ($this->contentContainer instanceof User && $contentContainer->id != Yii::$app->user->id) {
            $contentContainer = Yii::$app->user->getIdentity();
        }

        $this->dataProvider = new ActiveDataProvider([
            'query' => AccountHelper::getAccountsQuery($contentContainer),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->columns = [
            [
                'attribute' => 'id',
                'options' => [
                    'style' => 'width:50px',
                ]
            ],
            [
                'attribute' => 'space_id',
                'label' => 'Owner',
                'format' => 'raw',
                'options' => ['style' => 'width:35px'],
                'value' => function($model) {
                    if ($model->space !== null) {
                        return SpaceImage::widget(['space' => $model->space, 'width' => 26]);
                    }

                    return '-';
                }
            ],
            [
                'attribute' => 'user_id',
                'label' => 'Manager',
                'format' => 'raw',
                'options' => ['style' => 'width:35px'],
                'value' => function($model) {

                    if ($model->user === null) {
                        return '';
                    }

                    return UserImage::widget(['user' => $model->user, 'width' => 26]);
                }
            ],
            [
                'attribute' => 'title',
            ],
            [
                'label' => 'Asset(s) balance',
                'format' => 'raw',
                'value' => function($model) {
                    $list = [];
                    foreach ($model->getAssets() as $asset) {
                        $list[] = '<strong>' . $model->getAssetBalance($asset) . '</strong>&nbsp; ' .
                                SpaceImage::widget(['space' => $asset->space, 'width' => 20, 'showTooltip' => true, 'link' => true]) . '</span>';
                    }
                    return implode('&nbsp;&nbsp;&middot;&nbsp;&nbsp;', $list);
                }
            ],
            [
                'format' => 'raw',
                'options' => ['style' => 'width:55px'],
                'value' => function($model) use($contentContainer) {
                    $route = $this->nextRoute;
                    $route['accountId'] = $model->id;

                    $disabled = $model->isEmpty();
                    if ($this->disableAccount !== null && $model->id == $this->disableAccount->id) {
                        $disabled = true;
                    }

                    return Html::a('Select', $route, ['class' => 'btn btn-sm btn-success', 'data-target' => '#globalModal', 'data-ui-loader' => '', 'disabled' => $disabled]);
                }
            ],
        ];


        parent::init();
    }

}
