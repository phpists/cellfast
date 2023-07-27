<?php

namespace noIT\feedback\controllers;

use noIT\feedback\FeedbackModule;
use noIT\feedback\models\Feedback;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\web\Response;

/**
 * Default controller for the `feedback` module
 */
class DefaultController extends Controller
{
    public function actionIndex()
    {

        /** @var FeedbackModule $module */
        $module = Yii::$app->getModule('feedback');
        
        if (Yii::$app->request->isAjax) {

            $modelName = base64_decode(Yii::$app->request->post('model', base64_encode(Feedback::className())));

            if (!class_exists($modelName)) {
                throw new NotFoundHttpException("Page not found");
            }

            /** @var Feedback $model */
            $model = new $modelName([
                'adminEmailTo' => $module->adminEmails,
            ]);

            if ($model->load(Yii::$app->request->post())) {

                if ($model->save() && $model->sendEmail()) {

                    $title = Yii::t('app', 'successfull_modal_title');
                    $status = 'success';
                    $message = Yii::t('app', 'successfull_modal_message');
                } else {
                    $title = Yii::t('app', 'error_modal_title');
                    $status = 'error';
                    $message = Yii::t('app', 'error_modal_message');
                }

                Yii::$app->response->format = Response::FORMAT_JSON;

                return [
                    'title' => $title,
                    'status' => $status,
                    'message' => $message,
                    'closeButton' => Yii::t('app', 'modal_close_button')
                ];

            }

        } else {

            $modelName = base64_decode(Yii::$app->request->post('model', base64_encode(Feedback::className())));

            if (!class_exists($modelName)) {
                throw new NotFoundHttpException("Page not found");
            }

            /** @var Feedback $model */
            $model = new $modelName([]);

            if ($model->load(Yii::$app->request->post())) {

                if ($model->save() && $model->sendEmail()) {

                    Yii::$app->session->setFlash('success', $model->successfull);
                    return $this->redirect($model->getSource());

                } else {
                    Yii::$app->session->setFlash('error', $model->error);
                    return $this->refresh();
                }
            }

            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }
}
