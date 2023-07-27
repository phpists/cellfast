<?php

namespace noIT\soap\components;

use noIT\soap\SoapServerModule as SOAP;
use yii\base\ErrorException;
use yii\base\ErrorHandler;
use yii\base\ExitException;
use yii\web\HttpException;
use yii\web\Response;

class ErrorHandlerComponent Extends ErrorHandler
{
    public $errorAction = "site/error";

    /** @var SOAP $module */
    public $module;

    public function __construct(array $config = [])
    {
        register_shutdown_function([$this, 'handleFatalError']);
        parent::__construct($config);
    }

    /**
     * Renders the exception.
     * @param \Exception|\Error $exception the exception to be rendered.
     */
    protected function renderException($exception)
    {
        $response = \Yii::$app->getResponse();

        if ($exception instanceof HttpException) {
            $response->format = Response::FORMAT_HTML;
            $response->setStatusCode($exception->statusCode);
            if ($this->errorAction !== null) {
                \Yii::$app->getErrorHandler()->exception = $exception;
                $result = \Yii::$app->runAction($this->errorAction);
                if ($result instanceof Response) {
                    $response = $result;
                } else {
                    $response->data = $result;
                }
            } else {
                $response->data =
                    "<pre>" .
                    htmlspecialchars(static::convertExceptionToString($exception), ENT_QUOTES, $response->charset) .
                    "</pre>";
            }
        } else {
            $statusCode = $exception->getCode();
            $response->setStatusCode (($statusCode < 100 || $statusCode >= 600) ? 500 : $statusCode);
            $response->data = $exception;
        }
        $response->send();
    }

    /**
     * Register this error handler.
     */
    public function register()
    {
        ini_set('display_errors', false);
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
    }

    /**
     * Handles fatal PHP errors.
     */
    public function handleFatalError()
    {
        if (SOAP::getInstance() === null) {
            return;
        }
        $error = error_get_last();
        if (ErrorException::isFatalError($error)) {
            ob_end_clean();
            $exception = new ErrorException($error['message'], $error['type'], $error['type'], $error['file'], $error['line']);
            $this->handleException($exception);
        }
    }

    public function handleError($code, $message, $file, $line)
    {
        $exception = new ErrorException($message, $code, $code, $file, $line);
        $this->handleException($exception);
    }

    /**
     * Handles uncaught PHP exceptions.
     *
     * This method is implemented as a PHP exception handler.
     *
     * @param \Exception $exception the exception that is not caught
     */
    public function handleException($exception)
    {
        $this->module = SOAP::getInstance();

        if ($exception instanceof ExitException) {
            return;
        }
        $this->exception = $exception;

        // disable error capturing to avoid recursive errors while handling exceptions
        $this->unregister();

        // set preventive HTTP status code to 500
        http_response_code(500);

        try {
            $this->logException($exception);
            if ($this->discardExistingOutput) {
                $this->clearOutput();
            }
            $this->renderException($exception);
            $this->module->get('dispatcher')->logger->flush(true);
            \Yii::getLogger()->flush(true);
            exit(1);
        } catch (\Exception $e) {
            // an other exception could be thrown while displaying the exception
            $this->handleFallbackExceptionMessage($e, $exception);
        } catch (\Throwable $e) {
            // additional check for \Throwable introduced in PHP 7
            $this->handleFallbackExceptionMessage($e, $exception);
        }
        $this->exception = null;
    }

    /**
     * Logs the given exception.
     * @param \Exception $exception the exception to be logged
     * @since 2.0.3 this method is now public.
     */
    public function logException($exception)
    {
        $category = get_class($exception);
        if ($exception instanceof HttpException) {
            $category = 'yii\\web\\HttpException:' . $exception->statusCode;
        } elseif ($exception instanceof \ErrorException) {
            $category .= ':' . $exception->getSeverity();
        }
        $this->module->log($exception, "error", $category);
        $this->module->log($exception->getMessage(), "error", "SOAP_error");
    }
}