<?php

use yii\helpers\Html;
use yii\grid\GridView;
use firdows\menu\models\Menu;
use firdows\menu\models\MenuCategory;
//use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel firdows\menu\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('menu', 'ระบบจัดการเมนู');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='box box-info'>
    <div class='box-header'>
     <h3 class='box-title'><?= Html::encode($this->title) ?></h3>
    </div><!--box-header -->

    <div class='box-body pad'>

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'title',
                    'format'=>'html',
                    'value' => function($model) {
                        return Html::a($model->iconShow.' '.$model->title,['/menu/default/view','id'=>$model->id]);
                    }
                ],
                [
                    'attribute' => 'menu_category_id',
                    'filter' => MenuCategory::getList(),
                    'value' => function($model) {
                        return $model->menuCategory->title;
                    }
                ],
                [
                    'attribute' => 'router',
                    'filter' => Menu::getRouterDistinct(),                 
                ],
                [
                    'attribute' => 'parent_id',
                    'filter' => Menu::getParentDistinct(),
                    'value' => function($model) {
                        return $model->parentTitle;
                    }
                ],
                // 'parameter',
                // 'icon',
                
                [
                    'attribute' => 'status',
                    'filter' => Menu::getItemStatus(),
                    'value' => 'statusLabel',
                ],
                //'item_name',                      
                [
                    'attribute' => 'items',
                    'filter' => Menu::getItemsListDistinct(),
                    'value' => 'itemsList',
                    'headerOptions' => ['width' => '200']
                ],
                'sort',
                // 'language',
                // 'assoc',
                // 'created_at',
                // 'created_by',
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
        ?>


    </div><!--box-body pad-->
</div><!--box box-info-->
