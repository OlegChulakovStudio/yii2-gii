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

use yii\helpers\Inflector;

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
<?= $generator->enablePjax ? "use yii\widgets\Pjax;\n" : ''; ?>
use <?= $generator->moduleNamespace; ?>\models\<?= $generator->modelClass; ?>;

$this->title = Yii::t('ch/<?= $generator->moduleID; ?>', <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words($generator->modelClass))); ?>);

?>

<div class="row">
    <div class="col-md-9">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><?= "<?="; ?> Yii::t('ch/all', 'List'); ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body no-padding">
<?= $generator->enablePjax ? "                <?php Pjax::begin(); ?>\n" : ''; ?>
                <?= "<?php"; ?> $grid = GridView::begin([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

<?php
foreach ($properties as $property) :
if ($property['name'] == 'sort') {
    continue;
}
if ($property['name'] == 'is_active') : ?>
                        [
                            'class' => 'chulakov\view\grid\ToggleColumn',
                            'attribute' => '<?= $property['name']; ?>',
                            'value' => function (<?= $generator->modelClass; ?> $model) {
                                return ['active', 'id' => $model->id];
                            }
                        ],
<?php continue; endif;
if ($property['type'] == 'datetime' || in_array($property['name'], ['created_at', 'published_at'])) : ?>
                        [
                            'attribute' => '<?= $property['name']; ?>',
                            'format' => ['date', 'php:d.m.Y H:i'],
                        ],
<?php continue; endif;
echo "                        '" . $property['name'] . ($property['type'] === 'text' ? "" : ":" . $property['type']) . "',\n";
endforeach;
?>
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
                    'layout' => '{items}',
                    'options' => [
                        'class' => 'grid-view table-responsive',
                    ],
                ]);
                $grid::end(); ?>
<?= $generator->enablePjax ? "                <?php Pjax::end(); ?>\n" : ''; ?>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-success" href="<?= "<?="; ?> Url::to(['create']); ?>">
                            <i class="fa fa-plus"></i> <?= "<?="; ?> Yii::t('ch/all', 'Create'); ?>
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <?= "<?="; ?> $grid->renderPager(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <?= "<?="; ?> $this->render('_search', ['model' => $searchModel]); ?>
    </div>
</div>
