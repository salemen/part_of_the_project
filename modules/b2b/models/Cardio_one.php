<?php

namespace app\modules\b2b\models;

use Yii;

/**
 * This is the model class for table "cardio".
 *
 * @property int $id
 * @property string|null $employee_id
 * @property string $patient_id
 * @property string $ekg_date
 * @property string|null $patient_height
 * @property string|null $patient_weight
 * @property string|null $patient_sicks
 * @property string|null $patient_drugs
 * @property string|null $patient_target
 * @property int|null $is_end
 * @property int|null $is_payd
 * @property int|null $created_at
 */
class Cardio_one extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cardio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['patient_id', 'ekg_date'], 'required'],
            [['patient_sicks', 'patient_drugs', 'patient_target'], 'string'],
            [['is_end', 'is_payd', 'created_at'], 'integer'],
            [['employee_id', 'patient_id', 'ekg_date', 'patient_height', 'patient_weight'], 'string', 'max' => 255],
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
            'ekg_date' => 'Ekg Date',
            'patient_height' => 'Patient Height',
            'patient_weight' => 'Patient Weight',
            'patient_sicks' => 'Patient Sicks',
            'patient_drugs' => 'Patient Drugs',
            'patient_target' => 'Patient Target',
            'is_end' => 'Is End',
            'is_payd' => 'Is Payd',
            'created_at' => 'Created At',
        ];
    }
}
