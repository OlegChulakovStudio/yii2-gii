<?php
/**
 * Файл класса TranslationsHelper
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\gii\helpers;

use \Yii;
use yii\helpers\Inflector;

class TranslationsHelper
{
    /**
     * Список общих предустановленных переводов
     *
     * @var array
     */
    protected static $mainTranslations = [
        'ID',
        'Sort' ,
        'Slug',
        'Title',
        'Description',
        'Content',
        'Active' ,
        'Published',
        'Created at',
        'Updated at',
        'Created by',
        'Updated by',

        'List',
        'Filters',

        'Save',
        'Update',
        'Edit',
        'Apply',
        'Cancel',
        'Search',
        'Create',
        'Create and continue',
        'Reset',
        'Back',

        'Collapse',
        'Remove',

        'All active status',
        'Only not active',
        'Only active',
    ];

    /**
     * Получение пути для переводов
     *
     * @param $title
     * @param $moduleName
     * @return string
     */
    public static function getTranslatePath($title, $moduleName)
    {
        $translations = array_map('strtolower', static::$mainTranslations);

        return in_array(strtolower($title), $translations)
            ? 'ch/all' : "ch/{$moduleName}";
    }

    /**
     * Форматирование заголовка
     *
     * @param $title
     * @return string
     */
    public static function formatTitle($title)
    {
        return ucfirst(strtolower($title));
    }
}
