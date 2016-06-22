<?php

namespace firdows\menu\models;

//use yii\base\Model;
use Yii;
use yii\helpers\Html;
use backend\modules\menu\models\Menu;

/**
 * Description of navigate
 *
 * @author madone
 */
class Navigate extends \yii\base\Model {

    //put your code here
    public function menu($category_id) {
        $model = Menu::find()
                ->where([
                    'menu_category_id' => $category_id,
                    'status' => '1',
                    'parent_id' => null
                ])
                ->orderBy(['sort' => SORT_ASC])
                ->all();

        return $this->genArray($model);
    }

    public function parentMenu($id) {
        $model = Menu::find()
                ->where([
                    'parent_id' => $id,
                    'status' => '1'
                ])
                ->orderBy(['sort' => SORT_ASC])
                ->all();
        return $model ? $this->genArray($model) : null;
    }

    private function genArray($model) {
        //$ob='backend\modules\seller\models\RegisterSeller::getCoutRegis();';
        //$run ='getCoutRegis()';
        $menu = [];
        foreach ($model as $val) {
            $items = ($val->id) ? $this->parentMenu($val->id) : null;
            //$params = $val['params']?Json::encode($val['params']):null;
            //print_r($params);
            //$labelParam = $params?(eval('return '.$params['label'])):null;
            
            $visible = false;
            $menuAuths = $val->menuAuths; 
            foreach($menuAuths as $item){
                if($visible = Yii::$app->user->can($item->item_name)){
                    break;
                }
            }
            
            
            $menu[] = [
                'label' => $val->title . $this->getCount($val->router),
                'encode' => false,
                'icon' => $val->icon,
                'url' => [$val->router],
                'visible' => $visible,
                //$visible,
                'items' => $items
            ];
        }
        //print_r($menu);
        return $menu;
    }

    private function getCount($router) {
        $count = '';
        switch ($router) {
            /*
             * Seller
             */
            case '/seller':
                $count = \backend\modules\seller\models\RegisterSeller::find()
                        ->where(['IN', 'status', ['', '0']])
                        ->orWhere(['IS', 'status', NULL])
                        ->count();
                //echo $countRegisSeller;
                $count = $count ? ' <small class="label bg-yellow">' . $count . '</small>' : '';
                break;

            case '/seller/default/index':
                $count = \backend\modules\seller\models\Seller::find()
                        //->where(['IN', 'status', ['', '0']])
                        //->orWhere(['IS', 'status', NULL])
                        ->count();
                $count = Html::tag('b', ' (' . $count . ')');
                break;

            case '/seller/register/index':
                $count = \backend\modules\seller\models\RegisterSeller::find()
                        ->where(['!=', 'status', 0])
                        //->orWhere(['IS', 'status', NULL])
                        ->count();
                $count = Html::tag('b', ' (' . $count . ')');
                break;

            case '/seller/register/draft':
                $count = \backend\modules\seller\models\RegisterSeller::find()
                        ->where(['IN', 'status', [0]])
                        //->orWhere(['IS', 'status', NULL])
                        ->count();
                $count = Html::tag('b', ' (' . $count . ')', ['class' => 'text-red']);
                break;

            /**
             * Customer
             */
            case '/customer':
                $model = \backend\modules\customer\models\RegisterCustomer::find();
                if (Yii::$app->user->can('seller')) {
                    $count = $model->where(['IN', 'status', [1, 2]])->andWhere(['seller_id' => Yii::$app->user->id])->count();
                } elseif (Yii::$app->user->can('staff')) {
                    $count = $model->where(['IN', 'status', [1, 2]])->count();
                }
                $count = $count ? Html::tag('small', $count, ['class' => 'label pull-right  bg-yellow']) : '';


                break;

            case '/customer/default/index':
                $model = \backend\modules\customer\models\Customer::find()
                        ->where(['!=', 'status', 0]);

                if (Yii::$app->user->can('seller')) {
                    $count = $model->andWhere(['seller_id' => Yii::$app->user->id])->count();
                } elseif (Yii::$app->user->can('staff')) {
                    $count = $model->count();
                }
                $count = $count ? Html::tag('b', ' (' . $count . ')', ['class' => '']) : '';
                break;

            /*
             * Customer/Register
             */
            case '/customer/register/index':
                $model = \backend\modules\customer\models\RegisterCustomer::find()
                        ->where(['!=', 'status', 0]);

                if (Yii::$app->user->can('seller')) {
                    $count = $model->andWhere(['seller_id' => Yii::$app->user->id])->count();
                } elseif (Yii::$app->user->can('staff')) {
                    $count = $model->count();
                }
                $count = $count ? Html::tag('b', ' (' . $count . ')', ['class' => 'text-muted']) : '';
                break;

            case '/customer/register/draft':
                $count = \backend\modules\customer\models\RegisterCustomer::find()
                        ->where(['IN', 'status', [0]])
                        ->andWhere(['seller_id' => Yii::$app->user->id])
                        ->count();
                $count = $count ? Html::tag('b', ' (' . $count . ')', ['class' => 'text-red']) : '';
                break;

            case '/customer/register/offer':
                $count = \backend\modules\customer\models\RegisterCustomer::find()
                                ->where(['IN', 'status', [1]])->andWhere(['seller_id' => Yii::$app->user->id])->count();
                $count = $count ? Html::tag('b', ' (' . $count . ')', ['class' => 'text-muted']) : '';
                break;

            case '/customer/register/consider':
                $model = \backend\modules\customer\models\RegisterCustomer::find();

                if (Yii::$app->user->can('seller')) {
                    $count = $model->where(['IN', 'status', [2]])->andWhere(['seller_id' => Yii::$app->user->id])->count();
                    $count = $count ? Html::tag('b', ' (' . $count . ')', ['class' => '']) : '';
                } elseif (Yii::$app->user->can('staff')) {
                    $count = $model->where(['IN', 'status', [1, 2]])->count();
                    $count = $count ? Html::tag('small', $count, ['class' => 'label pull-right  bg-yellow']) : '';
                }


                break;

            case '/customer/register/result':
                $model = \backend\modules\customer\models\RegisterCustomer::find()
                        ->where(['IN', 'status', [3, 4, 5]]);

                if (Yii::$app->user->can('seller')) {
                    $count = $model->andWhere(['seller_id' => Yii::$app->user->id])->count();
                } elseif (Yii::$app->user->can('staff')) {
                    $count = $model->count();
                }

                $count = $count ? Html::tag('b', ' (' . $count . ')', ['class' => '']) : '';
                break;


            /**
             * Credit
             */
            case '/credit/draft':
                $count = \backend\modules\credit\models\Credit::find()
                        ->where(['IN', 'status', ['', '0']])
                        ->andWhere(['seller_id' => Yii::$app->user->id])
                        ->count();
                //echo $countRegisSeller;
                $count = $count ? '<small class="label pull-right bg-red">' . $count . '</small>' : '';
                break;

            case '/credit/default/result':
                $count = \backend\modules\credit\models\Credit::find()
                        ->where(['IN', 'status', [3, 4, 5]])
                        ->andWhere(['seller_id' => Yii::$app->user->id])
                        ->count();
                //echo $countRegisSeller;
                $count = $count ? '<small class="label pull-right bg-yellow">' . $count . '</small>' : '';
                break;

            /**
             * Contract
             */
            case '/contract/default/index':
                $model = \backend\modules\contract\models\Contract::find()
                        ->joinWith('credit');
                if (Yii::$app->user->can('seller')) {
                    $count = $model->where(['credit.seller_id' => Yii::$app->user->id])->count();
                } elseif (Yii::$app->user->can('staff')) {
                    $count = $model->count();
                }

                $count = Html::tag('b', ' (' . $count . ')', ['class' => 'text-muted']);
                break;

            /**
             * Contract/Credit
             */
            case '/contract':
                if (Yii::$app->user->can('seller')) {
                   $count = \backend\modules\contract\models\Credit::find()->where(['status' => [1, 2]])->count();
                } elseif (Yii::$app->user->can('staff')) {
                    $count = \backend\modules\contract\models\Credit::find()->where(['status' => [1, 2]])->count();
                }
                $count = $count ? Html::tag('small', $count, ['class' => 'label pull-right  bg-yellow']) : '';
                break;

            case '/contract/credit/index':
                $model = \backend\modules\contract\models\Credit::find()->where(['!=', 'status', 0]);

                if (Yii::$app->user->can('seller')) {
                    $count = $model->andWhere(['seller_id' => Yii::$app->user->id])->count();
                } elseif (Yii::$app->user->can('staff')) {
                    $count = $model->count();
                }

                $count = $count ? Html::tag('b', ' (' . $count . ')', ['class' => 'text-muted']) : '';
                break;

            case '/contract/credit/draft':
                $count = \backend\modules\contract\models\Credit::find()->where(['status' => 0, 'seller_id' => Yii::$app->user->id])->count();

                $count = $count ? Html::tag('b', ' (' . $count . ')', ['class' => 'text-red']) : '';
                break;

            case '/contract/credit/offer':
                $count = \backend\modules\contract\models\Credit::find()->where(['status' => 1, 'seller_id' => Yii::$app->user->id])->count();

                $count = $count ? Html::tag('b', ' (' . $count . ')', ['class' => 'text-primary']) : '';
                break;


            case '/contract/credit/consider':
                if (Yii::$app->user->can('staff')) {
                    $count = \backend\modules\contract\models\Credit::find()->where(['status' => [1, 2]])->count();
                    $count = $count ? Html::tag('small', $count, ['class' => 'label pull-right  bg-yellow']) : '';
                }
                break;

            case '/contract/credit/result':
                $model = \backend\modules\contract\models\Credit::find()->where(['status' => [3, 4, 5]]);
                if (Yii::$app->user->can('seller')) {
                    $count = $model->andWhere(['seller_id' => Yii::$app->user->id])->count();
                } elseif (Yii::$app->user->can('staff')) {
                    $count = $model->count();
                }

                $count = Html::tag('b', ' (' . $count . ')', ['class' => 'text-muted']);
                break;
        }
        return $count;
    }

}
