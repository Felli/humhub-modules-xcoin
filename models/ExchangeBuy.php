<?php

namespace humhub\modules\xcoin\models;

use Yii;
use yii\base\Model;
use yii\web\HttpException;

class ExchangeBuy extends Model
{

    /**
     * @var Account
     */
    public $fromAccount;

    /**
     * @var Exchange
     */
    public $exchange;

    /**
     * @var int
     */
    public $amountBuy;

    /**
     * @var int
     */
    public $amountPay;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amountBuy'], 'required'],
            ['amountBuy', 'number', 'min' => 0.001, 'max' => $this->getMaxBuyAmount()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'amountBuy' => 'Receive',
            'amountPay' => 'Pay',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'amountBuy' => 'Maximum: ' . $this->getMaxBuyAmount(),
            'amountPay' => 'Maximum: '. $this->getMaxPayAmount(),
        ];
    }

    /**
     * @return int max PA
     */
    protected function getMaxBuyAmount() {

        $available = $this->exchange->getAvailableAmountValidated();

        // Balance PE account
        $balance = $this->fromAccount->getAssetBalance($this->exchange->wantedAsset);

        // Balance PE account in PA
        $balanceConverted = $balance / $this->exchange->exchange_rate;

        if ($balanceConverted < $available) {
            return $balanceConverted;
        }

        return $available;

    }

    /**
     * @return int max PE
     */
    protected function getMaxPayAmount()
    {
        // in PA
        $available = $this->exchange->getAvailableAmountValidated();

        // in PE
        $convertedAmount = $available * $this->exchange->exchange_rate;

        // Balance PE account
        $balance = $this->fromAccount->getAssetBalance($this->exchange->wantedAsset);

        if ($balance < $convertedAmount) {
            return $balance;
        }

        return $convertedAmount;
    }

    public function getBuyAmount() {
        return $this->amountBuy;
    }

    public function getBuyAsset() {
        return $this->exchange->asset;
    }

    public function getPayAmount() {
        return $this->amountBuy * $this->exchange->exchange_rate;
    }

    public function getPayAsset() {
        return $this->exchange->wantedAsset;
    }

    protected function getMinPayAmount()
    {
        return $this->exchange->minimum_amount;
    }


    /**
     * @return bool
     * @throws HttpException
     */
    public function buy()
    {
        if (!$this->validate()) {
            return false;
        }

        // Buy
        $transaction = new Transaction();
        $transaction->transaction_type = Transaction::TRANSACTION_TYPE_TRANSFER;
        $transaction->asset_id = $this->getBuyAsset()->id;
        $transaction->to_account_id = $this->fromAccount->id;
        $transaction->from_account_id = $this->exchange->account_id;
        $transaction->amount = $this->getBuyAmount();
        $transaction->comment = 'Asset Exchange';
        if (!$transaction->save()) {
            Yii::error('Buy transaction failed: ' . print_r($transaction->getErrors(), 1). ' amount: '. $this->getBuyAmount(). ' asset Id'. $this->getBuyAsset()->id. ' from acc: '. $this->exchange->account_id, 'xcoin.exchange');
            throw new HttpException('Transaction failed!');
        }

        // Pay
        $transaction = new Transaction();
        $transaction->transaction_type = Transaction::TRANSACTION_TYPE_TRANSFER;
        $transaction->asset_id = $this->getPayAsset()->id;
        $transaction->to_account_id = $this->exchange->account_id;
        $transaction->from_account_id = $this->fromAccount->id;
        $transaction->amount = $this->getPayAmount();
        $transaction->comment = 'Asset Exchange';
        if (!$transaction->save()) {
            Yii::error('Pay transaction failed: ' . print_r($transaction->getErrors(), 1). ' amount: '. $this->getPayAmount(). ' asset Id'. $this->getPayAsset()->id. ' from acc: '. $this->fromAccount->id, 'xcoin.exchange');
            throw new HttpException('Transaction failed!');
        }

        $newAvailable = $this->exchange->available_amount - $this->getBuyAmount();
        $this->exchange->updateAttributes(['available_amount' => $newAvailable]);

        return true;
    }

}
