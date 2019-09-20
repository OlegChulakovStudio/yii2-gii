<?php
/**
 * This is the template for generating a CRUD controller view index file.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\crud\Generator
 * @var $searchModelClass string
 * @var $formModelClass string
 * @var $properties array
 */

use yii\helpers\Url;
use yii\helpers\Inflector;
use chulakov\gii\helpers\TranslationsHelper;

echo "<?php\n";
?>
/**
 * Файл шаблона index
 *
 * @copyright Copyright (c) <?= date('Y'); ?>, Oleg Chulakov Studio
 * @link http://chulakov.com/
 *
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \<?= $generator->moduleNamespace; ?>\models\search\<?= $searchModelClass; ?> $searchModel
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use chulakov\view\widgets\PageSizeWidget;
<?= $generator->enablePjax ? "use yii\widgets\Pjax;\n" : ''; ?>
use <?= $generator->moduleNamespace; ?>\models\<?= $generator->modelClass; ?>;

$this->title = Yii::t('ch/<?= $generator->moduleID; ?>', <?= $generator->generateString(Inflector::pluralize(Inflector::titleize($generator->modelClass))); ?>);

?>

<div class="box box-solid">

    <div class="box-header with-border">
        <h3 class="box-title"><?= "<?="; ?> Yii::t('ch/all', 'List'); ?></h3>

        <div class="box-tools pull-right">
            <a class="btn btn-success" href="<?= "<?="; ?> Url::to(['create']); ?>">
                <i class="fa fa-plus" title="<?= "<?="; ?> Yii::t('ch/all', 'Create'); ?>"></i>
            </a>
        </div>

    </div>

    <div class="box-body">

<?= $generator->enablePjax ? "    <?php Pjax::begin(); ?>\n" : ''; ?>
        <?= "<?="; ?> GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'filterSelector' => 'select[name="per-page"]', // '#search-filters input'
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
<?php foreach ($properties as $property) : if ($property['name'] == 'sort') { continue; } ?>
<?php if ($property['name'] == 'is_active'): ?>
                [
                    'class' => 'chulakov\view\grid\ToggleColumn',
                    'attribute' => '<?= $property['name']; ?>',
                    'value' => function (<?= $generator->modelClass; ?> $model) {
                        return ['active', 'id' => $model->id];
                    }
                ],
<?php continue; endif; ?>
<?php if ($property['type'] == 'datetime' || in_array($property['name'], ['created_at', 'published_at'])) : ?>
                [
                    'attribute' => '<?= $property['name']; ?>',
                    'format' => ['date', 'php:d.m.Y H:i'],
                    'filter' => false,
                ],
<?php continue; endif; ?>
<?php if ($property['type'] === 'color'): ?>
                [
                    'class' => 'chulakov\view\grid\ColorColumn',
                    'attribute' => '<?= $property['name']; ?>',
                ],
<?php continue; endif; ?>
<?php if ($property['type'] === 'Image'): ?>
                [
                    'class' => 'chulakov\view\grid\ImageColumn',
                    'attribute' => '<?= $property['name']; ?>',
                ],
<?php continue; endif; ?>
                [
                    'attribute' => '<?= $property['name']; ?>',
<?php if ($property['type'] != 'text'): ?>
                    'format' => '<?= $property['type']; ?>',
<?php endif; ?>
                    'filterInputOptions' => [
                        'id' => null,
                        'class' => 'form-control',
                        'placeholder' => true,
                    ],
                ],
<?php endforeach; ?>
<?php if (isset($properties['sort'])) : ?>
                [
                    'class' => 'chulakov\view\grid\ActionColumn',
                    'template' => '{up} {down} {view} {update} {delete}',
                ],
<?php else: ?>
                [
                    'class' => 'chulakov\view\grid\ActionColumn',
                ],
<?php endif; ?>
            ],
            'options' => [
                'class' => 'grid-view table-responsive',
            ],
        ]); ?>
<?= $generator->enablePjax ? "    <?php Pjax::end(); ?>\n" : ''; ?>

        <?= "<?php"; ?> if ($paginator = $dataProvider->getPagination()) : ?>
        <div class="paginator">
            <?= "<?="; ?> PageSizeWidget::widget([
                'pagination' => $paginator,
            ]); ?>
        </div>
        <?= "<?php"; ?> endif; ?>

    </div>

</div>
