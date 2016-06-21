<?php

namespace firdows\menu\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\rbac\models\AuthItem;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property integer $menu_category_id
 * @property integer $parent_id
 * @property string $title
 * @property string $router
 * @property string $parameter
 * @property string $icon
 * @property string $status
 * @property string $item_name
 * @property string $target
 * @property string $protocol
 * @property string $home
 * @property integer $sort
 * @property string $language
 * @property string $assoc
 * @property integer $created_at
 * @property integer $created_by
 *
 * @property MenuCategory $menuCategory
 */
class Menu extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['menu_category_id', 'title', 'router'], 'required'],
            [['menu_category_id', 'parent_id', 'created_at', 'created_by', 'sort'], 'integer'],
            [['status', 'home', 'params'], 'string'],
            [['title'], 'string', 'max' => 200],
            [['router', 'parameter'], 'string', 'max' => 250],
            [['icon', 'target'], 'string', 'max' => 30],
            [['item_name'], 'string', 'max' => 64],
            [['protocol'], 'string', 'max' => 20],
            [['language'], 'string', 'max' => 7],
            [['assoc'], 'string', 'max' => 12],
            [['items'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('system', 'รหัสเมนู'),
            'menu_category_id' => Yii::t('system', 'หมวดเมนู'),
            'parent_id' => Yii::t('system', 'ภายใต้เมนู'),
            'title' => Yii::t('system', 'ชื่อเมนู'),
            'router' => Yii::t('system', 'ลิงค์'),
            'parameter' => Yii::t('system', 'พารามิเตอร์'),
            'icon' => Yii::t('system', 'ไอคอน'),
            'status' => Yii::t('system', 'สถานะ'),
            'item_name' => Yii::t('system', 'บทบาท'),
            'target' => Yii::t('system', 'เป้าหมาย'),
            'protocol' => Yii::t('system', 'โปรโตคอล'),
            'home' => Yii::t('system', 'หน้าแรก'),
            'sort' => Yii::t('system', 'เรียง'),
            'language' => Yii::t('system', 'ภาษา'),
            'params' => Yii::t('system', 'ลักษณะพิเศษ'),
            'assoc' => Yii::t('system', 'ชุดเมนู'),
            'created_at' => Yii::t('system', 'สร้างเมื่อ'),
            'created_by' => Yii::t('system', 'สร้างโดย'),
            'items' => Yii::t('system', 'บทบาท'),
            'itemsList' => Yii::t('system', 'บทบาท'),
        ];
    }

    public $items;

    public static function itemsAlias($key) {
        $items = [
            'status' => [
                0 => Yii::t('app', 'ร่าง'),
                1 => Yii::t('app', 'แสดง'),
                2 => Yii::t('app', 'ซ่อน')
            ],
        ];
        return ArrayHelper::getValue($items, $key, []);
    }

    public function getStatusLabel() {
        return ArrayHelper::getValue($this->getItemStatus(), $this->status);
    }

    public static function getItemStatus() {
        return self::itemsAlias('status');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuCategory() {
        return $this->hasOne(MenuCategory::className(), ['id' => 'menu_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent() {
        return $this->hasOne(Menu::className(), ['id'=>'parent_id']);
    }

    public function getParentTitle() {
        if ($this->parent_id) {
            $parent = self::find()->where(['id' => $this->parent_id])->one();

            //$str = $str?$str->title:null;
            //return $parent->title;
//        echo "<pre>";
//        print_r($parent);
//        echo "</pre>";
            return $parent->title;
        } else {
            return null;
        }
    }

    public static function getList() {
        return ArrayHelper::map(self::find()->orderBy(['menu_category_id' => SORT_ASC, 'parent_id' => SORT_ASC])->all(), 'id', 'title');
    }

    public static function getSortBy($menu_category_id = null, $parent_id = null) {
        $sort = ArrayHelper::merge(['fist' => 'หน้าสุด', 'last' => 'ท้ายสุด'], ArrayHelper::map(self::find()->where(['parent_id' => $parent_id, 'menu_category_id' => $menu_category_id])->orderBy(['sort' => SORT_ASC])->all(), 'sort', 'title'));
        return $sort;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus() {
        return $this->hasMany(Menu::className(), ['parent_id' => 'id']);
    }

    public static function sortLast($menu_category_id = null, $parent_id = null) {
        $sort = self::find()
                        ->where(['parent_id' => $parent_id, 'menu_category_id' => $menu_category_id])
                        ->select('max(sort)')->scalar();
        return $sort;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuAuths() {
        return $this->hasMany(MenuAuth::className(), ['menu_id' => 'id']);
    }
    
    public function getItemAll() {
        $model = $this->menuAuths;
        return $model ? ArrayHelper::map($model, 'item_name', 'item_name') : null;
    }
    
    public function getItemsList() {
        $model = $this->menuAuths;        
        $str = [];
        if ($model) {
            foreach ($model as $val) {
                $str[] = $val->item_name;
            }
            return implode(', ', $str);
        }
        return null;
    }
    
    public static function getItemsListDistinct() {
        //$model = $this->menuAuth->distinct; 
        $model = MenuAuth::find()->select('item_name')->distinct()->all();
        return ArrayHelper::map($model, 'item_name', 'item_name');
        
    }
    
    public static function getRouterDistinct() {
        //$model = $this->menuAuth->distinct; 
        $model = self::find()->select('router')->distinct()->orderBy('router')->all();
        return ArrayHelper::map($model, 'router', 'router');
        
    }
    
    public static function getParentDistinct() {
        //$model = $this->menuAuth->distinct; 
        $model = self::find()->where(['IS NOT','parent_id',NULL])->all();
        $parent = [];
        foreach($model as $k=>$val){
            //echo $val->parent_id;
            if($val->parent_id)
            $parent[$val->parent_id]=['id'=>$val->parent_id,'title'=>$val->parentTitle];
        }
//        print_r($parent);
//        exit();
        return ArrayHelper::map($parent, 'id', 'title');
        
    }
    
    public static function getAuth(){
        return ArrayHelper::map(AuthItem::getAll(), 'name', 'name');
    }

    public function getIconShow() {
        return '<i class="' . $this->icon . '" ></i>';
    }
    
}
