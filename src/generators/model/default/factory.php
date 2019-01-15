<?php
/**
 * This is the template for generating the Factory class.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\model\Generator
 * @var $className string class name
 * @var $modelClassName string
 * @var $mapperClassName string
 * @var $searchClassName string
 * @var $formClassName string
 */

use chulakov\gii\helpers\ModuleHelper;

echo ModuleHelper::copyright('Файл класса ' . $className);
?>
namespace <?= $generator->moduleNamespace; ?>\models\factories;

use chulakov\components\exceptions\FormException;
use chulakov\components\models\factories\FactoryInterface;
use <?= $generator->moduleNamespace; ?>\models\mappers\<?= $mapperClassName; ?>;
use <?= $generator->moduleNamespace; ?>\models\search\<?= $searchClassName; ?>;
use <?= $generator->moduleNamespace; ?>\models\forms\<?= $formClassName; ?>;
use <?= $generator->moduleNamespace; ?>\models\<?= $modelClassName; ?>;

class <?= $className; ?> implements FactoryInterface
{
    /**
     * Создать модель
     *
     * @param array $config
     * @return <?= $modelClassName . "\n"; ?>
     */
    public function makeModel($config = [])
    {
        return new <?= $modelClassName; ?>($config);
    }

    /**
     * Создать поисковую модель
     *
     * @param array $config
     * @return <?= $searchClassName . "\n"; ?>
     */
    public function makeSearch($config = [])
    {
        return new <?= $searchClassName; ?>($config);
    }

    /**
     * Создать форму
     *
     * @param <?= $mapperClassName; ?> $mapper
     * @param <?= $modelClassName; ?> $model
     * @param array $config
     * @return <?= $formClassName . "\n"; ?>
     * @throws FormException
     */
    public function makeForm($mapper, $model = null, $config = [])
    {
        return new <?= $formClassName; ?>($mapper, $model, $config);
    }
}
