<?php
/**
 * This is the template for generating a module class file.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\module\Generator
 */
use chulakov\gii\helpers\ModuleHelper;

echo ModuleHelper::copyright('Файл модуля ' . $generator->moduleClass);
?>
namespace <?= $generator->moduleNamespace; ?>;

class <?= $generator->moduleClass; ?> extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->controllerMap = [
            // 'default' => '<?= $generator->moduleNamespace; ?>\controllers\DefaultController',
        ];
    }
}
