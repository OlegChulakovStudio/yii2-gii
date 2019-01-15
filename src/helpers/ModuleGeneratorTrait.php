<?php
/**
 * Файл класса ModuleGeneratorTrait
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\gii\helpers;

/**
 * Module preset trait
 *
 * @property string $fullModuleName The full name of the module class. This property is read-only.
 * @property string $fullModulePath The directory that contains the module class. This property is read-only.
 * @property string $moduleNamespace The namespace of the module. This property is read-only.
 */
trait ModuleGeneratorTrait
{
    /**
     * @var string Module ID name
     */
    public $moduleID;
    /**
     * @var string Relation path to modules location
     */
    public $modulePath = 'common/modules';

    /**
     * Получение полного имени модуля
     *
     * @return string
     */
    public function getFullModuleName()
    {
        return $this->modulePath . '/' . $this->moduleID;
    }

    /**
     * Полный составной путь до модуля
     *
     * @return string
     */
    public function getFullModulePath()
    {
        return \Yii::getAlias('@' . $this->fullModuleName);
    }

    /**
     * Неймспейс модуля
     *
     * @return string
     */
    public function getModuleNamespace()
    {
        return str_replace('/', '\\', $this->fullModuleName);
    }

    /**
     * Generates a class name for Mapper
     *
     * @param string $modelClassName
     * @return string
     */
    protected function generateMapperClassName($modelClassName)
    {
        return $modelClassName . 'Mapper';
    }

    /**
     * Generates a class name for Factory
     *
     * @param string $modelClassName
     * @return string
     */
    protected function generateFactoryClassName($modelClassName)
    {
        return $modelClassName . 'Factory';
    }

    /**
     * Generates a class name for Search
     *
     * @param string $modelClassName
     * @return string
     */
    protected function generateSearchClassName($modelClassName)
    {
        return $modelClassName . 'Search';
    }

    /**
     * Generates a class name for Form
     *
     * @param string $modelClassName
     * @return string
     */
    protected function generateFormClassName($modelClassName)
    {
        return $modelClassName . 'Form';
    }

    /**
     * Generates a class name for Repository
     *
     * @param string $modelClassName
     * @return string
     */
    protected function generateRepositoryClassName($modelClassName)
    {
        return $modelClassName . 'Repository';
    }

    /**
     * Generates a class name for Service
     *
     * @param string $modelClassName
     * @return string
     */
    protected function generateServiceClassName($modelClassName)
    {
        return $modelClassName . 'Service';
    }

    /**
     * Generates a class name for Bootstrap
     *
     * @param string $modelClassName
     * @return string
     */
    protected function generateBootstrapClassName($modelClassName)
    {
        return $modelClassName . 'Bootstrap';
    }
}
