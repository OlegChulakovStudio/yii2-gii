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
use chulakov\view\widgets\BoxFilterWidget;

?>

<?= "<?php" ?> $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]); ?>
    <?= "<?php" ?> BoxFilterWidget::begin([
        'collapsed' => empty($model->title)
    ]); ?>
<?php foreach ($properties as $attribute) : if (!in_array($attribute['name'], ['is_active', 'title', 'name'])) {continue;} ?>
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
    <?= "<?php" ?> BoxFilterWidget::end(); ?>
<?= "<?php" ?> ActiveForm::end(); ?>
