<?php
/**
 * Файл класса GenerateController
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\gii\console;

/**
 * This is the command line version of Chulakov Gii - a code generator.
 *
 * You can use this command to generate modules components. For example,
 * to generate an ActiveRecord model based on a DB table, you can run:
 *
 * ```
 * $ ./yii gii/model --tableName=city --modelClass=City
 * ```
 */
class GenerateController extends \yii\gii\console\GenerateController
{
    /**
     * @var \chulakov\gii\Module
     */
    public $module;

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        $actions = [];
        foreach ($this->generators as $name => $generator) {
            $actions[$name] = [
                'class' => 'chulakov\gii\console\GenerateAction',
                'generator' => $generator,
            ];
        }
        return $actions;
    }
}
