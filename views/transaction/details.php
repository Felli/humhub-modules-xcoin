<?php

use yii\bootstrap\Html;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;
?>
<?php ModalDialog::begin(['header' => '<strong>Transfer</strong> details', 'closable' => true]) ?>
<div class="modal-body">

    <table class="table table-condensed">
        <tr>
            <td>Transaction ID</td>
            <td><?= $transaction->id; ?></td>
        </tr>
        <tr>
            <td>Date</td>
            <td><?= $transaction->created_at; ?></td>
        </tr>
        <tr>
            <td>Amount</td>
            <td><?= $transaction->amount; ?></td>
        </tr>
        <tr>
            <td>Comment</td>
            <td><?= Html::encode($transaction->comment); ?></td>
        </tr>
        <tr>
            <td>Sender account</td>
            <td><?= Html::encode($transaction->from_account_id); ?></td>
        </tr>
        <tr>
            <td>Target account</td>
            <td><?= Html::encode($transaction->to_account_id); ?></td>
        </tr>
    </table>    
    
</div>

<div class="modal-footer">
    <?= ModalButton::cancel('Close'); ?>
</div>
<?php ModalDialog::end() ?>
