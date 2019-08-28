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

$this->title = Yii::t('ch/<?= $generator->moduleID; ?>', 'View <?= strtolower(Inflector::pluralize(Inflector::camel2words($generator->modelClass, false))); ?>');
$this->params['breadcrumbs'][] = ['label' => Yii::t('ch/<?= $generator->moduleID; ?>', <?= $generator->generateString(ucfirst(strtolower(Inflector::pluralize(Inflector::camel2words($generator->modelClass))))); ?>), 'url' => ['index']];
?>
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title"><?= "<?="; ?> Html::encode($model-><?= $generator->getNameAttribute() ?>); ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>

    <div class="box-body">

        <?= "<?="; ?> DetailView::widget([
            'model' => $model,
            'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "                '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        echo "                '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
    }
}
?>
            ],
        ]); ?>

    </div>

    <div class="box-footer">
        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-warning" href="<?= "<?="; ?> Url::to(['update', <?= $generator->generateUrlParams(); ?>]); ?>">
                    <i class="fa fa-pencil"></i> <?= "<?="; ?> Yii::t('yii', 'Update'); ?>
                </a>
                <a class="btn btn-default" href="<?= "<?="; ?> Url::to(['index']); ?>">
                    <i class="fa fa-arrow-left"></i> <?= "<?="; ?> Yii::t('ch/all', 'Back'); ?>
                </a>
            </div>
        </div>
    </div>
</div>
