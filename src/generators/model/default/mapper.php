<?php
/**
 * This is the template for generating the Mapper class.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\model\Generator
 * @var $className string class name
 * @var $modelClassName string class name
 * @var $properties array
 * @var $fields array
 * @var $labels array
 * @var $rules array
 */

use chulakov\gii\helpers\ModuleHelper;
use chulakov\gii\helpers\TranslationsHelper;

echo ModuleHelper::copyright('Файл класса ' . $className);
?>
namespace <?= $generator->moduleNamespace; ?>\models\mappers;

use Yii;
use chulakov\base\traits\SingletonTrait;
use chulakov\model\models\mappers\Mapper;
<?php if ($generator->imageProperties): ?>
use chulakov\filestorage\validators\FileValidator;
<?php endif; ?>
use chulakov\model\models\mappers\types\NullType;
use chulakov\model\models\mappers\types\ModelType;
use <?= $generator->moduleNamespace; ?>\models\<?= $modelClassName; ?>;
<?php if ($generator->imageProperties): ?>
use common\models\enums\File;
<?php endif; ?>

class <?= $className; ?> extends Mapper
{
    use SingletonTrait;

    /**
     * @inheritdoc
     */
    public function initFillAttributes()
    {
        return [<?= empty($fields) ? '' : '\'' . implode('\', \'', $fields) . '\''; ?>];
    }

    /**
     * @inheritdoc
     */
    public function initAcceptedModelTypes()
    {
        return [
            new NullType(),
            new ModelType(<?= $modelClassName; ?>::class),
        ];
    }

    /**
     * @inheritdoc
     */
    public function initModelRules()
    {
        return [<?= empty($rules) ? '' : ("\n            " . implode(",\n            ", $rules) . ",\n        "); ?>];
    }
<?php if ($generator->imageProperties): ?>

    /**
     * @inheritdoc
     */
    public function initFormRules()
    {
        return [
            [<?= str_replace(["\n", "\t", ' '], '', yii\helpers\VarDumper::export($generator->imageProperties)); ?>, 'file', 'maxSize' => File::MAX_SIZE],
<?php foreach ($properties as $property): ?>
<?php if ($property['type'] == 'Image'): ?>
            [['<?= $property['name']; ?>'], FileValidator::class, 'strict' => true, 'targetAttribute' => '<?= $property['name']; ?>Attached'],
<?php endif; ?>
<?php endforeach; ?>
        ];
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function initModelLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            '<?= $name; ?>' => Yii::t('<?= TranslationsHelper::getTranslatePath($label, $generator->moduleID); ?>', <?= $generator->generateString($label); ?>),
<?php endforeach; ?>
        ];
    }
<?php if ($generator->imageProperties): ?>

    /**
     * @inheritDoc
     */
    public function initFormLabels()
    {
        return array_merge(parent::initFormLabels(), [
<?php foreach ($properties as $property): ?>
<?php if ($property['type'] == 'Image'): ?>
            '<?= $property['name']; ?>' => Yii::t('<?= TranslationsHelper::getTranslatePath($label, $generator->moduleID); ?>', '<?= TranslationsHelper::formatTitle($property['name']); ?>'),
<?php endif; ?>
<?php endforeach; ?>
        ]);
    }
<?php endif; ?>
}
