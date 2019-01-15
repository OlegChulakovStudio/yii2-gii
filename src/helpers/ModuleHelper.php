<?php
/**
 * Файл класса ModuleHelper
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\gii\helpers;

class ModuleHelper
{
    /**
     * Шапка с копирайтом для шаблона классов
     *
     * @param string $title
     * @return string
     */
    public static function copyright($title)
    {
        $year = date('Y');
        return <<<TEXT
<?php
/**
 * {$title}
 *
 * @copyright Copyright (c) {$year}, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */


TEXT;
    }
}
