<?php

namespace app\modules\covid\models;

use Yii;

/**
 * This is the model class for table "covid_diary".
 *
 * @property int $id
 * @property string $usl_id
 * @property string $user_id
 * @property string $vac_org_1
 * @property string $vac_name_1
 * @property string $vac_date_1
 * @property string $vac_org_2
 * @property string $vac_name_2
 * @property string $vac_date_2
 */
class CovidDiary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'covid_diary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usl_id', 'user_id', 'vac_org_1', 'vac_name_1', 'vac_date_1'], 'required'],
            [['usl_id', 'user_id', 'vac_org_1', 'vac_name_1', 'vac_date_1', 'vac_org_2', 'vac_name_2', 'vac_date_2'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usl_id' => 'Usl ID',
            'user_id' => 'User ID',
            'vac_org_1' => 'Vac Org 1',
            'vac_name_1' => 'Vac Name 1',
            'vac_date_1' => 'Vac Date 1',
            'vac_org_2' => 'Vac Org 2',
            'vac_name_2' => 'Vac Name 2',
            'vac_date_2' => 'Vac Date 2',
        ];
    }
}
