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

use chulakov\gii\helpers\TranslationsHelper;
use yii\helpers\Inflector;

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
use chulakov\components\widgets\BoxWidget;

$this->title = Yii::t('ch/<?= $generator->moduleID; ?>', <?= $generator->generateString('Create ' . strtolower(Inflector::pluralize(Inflector::camel2words($generator->modelClass, false)))); ?>);
$this->params['breadcrumbs'][] = ['label' => Yii::t('ch/<?= $generator->moduleID; ?>', <?= $generator->generateString(TranslationsHelper::formatTitle(Inflector::pluralize(Inflector::camel2words($generator->modelClass)))); ?>), 'url' => ['index']];

?>

<?= '<?php'; ?> $form = ActiveForm::begin(); ?>
    <?= '<?='; ?> BoxWidget::begin(['title' => Yii::t('ch/<?= $generator->moduleID; ?>', '<?= ucfirst(strtolower(Inflector::camel2words($generator->modelClass))); ?> form')]); ?>
        <?= '<?='; ?> $this->render('_form', [
            'form' => $form,
            'model' => $model,
        ]); ?>
    <?= '<?='; ?> BoxWidget::end(); ?>
<?= '<?php'; ?> ActiveForm::end(); ?>
