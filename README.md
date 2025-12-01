# Генератор сказок (Fairytale Generator)

Система для генерации детских сказок на русском и казахском языках с использованием искусственного интеллекта.

## Что это?

Проект состоит из двух частей:
- **Python API** (FastAPI) - генерирует сказки через AI модель
- **Yii 2 модуль** (PHP) - веб-интерфейс для пользователя

**Поддерживаемые AI модели:**
-  **HuggingFace Mistral-7B** (бесплатно, по умолчанию) - не требует API ключа
-  **OpenAI GPT-3.5** (платно, опционально) - требует API ключ


## Шаг 1: Выбор AI модели

### Вариант А: HuggingFace (бесплатно, рекомендуется)

**Просто запустите проект** - всё уже настроено! API ключ не нужен.

### Вариант Б: OpenAI (платно)

1. В папке проекта найдите файл `.env.example`

2. Создайте копию этого файла с именем `.env` (без ".example"):
```bash
# Windows
copy .env.example .env
```

3. Откройте файл `.env` и измените:
```bash
AI_MODEL=openai
OPENAI_API_KEY=ваш-api-ключ-здесь
```

4. Получите API ключ: https://platform.openai.com/api-keys

**Важно:** Файл `.env` находится в корне проекта (папка `testtaskai/`)

## Шаг 2: Установка Python API

### Требования
- Python 3.8 или выше
- pip (менеджер пакетов Python)

### Установка

1. Откройте терминал в папке проекта

2. Установите зависимости:
```bash
pip install -r requirements.txt
```

3. Запустите сервер:
```bash
uvicorn main:app --reload
```

Сервер запустится на: `http://localhost:8000`

## Шаг 3: Установка Yii 2 приложения

### Вариант А: Если у вас УЖЕ есть Yii 2 приложение

1. Скопируйте папку `yii2-module/story` в `modules/` вашего Yii 2 приложения

2. Добавьте в `config/web.php`:
```php
'modules' => [
    'story' => [
        'class' => 'app\modules\story\Module',
        'pythonApiUrl' => 'http://localhost:8000',
    ],
],
```

3. Откройте: `http://ваш-домен/index.php?r=story/default/index`

### Вариант Б: Если НЕТ Yii 2 приложения (создать новое)

1. Установите Composer (если нет): https://getcomposer.org/

2. Создайте новое Yii 2 приложение:
```bash
composer create-project --prefer-dist yiisoft/yii2-app-basic yii-fairytale
cd yii-fairytale
```

3. Скопируйте модуль:
```bash
# Из папки testtaskai скопируйте yii2-module/story в yii-fairytale/modules/
# Windows:
xcopy /E /I ..\testtaskai\yii2-module\story modules\story
```

4. Откройте `config/web.php` и добавьте после строки `'id' => 'basic-app',`:
```php
'modules' => [
    'story' => [
        'class' => 'app\modules\story\Module',
        'pythonApiUrl' => 'http://localhost:8000',
    ],
],
```

5. Запустите Yii 2 dev-сервер:
```bash
php yii serve --port=8080
```

6. Откройте в браузере:
```
http://localhost:8080/index.php?r=story/default/index
```

## Как использовать

### Для запуска проекта нужно:

**Терминал 1:** Python API
```bash
cd testtaskai
uvicorn main:app --reload
```
Должно показать: ` Using HuggingFace: ... (FREE)`

**Терминал 2:** Yii 2 приложение
```bash
cd yii-fairytale  # или ваша папка Yii 2
php yii serve --port=8080
```

**Браузер:** Откройте форму
```
http://localhost:8080/index.php?r=story/default/index
```

### Использование формы:
1. **Возраст** - введите от 6 до 18 лет
2. **Язык** - выберите русский или казахский
3. **Персонажи** - нажмите на предложенных или добавьте своих
4. **Создать сказку** - текст появится постепенно в реальном времени

## Технические детали

### Как работает?

1. Форма отправляет данные в Yii 2 контроллер
2. Yii 2 проверяет данные и делает запрос к Python API
3. Python API генерирует сказку через AI модель (HuggingFace или OpenAI)
4. Текст передается потоком (streaming) обратно в браузер
5. Вы видите сказку в реальном времени по мере генерации

### Потоковая передача (Streaming)

PHP не поддерживает настоящий streaming из коробки, поэтому используется:
- Python API отправляет текст частями
- PHP получает части через cURL
- Каждая часть отправляется в браузер как Server-Sent Event (SSE)
- JavaScript получает события и обновляет страницу

## Структура проекта

```
testtaskai/
├── main.py              # Python FastAPI приложение
├── requirements.txt     # Python зависимости
├── .env.example         # Пример конфигурации
└── yii2-module/
    └── story/
        ├── Module.php
        ├── controllers/DefaultController.php
        ├── models/StoryForm.php
        └── views/default/
            ├── index.php
            └── result.php
```

## Возможные проблемы

### Python API не запускается

Ошибка при запуске `uvicorn`

**Решение:**
```bash
pip install -r requirements.txt --upgrade
python -m uvicorn main:app --reload
```

### Не подключается к Python API

"Не удалось подключиться к Python API"

**Решение:**
1. Проверьте, запущен ли Python сервер: `http://localhost:8000`
2. Убедитесь, что порт 8000 не занят
3. Измените `pythonApiUrl` в `config/web.php` если используете другой порт

### Сказка не генерируется

Долгая загрузка или ошибка

**Решение:**
1. Первый запуск медленный (модель загружается)
2. Проверьте интернет (HuggingFace работает онлайн)
3. Попробуйте еще раз - сервис может быть перегружен

## Пример прямого запроса к API

```bash
curl -X POST http://localhost:8000/generate_story \
  -H "Content-Type: application/json" \
  -d '{
    "age": 7,
    "language": "ru",
    "characters": ["Заяц", "Волк"]
  }'
```
