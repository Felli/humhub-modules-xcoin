<?php

use humhub\modules\content\widgets\richtext\RichTextField;
use yii\bootstrap\Html;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;
use humhub\widgets\ActiveForm;
use humhub\modules\ui\form\widgets\DatePicker;

?>

<?php ModalDialog::begin(['header' => Yii::t('XcoinModule.base', 'Provide details'), 'closable' => false]) ?>
<?php $form = ActiveForm::begin(['id' => 'account-form']); ?>

<?= Html::hiddenInput('step', '3'); ?>

<?= $form->field($model, 'asset_id')->hiddenInput()->label(false) ?>
<?= $form->field($model, 'available_amount', ['enableError' => false])->hiddenInput()->label(false)->hint(false) ?>
<?= $form->field($model, 'exchange_rate', ['enableError' => false])->hiddenInput()->label(false) ?>
<?= $form->field($model, 'amount')->hiddenInput()->label(false) ?>


<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'title')->textInput()
                ->hint('Please enter your campaign title') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'deadline')->widget(DatePicker::class, [
                'dateFormat' => Yii::$app->params['formatter']['defaultDateFormat'],
                'clientOptions' => ['minDate' => '+1d'],
                'options' => ['class' => 'form-control', 'autocomplete' => "off"]])
                ->hint('Please enter your campaign deadline') ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'description')->textarea(['maxlength'=>255])
                ->hint('Please enter your campaign description') ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'content')->widget(RichTextField::class, ['preset' => 'full'])
                ->hint('Please enter your campaign needs & commitments') ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <?= ModalButton::submitModal(null, Yii::t('base', 'Next')); ?>
    <?= ModalButton::cancel(); ?>
</div>

<?php ActiveForm::end(); ?>
<?php ModalDialog::end() ?>

