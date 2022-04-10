<?php

namespace app\modules\b2b\models;

use Yii;

/**
 * This is the model class for table "payments".
 *
 * @property int $id
 * @property string $invoiceId
 * @property string $customerNumber
 * @property int $orderCreatedDatetime
 * @property int $orderStatus
 * @property int $orderType
 * @property int $orderNumber
 * @property string|null $orderComment
 * @property string $orderSumAmount
 * @property string $orderSumCurrencyPaycash
 * @property string $shopSumAmount
 * @property string $paymentType
 * @property int $isTest
 * @property int|null $status1c
 * @property string|null $comment1c
 */
class Payments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoiceId', 'customerNumber', 'orderCreatedDatetime', 'orderStatus', 'orderNumber', 'orderSumAmount', 'orderSumCurrencyPaycash', 'shopSumAmount', 'paymentType'], 'required'],
            [['orderCreatedDatetime', 'orderStatus', 'orderType', 'orderNumber', 'isTest', 'status1c'], 'integer'],
            [['invoiceId', 'orderComment', 'orderSumAmount', 'orderSumCurrencyPaycash', 'shopSumAmount', 'paymentType'], 'string', 'max' => 32],
            [['customerNumber', 'comment1c'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoiceId' => 'Invoice ID',
            'customerNumber' => 'Customer Number',
            'orderCreatedDatetime' => 'Order Created Datetime',
            'orderStatus' => 'Order Status',
            'orderType' => 'Order Type',
            'orderNumber' => 'Order Number',
            'orderComment' => 'Order Comment',
            'orderSumAmount' => 'Order Sum Amount',
            'orderSumCurrencyPaycash' => 'Order Sum Currency Paycash',
            'shopSumAmount' => 'Shop Sum Amount',
            'paymentType' => 'Payment Type',
            'isTest' => 'Is Test',
            'status1c' => 'Status1c',
            'comment1c' => 'Comment1c',
        ];
    }
}
