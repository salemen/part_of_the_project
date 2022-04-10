<?php
namespace app\models\user\user_params;

use Yii;
use app\helpers\AppHelper;

class Statistic
{
    public static function getStatistic($query, $attrs, $param_name, $condition = null, $is_detail, $limit = 8, $format = "d.m.Y")
    {  
        if (empty($attrs)) {
            return null;
        }
        
        $attrs[] = 'created_at';
        $data = ['labels'=>[], 'datasets'=>[['data'=>[]]]];
                
        $query->where(['user_id'=>Yii::$app->user->id]);
        
        if ($condition !== null) {
            $query->andWhere(['condition'=>$condition]);
        }
                
        if (!$is_detail) {
            $query->limit($limit);
        }       
                
        $model = $query->orderBy('created_at')->asArray()->all();
        
        if ($model) {
            self::setTimeRange($model, $data, $format);
            self::setValues($model, $param_name, $attrs, $data);
        }

        return $data;
    }

    protected static function setTimeRange($model, &$data, $format)
    {
        foreach ($model as $key=>$one) {
            $data['labels'][$key] = date($format, $one['created_at']);
        }
    }

    protected static function setValues($model, $param_name, $attrs, &$data)
    {        
        unset($attrs[array_search('created_at', $attrs)]);
        
        foreach ($attrs as $key=>$attr) {            
            foreach ($model as $key2=>$one) {                
                $data['datasets'][$key]['data'][$key2] = $one[$attr];
                $data['datasets'][$key]['data_id'][$key2] = $one['id'];                
            }
            
            $data['datasets'][$key]['borderColor'] = AppHelper::generateHex($key);
            $data['datasets'][$key]['fill'] = false;
            $data['datasets'][$key]['param_name'] = $param_name;
        }
    }
}

