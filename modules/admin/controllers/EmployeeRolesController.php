<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\employee\Employee;

class EmployeeRolesController extends Controller
{
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'only'=>['*'],
                'rules'=>[
                    [
                        'allow'=>true,
                        'roles'=>['admin']
                    ]
                ]
            ],
            'verbs'=>[
                'class'=>VerbFilter::className(),
                'actions'=>[
                    'toggle-permission'=>['POST'],
                    'toggle-role'=>['POST']
                ]
            ]
        ];
    }
    
    public function actionIndex()
    {
        $auth = Yii::$app->getAuthManager();
        $roles = $auth->getRoles();
        
        $employees = [];
        foreach ($roles as $role) {
            $assignments = $auth->getUserIdsByRole($role->name);
            if (!empty($assignments)) {
                foreach ($assignments as $assignment) {
                    array_push($employees, $assignment);
                }
            }
        }
        
        $dataProvider = new ActiveDataProvider([
            'query'=>Employee::find()->where(['IN', 'id', $employees])->orderBy('fullname'),
            'sort'=>false
        ]);

        return $this->render('index', [
            'dataProvider'=>$dataProvider
        ]);
    }

    public function actionCreate()
    {
        $model = new DynamicModel(['employee_id', 'roles']);
        $model->addRule(['employee_id', 'roles'], 'required');

        if ($model->load(Yii::$app->request->post())) {
            $auth = Yii::$app->getAuthManager();
            $userId = $model->employee_id;
            $roles = $model->roles;
            
            if (is_array($roles)) {
                foreach ($roles as $roleName) {
                    $role = $auth->getRole($roleName);
                    if (empty($auth->getAssignment($roleName, $userId))) {
                        $auth->assign($role, $userId);
                    }
                }
            }
            
            return $this->redirect(['index']);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }
    
    public function actionPermissions($id)
    {
        $auth = Yii::$app->getAuthManager();   
        $roles = $auth->getRolesByUser($id);
        $data = [];
        if ($roles) {
            foreach ($roles as $key=>$role) {
                $data[$key]['name'] = $role->name;
            }
        }      
        
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data,
            'pagination'=>false,
            'sort'=>false
        ]);
        
        return $this->render('permissions', [
            'auth'=>$auth,
            'dataProvider'=>$dataProvider,
            'employee_id'=>$id
        ]);
    }        
    
    public function actionTogglePermission($employee_id, $permission_name)
    {
        $name = $permission_name;
        $userId = $employee_id;
        
        $auth = Yii::$app->getAuthManager();        
        $permissionObject = $auth->getPermission($name);
        
        if (empty($auth->getAssignment($name, $userId))) {
            $auth->assign($permissionObject, $userId);
        } else {
            $auth->revoke($permissionObject, $userId);            
        }        

        return $this->redirect(['permissions', 'id'=>$employee_id]);
    }
    
    public function actionToggleRole($employee_id, $role_name)
    {
        $name = $role_name;
        $userId = $employee_id;
        
        $auth = Yii::$app->getAuthManager();        
        $role = $auth->getRole($name);
        
        if (empty($auth->getAssignment($name, $userId))) {
            $auth->assign($role, $userId);
        } else {
            $adminTryRevoke = ($name == 'admin') && (Yii::$app->user->id == $userId);
            if ($adminTryRevoke == false) {
                $userPermissions = $auth->getPermissionsByUser($userId);                
                if ($userPermissions) {
                    foreach ($userPermissions as $permission) {
                        $childrens = $auth->getChildren($permission->name);
                        if ($childrens) {
                            foreach ($childrens as $child) {
                                if ($child->name == $name) {
                                    $permissionObject = $auth->getPermission($permission->name);
                                    $auth->revoke($permissionObject, $userId);
                                }
                            }
                        }                        
                    }
                }
                
                $auth->revoke($role, $userId);
            }
        }        

        return $this->redirect(['index']);
    }

    protected function findModel($employee_id, $role_type)
    {
        if (($model = Roles::findOne(['employee_id'=>$employee_id, 'role_type'=>$role_type])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}