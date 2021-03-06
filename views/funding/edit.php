<?php

use yii\bootstrap\Html;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;
use humhub\widgets\ActiveForm;
use humhub\assets\Select2BootstrapAsset;
use humhub\modules\xcoin\widgets\AmountField;

Select2BootstrapAsset::register($this);
?>

<?php ModalDialog::begin(['header' => Yii::t('XcoinModule.base', 'Define exchange rate and maximum'), 'closable' => false]) ?>
<?php $form = ActiveForm::begin(['id' => 'account-form']); ?>

<div class="modal-body">
    <?= Html::hiddenInput('step', '2'); ?>
    <?= $form->field($model, 'asset_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'available_amount')->widget(AmountField::class, ['asset' => $myAsset])->label(Yii::t('XcoinModule.base', 'Maximum offered amount')); ?>
    <p class='alert alert-info'>
        The current balance of the funding account is: <strong><?= $fundingAccountBalance; ?></strong>
    </p>
    <hr/>
    <p><?= Yii::t('XcoinModule.base', 'Determine the exchange rate for which you are willing to trade assets.'); ?></p>
    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'exchange_rate')->widget(AmountField::class, ['asset' => $myAsset])->label(Yii::t('XcoinModule.base', 'Provided asset')); ?>
        </div>
        <div class="col-md-2 text-center">
            <i class="fa fa-exchange colorSuccess" style="font-size:28px;padding-top:24px" aria-hidden="true"></i>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'amount')->widget(AmountField::class, ['asset' => $model->asset, 'readonly' => true])->label(Yii::t('XcoinModule.base', 'Requested asset')); ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <?= ModalButton::submitModal(null, Yii::t('base', 'Next')); ?>
    <?= ModalButton::cancel(); ?>
</div>

<?php ActiveForm::end(); ?>
<?php ModalDialog::end() ?>

