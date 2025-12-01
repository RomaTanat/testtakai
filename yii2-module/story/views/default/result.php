<?php

use yii\helpers\Html;

$this->title = 'Ваша сказка';
?>

<style>
    .story-result-page {
        max-width: 900px;
        margin: 40px auto;
        padding: 40px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .story-content {
        font-size: 1.1em;
        line-height: 1.8;
        color: #2d3748;
    }

    .story-content h1 {
        color: #667eea;
        margin-bottom: 20px;
    }

    .story-content h2,
    .story-content h3 {
        color: #764ba2;
        margin-top: 25px;
        margin-bottom: 15px;
    }

    .back-btn {
        display: inline-block;
        margin-top: 30px;
        padding: 12px 30px;
        background: #667eea;
        color: white;
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background: #764ba2;
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }
</style>

<div class="story-result-page">
    <div class="story-content" id="resultContent">
        <p>Загрузка сказки...</p>
    </div>

    <?= Html::a('← Создать новую сказку', ['index'], ['class' => 'back-btn']) ?>
</div>