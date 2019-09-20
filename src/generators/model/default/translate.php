<?php
/**
 * This is the template for generating a translate file.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\model\Generator
 * @var $className string
 */

use yii\helpers\Inflector;

$name = Inflector::titleize($className);
$upName = Inflector::pluralize(Inflector::titleize($className));
$downName = Inflector::camel2words($className, false);

echo "<?php\n";
?>
return [
    '<?= $name; ?>' => '<?= $className; ?>',
    '<?= $upName; ?>' => '<?= $upName; ?>',
    'Create <?= $downName; ?>' => 'Добавить <?= $downName; ?>',
    'Update <?= $downName; ?>' => 'Редактирование <?= $downName; ?>',
    'View <?= $downName; ?>' => 'Детализация <?= $downName; ?>',
    '<?= $name; ?> form' => '<?= $name; ?> форма',
];
