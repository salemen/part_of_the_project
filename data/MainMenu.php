<?php
// Формирование массива элементов главного меню из БД

namespace app\data;

use Yii;
use yii\helpers\Html;
use app\helpers\AppHelper;
use app\models\CommonUser;
use app\models\menu\MenuItem;
use app\models\menu\MenuSection;

class MainMenu
{
    public $items = [];    
    public $getDynamicMenu = true;
    public $getUserMenu = true;    
    public $visibility = null;
    
    public function getItems()
    {
        if ($this->getDynamicMenu) {
            $dynamicMenu = $this->getDynamicMenu();
            if ($dynamicMenu) {
                foreach ($dynamicMenu as $dynMenu) {
                    array_push($this->items, $dynMenu);
                }
            }
        }
        
        if ($this->getUserMenu) {
            $userMenu = $this->getUserMenu();
            array_push($this->items, $userMenu);
        }
        
        return $this->items;
    }
    
    protected function getDynamicMenu()
    {
        $result = [];

        switch ($this->visibility) {
            case 'header';
                $condition = ['is_on_header'=>true];
                break;
            case 'footer':
                $condition = ['is_on_footer'=>true];
                break;
            default:
                $condition = [];
                break;
        }
        
        $sections = MenuSection::find()
            ->where(['status'=>10])
            ->andWhere($condition)
            ->all();
        
        if ($sections) {
            foreach ($sections as $section) {
                $items = MenuItem::find()
                    ->where(['section_id'=>$section->id, 'status'=>10])
                    ->andWhere($condition)
                    ->orderBy(['id'=>SORT_ASC])
                    ->all();

                if ($items) {
                    $dynamicItems = [];
                    
                    if (count($items) > 1) {                    
                        foreach ($items as $item) {
                            $class = (Yii::$app->user->isGuest) ? $item->class_guest : $item->class_default;
                            $target = ($item->is_blank) ? '_blank' : '_self';

                            $dynamicItems[] = [
                                'label'=>$item->name,
                                'linkOptions'=>['class'=>$class, 'target'=>$target],
                                'url'=>$item->url
                            ];
                        }

                        if (!$section->is_hide_all) {
                            $dynamicItems[] = ['label'=>false, 'options'=>['class'=>'divider'], 'url'=>false];
                            $dynamicItems[] = ['label'=>'Показать все', 'url'=>['/menu/index/' . $section->slug]];
                        }
                        
                        $result[] = [
                            'label'=>$section->name,
                            'items'=>$dynamicItems,
                            'linkOptions'=>['class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'],
                            'options'=>['class'=>'dropdown'],
                            'url'=>'#'
                        ];
                    } else {
                        $item = $items[0];
                        $class = (Yii::$app->user->isGuest) ? $item->class_guest : $item->class_default;
                        $target = ($item->is_blank) ? '_blank' : '_self';
                        $result[] = [
                            'label'=>$item->name,
                            'linkOptions'=>['class'=>$class, 'target'=>$target],
                            'url'=>$item->url
                        ];
                    }
                }
            }
        }
        
        return $result;
    }
    
    protected function getUserMenu()
    {
        $result = [];
        $user = Yii::$app->user;

        if ($user->isGuest) {
            $result = [
                'label'=>'Личный кабинет',
                'linkOptions'=>['class'=>'btn-modal', 'id'=>'btn-login', 'style'=>'color: green;'],
                'url'=>['/site/login']
            ];
        } else {
            $identity = $user->identity;
            $roles = $identity->roles;
            $photoPath = CommonUser::getPhoto($user->id);            
            $result = [
                'label'=>Html::img($photoPath, ['class'=>'user-image']) . AppHelper::shortFullname($identity->fullname),
                'items'=>[
                    [
                        'label'=>'<i class="fa fa-comments"></i> Мои консультации',
                        'url'=>['/consult']
                    ],
                    [
                        'label'=>'<i class="fa fa-user"></i> Личный кабинет',
                        'url'=>['/user']
                    ],
                    [
                        'label'=>false,
                        'options'=>['class'=>'divider'],
                        'url'=>false
                    ],
                    [
                        'label'=>'<i class="fa fa-user"></i> Личный кабинет ' . (($roles->is_director) ? 'руководителя' : 'куратора'),
                        'url'=>['/b2b'],
                        'visible'=>(Yii::$app->session->has('employee_santal') && ($roles->is_director || $roles->is_visor))
                    ],
                    [
                        'label'=>'<i class="fa fa-user-md"></i> Панель администратора / доктора',                        
                        'url'=>['/med'],
                        'visible'=>Yii::$app->session->has('employee_santal') && $roles->is_santal
                    ],
                    [
                        'label'=>'<i class="fa fa-cog"></i> Панель управления',
                        'visible'=>$user->can('permAdminPanel'),
                        'url'=>['/admin']
                    ],
                    [
                        'label'=>'<i class="fa fa-power-off"></i> Выход',
                        'linkOptions'=>['data-method'=>'post'],
                        'url'=>['/site/logout']
                    ] 
                ],
                'linkOptions'=>['class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'],
                'options'=>['class'=>'dropdown user user-menu'],
                'url'=>'#'
            ];
        }
        
        return $result;
    }        
}