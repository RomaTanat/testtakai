<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 40px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .header {
        text-align: center;
        margin-bottom: 40px;
    }

    .header h1 {
        font-size: 2em;
        color: #2d3748;
        margin-bottom: 8px;
    }

    .header p {
        color: #718096;
        font-size: 1em;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        font-size: 0.95em;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1em;
        transition: all 0.2s;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .characters-section {
        margin-bottom: 24px;
    }

    .suggested-characters {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px;
    }

    .character-tag {
        padding: 8px 14px;
        background: #f7fafc;
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.9em;
        color: #4a5568;
    }

    .character-tag:hover {
        background: #edf2f7;
        border-color: #667eea;
        transform: translateY(-1px);
    }

    .character-tag.selected {
        background: #667eea;
        border-color: #667eea;
        color: white;
    }

    .characters-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 12px;
    }

    .character-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        background: #f7fafc;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
    }

    .character-item input {
        flex: 1;
        border: none;
        background: transparent;
        font-size: 1em;
        padding: 4px;
    }

    .character-item input:focus {
        outline: none;
    }

    .remove-btn {
        background: #fc8181;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.85em;
        transition: all 0.2s;
    }

    .remove-btn:hover {
        background: #f56565;
    }

    .add-character-btn {
        width: 100%;
        padding: 10px;
        background: #f7fafc;
        border: 2px dashed #cbd5e0;
        border-radius: 8px;
        color: #667eea;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .add-character-btn:hover {
        background: #edf2f7;
        border-color: #667eea;
    }

    .submit-btn {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1em;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 8px;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .submit-btn:active {
        transform: translateY(0);
    }

    .help-text {
        font-size: 0.85em;
        color: #718096;
        margin-top: 4px;
    }

    .error-message {
        color: #e53e3e;
        font-size: 0.85em;
        margin-top: 4px;
    }

    .section-divider {
        margin: 12px 0;
        text-align: center;
        color: #a0aec0;
        font-size: 0.85em;
    }

    /* Loading overlay */
    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    .loading-content {
        text-align: center;
        color: white;
    }

    .spinner {
        border: 4px solid rgba(255, 255, 255, 0.2);
        border-top: 4px solid white;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 0.8s linear infinite;
        margin: 0 auto 20px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Story result */
    .story-result {
        display: none;
        max-width: 800px;
        margin: 40px auto;
        padding: 40px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .story-content {
        font-size: 1.05em;
        line-height: 1.7;
        color: #2d3748;
    }

    .story-content h1 {
        color: #667eea;
        margin-bottom: 20px;
    }

    .story-content h2,
    .story-content h3 {
        color: #764ba2;
        margin-top: 20px;
        margin-bottom: 12px;
    }

    .back-btn {
        display: inline-block;
        margin-top: 30px;
        padding: 12px 24px;
        background: #667eea;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .back-btn:hover {
        background: #764ba2;
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }
</style>

<div class="container">
    <div class="header">
        <h1>✨ Генератор сказок</h1>
        <p>Создайте уникальную сказку для вашего ребёнка</p>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'story-form',
        'options' => ['class' => 'story-form'],
    ]); ?>

    <div class="form-group">
        <?= $form->field($model, 'age')->textInput([
            'type' => 'number',
            'min' => 6,
            'max' => 18,
            'placeholder' => 'Введите возраст (6-18 лет)',
            'class' => 'form-control'
        ])->label('Возраст ребёнка') ?>
        <div class="help-text">Для детей от 6 до 18 лет</div>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'language')->dropDownList(
            StoryForm::getLanguageOptions(),
            [
                'prompt' => 'Выберите язык сказки',
                'class' => 'form-control'
            ]
        )->label('Язык сказки') ?>
    </div>

    <div class="characters-section">
        <label>Персонажи сказки</label>
        <div class="help-text" style="margin-bottom: 12px;">Выберите из предложенных или добавьте своих</div>

        <div class="suggested-characters">
            <div class="character-tag" onclick="toggleCharacter(this, 'Заяц')">🐰 Заяц</div>
            <div class="character-tag" onclick="toggleCharacter(this, 'Волк')">🐺 Волк</div>
            <div class="character-tag" onclick="toggleCharacter(this, 'Лиса')">🦊 Лиса</div>
            <div class="character-tag" onclick="toggleCharacter(this, 'Медведь')">🐻 Медведь</div>
            <div class="character-tag" onclick="toggleCharacter(this, 'Колобок')">🥮 Колобок</div>
            <div class="character-tag" onclick="toggleCharacter(this, 'Алдар Көсе')">🎭 Алдар Көсе</div>
            <div class="character-tag" onclick="toggleCharacter(this, 'Принцесса')">👸 Принцесса</div>
            <div class="character-tag" onclick="toggleCharacter(this, 'Дракон')">🐉 Дракон</div>
        </div>

        <div class="section-divider">или добавьте своих персонажей</div>

        <div class="characters-list" id="charactersList"></div>

        <button type="button" class="add-character-btn" onclick="addCustomCharacter()">+ Добавить своего
            персонажа</button>
        <?= Html::error($model, 'characters', ['class' => 'error-message']) ?>
    </div>

    <button type="submit" class="submit-btn">🎭 Создать сказку</button>

    <?php ActiveForm::end(); ?>
</div>

<!-- Loading overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="spinner"></div>
        <h2>Создаём волшебную сказку...</h2>
        <p>Это может занять несколько секунд</p>
    </div>
</div>

<!-- Story result container -->
<div class="story-result" id="storyResult">
    <div class="story-content" id="storyContent"></div>
    <a href="<?= \yii\helpers\Url::to(['index']) ?>" class="back-btn">← Создать новую сказку</a>
</div>

<?php
$generateUrl = \yii\helpers\Url::to(['generate']);
$this->registerJs(<<<JS
// Include marked.js for markdown parsing
const script = document.createElement('script');
script.src = 'https://cdn.jsdelivr.net/npm/marked/marked.min.js';
document.head.appendChild(script);

// Selected characters from suggestions
let selectedCharacters = new Set();

// Toggle suggested character
window.toggleCharacter = function(element, character) {
    if (selectedCharacters.has(character)) {
        selectedCharacters.delete(character);
        element.classList.remove('selected');
    } else {
        selectedCharacters.add(character);
        element.classList.add('selected');
    }
};

// Add custom character input
window.addCustomCharacter = function() {
    const list = document.getElementById('charactersList');
    const item = document.createElement('div');
    item.className = 'character-item';
    item.innerHTML = `
        <input type="text" class="custom-character" placeholder="Введите имя персонажа..." required>
        <button type="button" class="remove-btn" onclick="removeCustomCharacter(this)">Удалить</button>
    `;
    list.appendChild(item);
};

// Remove custom character
window.removeCustomCharacter = function(btn) {
    btn.closest('.character-item').remove();
};

// Form submission
document.getElementById('story-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Collect all characters
    const allCharacters = Array.from(selectedCharacters);
    
    // Add custom characters
    const customInputs = document.querySelectorAll('.custom-character');
    customInputs.forEach(input => {
        if (input.value.trim()) {
            allCharacters.push(input.value.trim());
        }
    });
    
    // Validate at least one character
    if (allCharacters.length === 0) {
        alert('Пожалуйста, выберите или добавьте хотя бы одного персонажа');
        return;
    }
    
    // Create form data
    const formData = new FormData(this);
    
    // Remove any existing character fields and add collected ones
    formData.delete('StoryForm[characters][]');
    allCharacters.forEach(char => {
        formData.append('StoryForm[characters][]', char);
    });
    
    const loadingOverlay = document.getElementById('loadingOverlay');
    const storyResult = document.getElementById('storyResult');
    const storyContent = document.getElementById('storyContent');
    const formContainer = document.querySelector('.container');
    
    // Show loading
    loadingOverlay.style.display = 'flex';
    storyContent.innerHTML = '';
    
    // Create XMLHttpRequest for streaming
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '{$generateUrl}', true);
    
    let buffer = '';
    let lastProcessedIndex = 0;
    
    xhr.onprogress = function() {
        const response = xhr.responseText.substring(lastProcessedIndex);
        lastProcessedIndex = xhr.responseText.length;
        
        // Parse SSE data
        const lines = response.split('\\n');
        for (let line of lines) {
            if (line.startsWith('data: ')) {
                try {
                    const data = JSON.parse(line.substring(6));
                    
                    if (data.error) {
                        storyContent.innerHTML = '<div style="color: #e53e3e; padding: 20px; background: #fed7d7; border-radius: 8px;">' + data.error + '</div>';
                        loadingOverlay.style.display = 'none';
                        formContainer.style.display = 'none';
                        storyResult.style.display = 'block';
                    } else if (data.done) {
                        loadingOverlay.style.display = 'none';
                        formContainer.style.display = 'none';
                        storyResult.style.display = 'block';
                    } else if (data.content) {
                        buffer += data.content;
                        // Parse markdown and display
                        if (typeof marked !== 'undefined') {
                            storyContent.innerHTML = marked.parse(buffer);
                        } else {
                            storyContent.textContent = buffer;
                        }
                        // Scroll to bottom
                        window.scrollTo(0, document.body.scrollHeight);
                    }
                } catch (e) {
                    console.error('Error parsing SSE data:', e);
                }
            }
        }
    };
    
    xhr.onload = function() {
        if (xhr.status !== 200) {
            storyContent.innerHTML = '<div style="color: #e53e3e; padding: 20px; background: #fed7d7; border-radius: 8px;">Ошибка при генерации сказки. Пожалуйста, попробуйте снова.</div>';
            loadingOverlay.style.display = 'none';
            formContainer.style.display = 'none';
            storyResult.style.display = 'block';
        }
    };
    
    xhr.onerror = function() {
        storyContent.innerHTML = '<div style="color: #e53e3e; padding: 20px; background: #fed7d7; border-radius: 8px;">Ошибка соединения. Убедитесь, что Python API запущен.</div>';
        loadingOverlay.style.display = 'none';
        formContainer.style.display = 'none';
        storyResult.style.display = 'block';
    };
    
    xhr.send(formData);
});
JS
);
?>