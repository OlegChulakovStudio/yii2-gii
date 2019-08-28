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

echo "<?php\n";
?>
/**
 * Файл шаблона поиска
 *
 * @copyright Copyright (c) <?= date('Y'); ?>, Oleg Chulakov Studio
 * @link http://chulakov.com/
 *
 * @var \yii\web\View $this
 * @var \<?= $generator->moduleNamespace; ?>\models\search\<?= $searchModelClass; ?> $searchModel
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="box box-primary collapsed-box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= "<?=" ?> \Yii::t('ch/all', 'Filters'); ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <?= "<?php" ?> $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
<?php if ($generator->enablePjax) : ?>
            'options' => [
                'data-pjax' => 1
            ],
<?php endif; ?>
        ]); ?>

<?php
foreach ($properties as $attribute) :
    if (!in_array($attribute['name'], ['is_active', 'title', 'name'])) {
        continue;
    }
?>
        <div class="row">
            <div class="col-md-12">
                <?= "<?=" ?> $form->field($model, '<?= $attribute['name']; ?>', [
                    'labelOptions' => [
                        'class' => 'control-label sr-only',
                    ],
                ])<?= $generator->generateActiveSearchField($attribute['name'], "\n                "); ?> ?>
            </div>
        </div>
<?php endforeach; ?>

        <?= "<?php" ?> ActiveForm::end(); ?>

    </div>

    <div class="box-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-search"></i> <?= "<?=" ?> \Yii::t('ch/all', 'Search'); ?>
        </button>
        <a class="btn btn-default" href="<?= "<?=" ?> Url::to(['index']); ?>">
            <i class="fa fa-times"></i> <?= "<?=" ?> \Yii::t('ch/all', 'Reset'); ?>
        </a>
    </div>
</div>
