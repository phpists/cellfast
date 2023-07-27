<?php
namespace noIT\seo\helpers;

use Yii;

class RedirectHelper
{
    public static function beforeRequest()
    {
        $pathInfo = Yii::$app->request->pathInfo;
        $query = Yii::$app->request->queryString;

        if (!empty($pathInfo)) {

            $isStringHasUpperCase = preg_match('/[A-Z]/', $pathInfo);
            $isStringHasSlash = substr($pathInfo, -1) === '/';

            if($isStringHasSlash || $isStringHasUpperCase) {

                if($isStringHasUpperCase) {
                    $pathInfo = strtolower($pathInfo);
                }

                $url = $isStringHasSlash ? '/' . substr($pathInfo, 0, -1) : '/' . $pathInfo;

                if ($query) {
                    $url .= '?' . $query;
                }

                Yii::$app->response->redirect($url, 301);
                Yii::$app->end();
            }

        }
    }
}