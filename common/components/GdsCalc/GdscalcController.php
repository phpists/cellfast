<?php

namespace common\components\GdsCalc;

use common\components\GdsCalc\components\GdsCalcDrain;
use common\components\GdsCalc\models\OrderForm;
use common\components\GdsCalc\models\GdsCalcModel;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class GdscalcController extends \yii\web\Controller
{
    protected $category;
    protected $component;
    protected $model;

    /**
     * @return GdsCalcDrain
     */
    protected function getComponent()
    {
        if ($this->component === null) {
            if ( !($component_alias = \Yii::$app->request->post('component')) || !($this->component = \Yii::$app->{$component_alias}) ) {
                $this->component = false;
            }
        }
        return $this->component;
    }

    /**
     * @return GdsCalcModel
     */
    protected function getModel()
    {
        if ($this->model === null) {
            if ( !($model_alias = \Yii::$app->request->post('model')) || !($component = $this->getComponent()) || !($this->model =  $component->getModel($model_alias)) ) {
                $this->model = false;
            }
        }
        return $this->model;
    }

    /**
     * GET-params $category, $component, $model
     * @return mixed
     */
    public function actionForm()
    {
        if (!($component = $this->getComponent()) || !($model = $this->getModel())) {
            // Незнаем компонент или модель
            return false;
        }

        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => Yii::t('app', $model->name),
                'form' => $this->renderAjax('@common/components/GdsCalc/views/forms/'. $model->alias, ['component' => $component, 'model' => $model]),
            ];
        }

        return $this->renderAjax('@common/components/GdsCalc/views/forms/'. $model->alias, ['component' => $component, 'model' => $model]);
    }

    /**
     * Расчет и вывод результатов
     * @return bool|string
     */
    public function actionCalc()
    {
        if (!($component = $this->getComponent()) || !($model = $this->getModel())) {
            // Незнаем компонент или модель
            return false;
        }

        $params = \Yii::$app->request->post('params', []);
        $component->setModel($model);

        if (!$model->load(\Yii::$app->request->post())) {
            var_dump($model->errors);exit;
        }

        return $this->renderAjax(
            '@common/components/GdsCalc/views/result/'. $component->alias .'/index',
            [
                'params' => $params,
                'systems' => $component->getSystems(),
                'calcResult' => $component->getCalcResult($params),
//                'colors' => $component->getColors(),
//                'component' => $component,
//                'products' => $products,
//                'counts' => $component->getCalcData(),
            ]);
    }

    public function actionOrder()
    {
        // Проверяем входящие данные
        if ( !($data = Yii::$app->request->post('data')) || !($data = json_decode($data, true)) || !isset($data['products'])) {
            throw new NotFoundHttpException("Bad request");
        }

        $model = new OrderForm();

        $model->data = $data;

        if (Yii::$app->request->post('send') && $model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Спасибо! Ваша заявка успешно отправлена');

            return $this->redirect($_SERVER['HTTP_REFERER']);
        }

        return $this->renderAjax('@common/components/GdsCalc/views/order', ['model' => $model]);
    }
}
