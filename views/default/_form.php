<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use firdows\menu\models\Menu;
use firdows\menu\models\MenuCategory;
use kartik\widgets\Select2;
/* @var $this yii\web\View */
/* @var $model firdows\menu\models\Menu */
/* @var $form yii\widgets\ActiveForm */
use firdows\menu\assets\AppAsset;

$asset = AppAsset::register($this);
?>



<?php $form = ActiveForm::begin(); ?>

<div class="row">   
    <div class="col-sm-2">
        <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>
    </div>   
    <div class="col-sm-10">   

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<div class="row">   
    <div class="col-sm-2">  
        <?= $form->field($model, 'target')->textInput(['maxlength' => true]) ?>
    </div>   
    <div class="col-sm-6">  
        <?= $form->field($model, 'router')->textInput(['maxlength' => true]) ?>
    </div>   
    <div class="col-sm-4">
        <?= $form->field($model, 'parameter')->textInput(['maxlength' => true]) ?>
    </div> 
</div> 


<div class="row">   
    <div class="col-sm-6">
        <?= $form->field($model, 'menu_category_id')->dropDownList(MenuCategory::getList(), ['prompt' => Yii::t('app', 'เลือก')]) ?>
    </div>   
    <div class="col-sm-6">  
        <?= $form->field($model, 'parent_id')->dropDownList(Menu::getList(), ['prompt' => Yii::t('app', 'เลือก')]) ?>
    </div> 
</div> 


<div class="row">   
    <div class="col-sm-3">
        <?= $form->field($model, 'status')->dropDownList(Menu::getItemStatus(), ['prompt' => Yii::t('app', 'เลือก')]) ?>
    </div>   

    <div class="col-sm-3">  
        <?=
        $form->field($model, 'items')->widget(Select2::ClassName(), [
            'data' => Menu::getAuth(),
            'options' => [
                'placeholder' => 'Select a color ...',
                'multiple' => true
            ],
            'pluginOptions' => [
                //'allowClear' => true,
                'tags' => true,
                //'tokenSeparators' => [',', ' '],
                'maximumInputLength' => 10
            ],
        ])
        ?>
    </div>   
    <div class="col-sm-3">  
        <?= $form->field($model, 'protocol')->textInput(['maxlength' => true]) ?>
    </div>   
    <div class="col-sm-3">  
        <?= $form->field($model, 'home')->dropDownList([ 1 => '1', 0 => '0',], ['prompt' => '']) ?>
    </div>   
</div>   



<div class="row">   
    <div class="col-sm-3">

        <?php /* = $form->field($model, 'sort')->dropDownList(Menu::getSortBy($model->menu_category_id, $model->parent_id), ['prompt' => Yii::t('app', 'เลือก')]) */ ?>
        <?= $form->field($model, 'sort')->textInput() ?>
    </div>   
    <div class="col-sm-3">  

        <?php /* = $form->field($model, 'language')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'assoc')->textInput(['maxlength' => true]) */ ?>

        <?= $form->field($model, 'params')->textInput() ?> 

    </div>   
</div>  





<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('menu', 'Create') : Yii::t('menu', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>



