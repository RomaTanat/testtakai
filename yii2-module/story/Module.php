<?php

namespace app\modules\story;

use Yii;

/**
 * Story module for generating fairytales
 * 
 * This module acts as a proxy to the Python FastAPI service
 * that generates fairytales using OpenAI API.
 */
class Module extends \yii\base\Module
{
    /**
     * @var string URL of the Python API service
     */
    public $pythonApiUrl = 'http://localhost:8000';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\story\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        // Custom initialization code goes here
    }
}
