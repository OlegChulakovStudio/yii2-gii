<?php
/**
 * This is the template for generating a CRUD controller view view file.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\crud\Generator
 * @var $searchModelClass string
 * @var $formModelClass string
 * @var $properties array
 */

use yii\helpers\Inflector;

echo "<?php\n";
?>
/**
 * Файл шаблона view
 *
 * @copyright Copyright (c) <?= date('Y'); ?>, Oleg Chulakov Studio
 * @link http://chulakov.com/
 *
 * @var \yii\web\View $this
 * @var \<?= $generator->moduleNamespace; ?>\models\<?= $generator->modelClass; ?> $model
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Yii::t('ch/<?= $generator->moduleID; ?>', <?= $generator->generateString(Inflector::titleize('View_' . $generator->modelClass)); ?>);
$this->params['breadcrumbs'][] = ['label' => Yii::t('ch/<?= $generator->moduleID; ?>', <?= $generator->generateString(Inflector::pluralize(Inflector::titleize($generator->modelClass))); ?>), 'url' => ['index']];

?>

<div class="box box-solid">

    <div class="box-header with-border">
        <h3 class="box-title"><?= "<?="; ?> Html::encode($model-><?= $generator->getNameAttribute() ?>); ?></h3>
        <div class="box-tools pull-right">
            <a class="btn" href="<?= "<?="; ?> Url::to(['update', 'id' => $model->id]); ?>">
                <i class="fa fa-pen" title="<?= Yii::t('yii', 'Update'); ?>"></i>
            </a>
            <a class="btn" href="<?= "<?="; ?> Url::to(['index']); ?>">
                <i class="fa fa-arrow-left" title="<?= "<?="; ?> Yii::t('ch/all', 'Back'); ?>"></i>
            </a>
        </div>
    </div>

    <div class="box-body">

        <?= "<?="; ?> DetailView::widget([
            'model' => $model,
            'attributes' => [
<?php if (($tableSchema = $generator->getTableSchema()) === false): ?>
<?php foreach ($generator->getColumnNames() as $name): ?>
                '<?= $name; ?>',
<?php endforeach; ?>
<?php else: ?>
<?php foreach ($generator->getTableSchema()->columns as $column): ?>
<?php if ($generator->isColorProperty($column)): ?>
                [
                    'attribute' => '<?= $column->name; ?>',
                    'format' => 'raw',
                    'value' => chulakov\view\grid\ColorColumn::render($model, '<?= $column->name; ?>'),
                ],
<?php continue; endif; ?>
<?php $format = $generator->generateColumnFormat($column); ?>
                '<?= $column->name . ($format === 'text' ? "" : ":" . $format); ?>',
<?php endforeach; ?>
<?php endif; ?>
<?php if ($properties): ?>
<?php foreach ($properties as $property): ?>
<?php if ($generator->isImageProperty($property)): ?>
                [
                    'attribute' => '<?= $property['name']; ?>',
                    'format' => 'raw',
                    'value' => chulakov\view\grid\ImageColumn::render($model, '<?= $property['name']; ?>'),
                ],
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
            ],
        ]); ?>

    </div>
</div>
