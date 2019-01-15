<?php
/**
 * This is the template for generating a CRUD bootstrap class file.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\crud\Generator
 * @var $className string
 * @var $mapperClassName string
 * @var $factoryClassName string
 * @var $searchClassName string
 * @var $formClassName string
 * @var $repositoryClassName string
 * @var $serviceClassName string
 */

use chulakov\gii\helpers\ModuleHelper;

echo ModuleHelper::copyright('Файл класса ' . $className);
?>
namespace <?= $generator->moduleNamespace; ?>\bootstrap;

use yii\base\BootstrapInterface;

class <?= $className; ?> implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        \Yii::$container->setDefinitions([
            'chulakov\components\models\mappers\Mapper' => '<?= $generator->moduleNamespace; ?>\models\mappers\<?= $mapperClassName; ?>',
            'chulakov\components\models\factories\FactoryInterface' => '<?= $generator->moduleNamespace; ?>\models\factories\<?= $factoryClassName; ?>',
            'chulakov\components\repositories\Repository' => '<?= $generator->moduleNamespace; ?>\repositories\<?= $repositoryClassName; ?>',
            'chulakov\components\services\Service' => '<?= $generator->moduleNamespace; ?>\services\<?= $serviceClassName; ?>',
        ]);
    }
}
