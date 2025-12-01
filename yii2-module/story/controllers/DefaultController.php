<?php

namespace app\modules\story\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\modules\story\models\StoryForm;

/**
 * Default controller for the story module
 */
class DefaultController extends Controller
{
    /**
     * Displays the story generation form
     * @return string
     */
    public function actionIndex()
    {
        $model = new StoryForm();

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Handles form submission and streams the story from Python API
     * @return Response
     */
    public function actionGenerate()
    {
        $model = new StoryForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Get the Python API URL from module configuration
            $module = $this->module;
            $apiUrl = rtrim($module->pythonApiUrl, '/') . '/generate_story';

            // Prepare the request data
            $requestData = [
                'age' => (int) $model->age,
                'language' => $model->language,
                'characters' => $model->characters,
            ];

            try {
                // Disable output buffering for streaming
                if (ob_get_level()) {
                    ob_end_clean();
                }

                // Set up streaming response
                Yii::$app->response->format = Response::FORMAT_RAW;
                Yii::$app->response->headers->set('Content-Type', 'text/event-stream');
                Yii::$app->response->headers->set('Cache-Control', 'no-cache');
                Yii::$app->response->headers->set('X-Accel-Buffering', 'no');

                // Initialize cURL for streaming
                $ch = curl_init($apiUrl);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                ]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 120); // 2 minute timeout
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // 5 second connection timeout
                curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) {
                    // Send data as Server-Sent Events
                    echo "data: " . json_encode(['content' => $data]) . "\n\n";
                    if (ob_get_level()) {
                        ob_flush();
                    }
                    flush();
                    return strlen($data);
                });

                // Execute the request
                $result = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curlError = curl_error($ch);

                if ($result === false || $httpCode !== 200) {
                    curl_close($ch);

                    $errorMessage = 'Ошибка при обращении к сервису генерации сказок';
                    if ($curlError) {
                        $errorMessage .= ': ' . $curlError;
                    }
                    if ($httpCode === 0) {
                        $errorMessage = 'Не удалось подключиться к Python API. Убедитесь, что сервер запущен на порту 8000.';
                    }

                    echo "data: " . json_encode(['error' => $errorMessage]) . "\n\n";
                    if (ob_get_level()) {
                        ob_flush();
                    }
                    flush();
                } else {
                    curl_close($ch);
                }

                // Send completion event
                echo "data: " . json_encode(['done' => true]) . "\n\n";
                if (ob_get_level()) {
                    ob_flush();
                }
                flush();

                return Yii::$app->response;

            } catch (\Exception $e) {
                // Send error as SSE if we're already streaming
                echo "data: " . json_encode(['error' => 'Ошибка при генерации сказки: ' . $e->getMessage()]) . "\n\n";
                echo "data: " . json_encode(['done' => true]) . "\n\n";
                if (ob_get_level()) {
                    ob_flush();
                }
                flush();
                return Yii::$app->response;
            }
        }

        // If validation failed, return to form with errors
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Displays the result page (for non-streaming fallback)
     * @return string
     */
    public function actionResult()
    {
        return $this->render('result');
    }
}
