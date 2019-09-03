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

use yii\helpers\Inflector;
use chulakov\gii\helpers\TranslationsHelper;

echo "<?php\n";
?>
/**
 * Файл шаблона create
 *
 * @copyright Copyright (c) <?= date('Y'); ?>, Oleg Chulakov Studio
 * @link http://chulakov.com/
 *
 * @var \yii\web\View $this
 * @var \<?= $generator->moduleNamespace; ?>\models\forms\<?= $formModelClass; ?> $model
 */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('ch/<?= $generator->moduleID; ?>', <?= $generator->generateString('Create ' . strtolower(Inflector::pluralize(Inflector::camel2words($generator->modelClass, false)))); ?>);
$this->params['breadcrumbs'][] = ['label' => Yii::t('ch/<?= $generator->moduleID; ?>', <?= $generator->generateString(TranslationsHelper::formatTitle(Inflector::pluralize(Inflector::camel2words($generator->modelClass)))); ?>), 'url' => ['index']];

?>

<?= '<?php'; ?> $form = ActiveForm::begin(); ?>
    <div class="form">
        <h3 class="form-title"><?= '<?='; ?> Yii::t('ch/<?= $generator->moduleID; ?>', '<?= ucfirst(strtolower(Inflector::camel2words($generator->modelClass))); ?> form'); ?></h3>
        <?= '<?='; ?> $this->render('_form', [
            'form' => $form,
            'model' => $model,
        ]); ?>
        <div class="footer">
            <button type="submit" class="btn btn-success">
                <i class="fa fa-save"></i> <?= '<?='; ?> Yii::t('ch/all', 'Create'); ?>
            </button>
            <button type="submit" name="refresh" value="1" class="btn btn-success">
                <i class="fa fa-save"></i> <?= '<?='; ?> Yii::t('ch/all', 'Create and continue'); ?>
            </button>
            <a class="btn btn-danger" href="<?= '<?='; ?> Url::to(['index']); ?>">
                <i class="fa fa-ban"></i> <?= '<?='; ?> Yii::t('ch/all', 'Cancel'); ?>
            </a>
        </div>
    </div>
<?= '<?php'; ?> ActiveForm::end(); ?>
