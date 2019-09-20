<?php
/**
 * This is the template for generating the ActiveQuery class.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\model\Generator
 * @var $className string class name
 * @var $queryTraits array traits classes
 */

use chulakov\gii\helpers\ModuleHelper;

echo ModuleHelper::copyright('Файл класса ' . $className);
?>
namespace <?= $generator->moduleNamespace; ?>\models\scopes;

use chulakov\model\models\scopes\ActiveQuery;
<?php foreach ($queryTraits as $trait) : ?>
use <?= $trait; ?>;
<?php endforeach; ?>

class <?= $className; ?> extends ActiveQuery
{
<?php if (!empty($queryTraits)) : ?>
    use <?= implode(",\n        ", array_keys($queryTraits)); ?>;
<?php endif; ?>
}
