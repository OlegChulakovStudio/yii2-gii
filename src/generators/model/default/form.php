<?php
/**
 * This is the template for generating the Form class.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\model\Generator
 * @var $className string class name
 * @var $properties array
 */

use chulakov\gii\helpers\ModuleHelper;

echo ModuleHelper::copyright('Файл класса ' . $className);
?>
namespace <?= $generator->moduleNamespace; ?>\models\forms;

use chulakov\model\models\forms\Form;
<?php if ($generator->imageProperties): ?>
use <?= $generator->moduleNamespace; ?>\models\<?= $modelClassName; ?>;
use chulakov\filestorage\models\Image;
use chulakov\fileinput\behaviors\FileModelBehavior;
use chulakov\components\behaviors\FileOwnerBehavior;
use chulakov\filestorage\behaviors\FileUploadBehavior;
<?php endif; ?>

<?php if ($generator->imageProperties): ?>
/**
 * Класс формы модели <?= $modelClassName . "\n"; ?>
 *
 * @mixin FileUploadBehavior
 * @mixin FileOwnerBehavior
 * @mixin FileModelBehavior
 */
<?php endif; ?>
class <?= $className; ?> extends Form
{
<?php foreach ($properties as $data) : ?>
<?php if ($data['type'] == 'Image'):?>
    /**
     * @var <?= "{$data['type']}\n"; ?>
     */
    public $<?= "{$data['name']}"; ?>;
    /**
     * @var <?= "{$data['type']}\n"; ?>
     */
    public $<?= "{$data['name']}"; ?>Attached;
<?php else: ?>
    /**
     * @var <?= "{$data['type']}\n"; ?>
     */
    public $<?= "{$data['name']}"; ?>;
<?php endif; ?>
<?php endforeach; ?>

<?php if ($generator->imageProperties): ?>
    /**
     * @var <?=$modelClassName . "\n"; ?>
     */
    protected $model;
<?php endif; ?>

<?php if ($generator->imageProperties): ?>
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => FileModelBehavior::class,
            ],
            [
                'class' => FileOwnerBehavior::class,
                'fileOwner' => $this->model,
            ],
<?php foreach ($properties as $property): ?>
<?php if ($property['type'] == 'Image'): ?>
            [
                'class' => FileUploadBehavior::class,
                'group' => <?= $modelClassName; ?>::IMAGE_GROUP,
                'type' => <?= $modelClassName; ?>::IMAGE_GROUP_<?= mb_strtoupper($property['name']); ?>,
                'attribute' => '<?= mb_strtolower($property['name']); ?>',
                'skipOnEmpty' => true,
            ],
<?php endif; ?>
<?php endforeach; ?>
        ];
    }

    /**
     * @inheritDoc
     */
    protected function loadDependency()
    {
<?php foreach ($properties as $property): ?>
<?php if ($property['type'] == 'Image'): ?>
        $this-><?= mb_strtolower($property['name']); ?>Attached = $this->model-><?= mb_strtolower($property['name']); ?>;
<?php endif; ?>
<?php endforeach; ?>
    }
<?php endif; ?>
}
