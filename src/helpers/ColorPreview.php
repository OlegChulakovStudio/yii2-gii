<?php
/**
 * Файл класса ColorPreview
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\gii\helpers;

use yii\helpers\Html;

class ColorPreview
{
    /**
     * Превью для полей со значеием цвета
     *
     * @param string $color
     * @return string
     */
    public static function display($color)
    {
        return Html::tag('div', '', [
            'style' => "background: {$color}; padding: 13px;",
        ]);
    }
}
