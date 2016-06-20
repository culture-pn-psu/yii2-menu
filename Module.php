<?php

namespace firdows\menu;

/**
 * menu module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'firdows\menu\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->layout = 'left-menu.php';
        parent::init();

        // custom initialization code goes here
    }
}
