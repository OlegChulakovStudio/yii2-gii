<?php
/**
 * This is the template for generating the Service class.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\model\Generator
 * @var $className string class name
 * @var $repositoryClassName string
 * @var $factoryClassName string
 * @var $mapperClassName string
 */

use chulakov\gii\helpers\ModuleHelper;

echo ModuleHelper::copyright('Файл класса ' . $className);
?>
namespace <?= $generator->moduleNamespace; ?>\services;

use chulakov\components\services\Service;
use <?= $generator->moduleNamespace; ?>\repositories\<?= $repositoryClassName; ?>;
use <?= $generator->moduleNamespace; ?>\models\factories\<?= $factoryClassName; ?>;
use <?= $generator->moduleNamespace; ?>\models\mappers\<?= $mapperClassName; ?>;

class <?= $className; ?> extends Service
{
    /**
     * Конструктор сервиса
     *
     * @param <?= $repositoryClassName; ?> $repository
     * @param <?= $factoryClassName; ?> $factory
     * @param <?= $mapperClassName; ?> $mapper
     */
    public function __construct(
        <?= $repositoryClassName; ?> $repository,
        <?= $factoryClassName; ?> $factory,
        <?= $mapperClassName; ?> $mapper
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->mapper = $mapper;
    }
}
