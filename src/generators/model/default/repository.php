<?php
/**
 * This is the template for generating the Repository class.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\model\Generator
 * @var $className string class name
 * @var $modelClassName string
 * @var $queryClassName string
 */

use chulakov\gii\helpers\ModuleHelper;

echo ModuleHelper::copyright('Файл класса ' . $className);
?>
namespace <?= $generator->moduleNamespace; ?>\repositories;

use chulakov\components\repositories\Repository;
use <?= $generator->moduleNamespace; ?>\models\scopes\<?= $queryClassName; ?>;
use <?= $generator->moduleNamespace; ?>\models\<?= $modelClassName; ?>;

class <?= $className; ?> extends Repository
{
    /**
     * Модель поиска
     *
     * @return <?= $queryClassName . "\n"; ?>
     */
    public function query()
    {
        return <?= $modelClassName; ?>::find();
    }
}
