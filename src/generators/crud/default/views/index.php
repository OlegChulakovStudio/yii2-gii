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
use \chulakov\gii\helpers\TranslationsHelper;

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

$this->title = Yii::t('ch/<?= $generator->moduleID; ?>', <?=$generator->generateString(TranslationsHelper::formatTitle(Inflector::pluralize(Inflector::camel2words($generator->modelClass))))?>);

?>

<?= "<?="; ?> $this->render('_search', ['model' => $searchModel]); ?>

<div class="grid">
    <h3 class="grid-title"><?= "<?="; ?> Yii::t('ch/all', 'List'); ?></h3>
<?= $generator->enablePjax ? "    <?php Pjax::begin(); ?>\n" : ''; ?>
    <?= "<?php"; ?> $grid = GridView::begin([
        'dataProvider' => $dataProvider,
        'filterSelector' => 'select[name="per-page"]',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
<?php foreach ($properties as $property) : if ($property['name'] == 'sort') {continue;} ?>
<?php if ($property['name'] == 'is_active'): ?>
            [
                'class' => 'chulakov\components\widgets\ToggleColumn',
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
            ],
<?php continue; endif; ?>
<?php if ($property['type'] === 'color'): ?>
            [
                'class' => \chulakov\view\grid\ColorColumn::class,
                'attribute' => '<?= $property['name']; ?>',
            ],
<?php continue; endif; ?>
<?php if ($property['type'] === 'Image'): ?>
            [
                'class' => \chulakov\view\grid\ImageColumn::class,
                'attribute' => '<?= $property['name']; ?>',
            ],
<?php continue; endif; ?>
            '<?= $property['name'] . ($property['type'] === 'text' ? "" : ":" . $property['type']); ?>',
<?php endforeach; ?>
<?php if (isset($properties['sort'])) : ?>
            [
                'class' => 'chulakov\components\widgets\ActionColumn',
                'template' => '{up} {down} {view} {update} {delete}',
            ],
<?php else: ?>
            [
                'class' => 'chulakov\components\widgets\ActionColumn',
            ],
<?php endif; ?>
        ],
        'layout' => '{items}',
        'options' => [
            'class' => 'grid-view table-responsive',
        ],
    ]);
    $grid::end(); ?>
<?= $generator->enablePjax ? "    <?php Pjax::end(); ?>\n" : ''; ?>
    <div class="footer">
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-success" href="<?= "<?="; ?> Url::to(['create']); ?>">
                    <i class="fa fa-plus"></i> <?= "<?="; ?> Yii::t('ch/all', 'Create'); ?>
                </a>
            </div>
            <div class="col-md-6 text-right">
                <?= "<?="; ?> PageSizeWidget::widget([
                    'defaultPageSize' => $grid->dataProvider->getPagination()->defaultPageSize
                ]); ?>
                <?= "<?="; ?> $grid->renderPager(); ?>
            </div>
        </div>
    </div>
</div>
