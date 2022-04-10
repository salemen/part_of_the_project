<?php

namespace app\modules\b2b\models;

use Yii;

/**
 * This is the model class for table "consult".
 *
 * @property int $id
 * @property string $employee_id
 * @property string $patient_id
 * @property string|null $comment
 * @property int|null $dep_id
 * @property int $e_hide
 * @property int $p_hide
 * @property int|null $is_canceled
 * @property int $is_end
 * @property int|null $is_payd
 * @property int|null $is_special
 * @property int|null $created_at
 * @property int|null $ended_at
 */
class Consult extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'consult';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_id', 'patient_id'], 'required'],
            [['dep_id', 'e_hide', 'p_hide', 'is_canceled', 'is_end', 'is_payd', 'is_special', 'created_at', 'ended_at'], 'integer'],
            [['employee_id', 'patient_id', 'comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee ID',
            'patient_id' => 'Patient ID',
            'comment' => 'Comment',
            'dep_id' => 'Dep ID',
            'e_hide' => 'E Hide',
            'p_hide' => 'P Hide',
            'is_canceled' => 'Is Canceled',
            'is_end' => 'Is End',
            'is_payd' => 'Is Payd',
            'is_special' => 'Is Special',
            'created_at' => 'Created At',
            'ended_at' => 'Ended At',
        ];
    }
}
