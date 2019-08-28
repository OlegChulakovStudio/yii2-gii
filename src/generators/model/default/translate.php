<?php
/**
 * This is the template for generating a translate file.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\model\Generator
 * @var $className string
 */

use yii\helpers\Inflector;

$upName = Inflector::pluralize(Inflector::camel2words($className));
$downName = strtolower(Inflector::pluralize(Inflector::camel2words($className, false)));

echo "<?php\n";
?>
return [
    '<?= $upName; ?>' => '<?= $upName; ?>',
    'Create <?= $downName; ?>' => 'Добавить <?= $downName; ?>',
    'Update <?= $downName; ?>' => 'Редактирование <?= $downName; ?>',
    'View <?= $downName; ?>' => 'Детализация <?= $downName; ?>',
    '<?= $upName; ?> form' => '<?= $upName; ?> форма',
];
