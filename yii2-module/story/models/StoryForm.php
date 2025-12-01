<?php

namespace app\modules\story\models;

use Yii;
use yii\base\Model;

/**
 * StoryForm is the model for the fairytale generation form.
 */
class StoryForm extends Model
{
    public $age;
    public $language;
    public $characters = [];

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // Age is required, must be an integer between 6 and 18
            ['age', 'required', 'message' => 'Пожалуйста, укажите возраст ребёнка.'],
            ['age', 'integer', 'min' => 6, 'max' => 18, 'message' => 'Возраст должен быть от 6 до 18 лет.'],

            // Language is required and must be either 'ru' or 'kk'
            ['language', 'required', 'message' => 'Пожалуйста, выберите язык.'],
            ['language', 'in', 'range' => ['ru', 'kk'], 'message' => 'Язык должен быть русский или казахский.'],

            // Characters is required and must have at least one selection
            ['characters', 'required', 'message' => 'Пожалуйста, выберите хотя бы одного персонажа.'],
            ['characters', 'each', 'rule' => ['string']],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'age' => 'Возраст ребёнка',
            'language' => 'Язык сказки',
            'characters' => 'Персонажи',
        ];
    }

    /**
     * Get available characters for selection
     * @return array
     */
    public static function getAvailableCharacters()
    {
        return [
            'Заяц' => 'Заяц',
            'Волк' => 'Волк',
            'Лиса' => 'Лиса',
            'Медведь' => 'Медведь',
            'Колобок' => 'Колобок',
            'Алдар Көсе' => 'Алдар Көсе (казахский фольклор)',
            'Әйел Арстан' => 'Әйел Арстан (казахский фольклор)',
            'Жар-Жар' => 'Жар-Жар (казахский фольклор)',
            'Принцесса' => 'Принцесса',
            'Дракон' => 'Дракон',
        ];
    }

    /**
     * Get language options
     * @return array
     */
    public static function getLanguageOptions()
    {
        return [
            'ru' => 'Русский',
            'kk' => 'Казахский (Қазақша)',
        ];
    }
}
