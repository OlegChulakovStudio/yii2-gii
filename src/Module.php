<?php
/**
 * Файл класса Module
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\gii;

class Module extends \yii\gii\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'chulakov\gii\controllers';
    /**
     * @inheritdoc
     */
    public $viewPath = '@yii/gii/views';

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application) {
            $app->getUrlManager()->addRules([
                ['class' => 'yii\web\UrlRule', 'pattern' => $this->id, 'route' => $this->id . '/default/index'],
                ['class' => 'yii\web\UrlRule', 'pattern' => $this->id . '/<id:\w+>', 'route' => $this->id . '/default/view'],
                ['class' => 'yii\web\UrlRule', 'pattern' => $this->id . '/<controller:[\w\-]+>/<action:[\w\-]+>', 'route' => $this->id . '/<controller>/<action>'],
            ], false);
        } elseif ($app instanceof \yii\console\Application) {
            $app->controllerMap[$this->id] = [
                'class' => 'chulakov\gii\console\GenerateController',
                'generators' => array_merge($this->coreGenerators(), $this->generators),
                'module' => $this,
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function getControllerPath()
    {
        return \Yii::getAlias('@vendor/chulakov/yii2-gii/src/console');
    }

    /**
     * Возвращает список допустимых генераторов кода
     *
     * @return array
     */
    protected function coreGenerators()
    {
        return [
            'module' => ['class' => 'chulakov\gii\generators\module\Generator'],
            'model' => ['class' => 'chulakov\gii\generators\model\Generator'],
            'crud' => ['class' => 'chulakov\gii\generators\crud\Generator'],
        ];
    }
}
