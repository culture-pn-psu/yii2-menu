<?php

namespace firdows\menu\controllers;

use Yii;
use firdows\menu\models\Menu;
use firdows\menu\models\MenuAuth;
use firdows\menu\models\MenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * DefaultController implements the CRUD actions for Menu model.
 */
class DefaultController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Menu();

        if ($model->load(Yii::$app->request->post())) {
            //post
            $post = Yii::$app->request->post();
            $model->created_at = time();
            $model->created_by = Yii::$app->user->id;


            $transaction = \Yii::$app->db->beginTransaction();
            try {

                if ($flag = $model->save(false)) {

                    $title = $post['Menu']['items'];
                    if ($title) {
                        MenuAuth::deleteAll(['menu_id' => $model->id]);
                        foreach ($title as $key => $val) {
                            $menuAuth = new MenuAuth();
                            $menuAuth->menu_id = $model->id;
                            $menuAuth->item_name = $val;

                            if (($flag = $menuAuth->save(false)) === false) {
                                $transaction->rollBack();
                                break;
                            } else {
                                print_r($articleTag->getErrors());
                            }
                        }
                    }
                } else {
                    print_r($model->getError());
                    exit();
                }

                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $model->items = $model->itemAll;

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();



            $model->created_at = time();
            $model->created_by = Yii::$app->user->id;
            $model->params = $post['Menu']['params'] ? Json::decode($post['Menu']['params']) : null;
            $transaction = \Yii::$app->db->beginTransaction();
            try {

                if ($flag = $model->save(false)) {

                    $title = $post['Menu']['items'];
                    MenuAuth::deleteAll(['menu_id' => $model->id]);
                    if ($title) {
                        
                        foreach ($title as $key => $val) {
                            $menuAuth = new MenuAuth();
                            $menuAuth->menu_id = $model->id;
                            $menuAuth->item_name = $val;

                            if (($flag = $menuAuth->save(false)) === false) {
                                $transaction->rollBack();
                                break;
                            } else {
                                print_r($menuAuth->getErrors());
                            }
                        }
                    }
                } else {
                    print_r($model->getError());
                    exit();
                }

                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
        $model->params = $model->params ? Json::encode($model->params) : null;
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
