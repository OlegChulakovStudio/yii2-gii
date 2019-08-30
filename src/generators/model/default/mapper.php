<?php
/**
 * This is the template for generating the Mapper class.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\model\Generator
 * @var $className string class name
 * @var $modelClassName string class name
 * @var $fields array
 * @var $labels array
 * @var $rules array
 */

use chulakov\gii\helpers\ModuleHelper;

echo ModuleHelper::copyright('Файл класса ' . $className);
?>
namespace <?= $generator->moduleNamespace; ?>\models\mappers;

use Yii;
use chulakov\base\traits\SingletonTrait;
use chulakov\model\models\mappers\Mapper;
use chulakov\model\models\mappers\types\NullType;
use chulakov\model\models\mappers\types\ModelType;
use <?= $generator->moduleNamespace; ?>\models\<?= $modelClassName; ?>;

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

    /**
     * @inheritdoc
     */
    public function initModelLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => Yii::t('ch/{$generator->moduleID}', " . $generator->generateString($label) . "),\n" ?>
<?php endforeach; ?>
        ];
    }
}
