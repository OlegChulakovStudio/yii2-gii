<?php
/**
 * Файл класса ColorColumn
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\gii\widgets;

use yii\helpers\Html;
use yii\grid\DataColumn;

class ColorColumn extends DataColumn
{
    /**
     * В каком формате значение каждой модели данных будет отображаться
     * 
     * @var string 
     */
    public $format = 'raw';
    /**
     * Атрибуты HTML для тега ячейки данных
     * 
     * @var array 
     */
    public $contentOptions = ['class' => 'text-center'];

    /**
     * @inheritDoc
     */
    public function renderDataCellContent($model, $key, $index)
    {
        if ($this->content === null) {
            $color = (string) $model->{$this->attribute};
            return Html::tag('div', '', [
                'style' => "background: {$color}; padding: 13px;",
            ]);
        }
        return parent::renderDataCellContent($model, $key, $index);
    }
}
