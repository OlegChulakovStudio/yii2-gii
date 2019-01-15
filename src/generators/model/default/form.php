<?php
/**
 * This is the template for generating the Form class.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\model\Generator
 * @var $className string class name
 * @var $properties array
 */

use chulakov\gii\helpers\ModuleHelper;

echo ModuleHelper::copyright('Файл класса ' . $className);
?>
namespace <?= $generator->moduleNamespace; ?>\models\forms;

use chulakov\components\models\forms\Form;

class <?= $className; ?> extends Form
{
<?php foreach ($properties as $data) : ?>
    /**
     * @var <?= "{$data['type']}\n"; ?>
     */
    public $<?= "{$data['name']}"; ?>;
<?php endforeach; ?>
}
