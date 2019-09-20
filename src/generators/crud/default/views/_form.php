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
<?php echo in_array('color', array_column($properties, 'type')) ? 'use kartik\color\ColorInput;' : ''; ?>
<?php echo in_array('Image', array_column($properties, 'type')) ? 'use chulakov\fileinput\widgets\FileInput;' : ''; ?>

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
<?php if ($attribute['type'] == 'color'): ?>
        <?= "<?=" ?> $form->field($model, '<?= $attribute['name']; ?>')->widget(ColorInput::class, [
            'options' => ['placeholder' => \Yii::t('ch/all', 'Select color...')],
        ]); ?>
<?php elseif ($attribute['type'] == 'Image'): ?>
        <?= "<?=" ?> $form->field($model, '<?= $attribute['name']; ?>')->widget(FileInput::class, [
            'options' => [
                'multiple' => false,
            ],
            'attachedFilesAttribute' => '<?= $attribute['name']; ?>Attached',
            'pluginOptions' => [
                'overwriteInitial' => true,
                'showUpload' => false,
                'showClose' => false,
                'showRemove' => false,
                'fileActionSettings' => [
                    'showRemove' => false,
                ],
            ],
        ]); ?>
<?php else: ?>
        <?= "<?= " . $generator->generateActiveField($attribute['name']) . "; ?>\n"; ?>
<?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
