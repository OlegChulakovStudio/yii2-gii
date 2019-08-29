<?php
/**
 * This is the template for generating a CRUD controller view create file.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\crud\Generator
 * @var $searchModelClass string
 * @var $formModelClass string
 * @var $properties array
 */

echo "<?php\n";
?>
/**
 * Файл шаблона формы
 *
 * @copyright Copyright (c) <?= date('Y'); ?>, Oleg Chulakov Studio
 * @link http://chulakov.com/
 *
 * @var \yii\web\View $this
 * @var \<?= $generator->moduleNamespace; ?>\models\forms\<?= $formModelClass; ?> $model
 * @var \yii\widgets\ActiveForm $form
 */

use yii\helpers\Html;

?>

<?= "<?="; ?> $form->errorSummary($model, [
    'class' => 'alert alert-error'
]); ?>

<?php foreach ($properties as $attribute) :
    if (in_array($attribute['name'], ['sort', 'created_at'])) {
        continue;
    }
?>
<div class="row">
    <div class="col-md-12">
        <?= "<?= " . $generator->generateActiveField($attribute['name']) . "; ?>\n"; ?>
    </div>
</div>
<?php endforeach; ?>
