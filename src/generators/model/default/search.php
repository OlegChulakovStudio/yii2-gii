<?php
/**
 * This is the template for generating the SearchForm class.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\model\Generator
 * @var $className string class name
 * @var $modelClassName string
 * @var $properties array
 * @var $rules array
 * @var $apply array
 * @var $sort array
 */

use chulakov\gii\helpers\ModuleHelper;
use chulakov\gii\helpers\TranslationsHelper;

echo ModuleHelper::copyright('Файл класса ' . $className);
?>
namespace <?= $generator->moduleNamespace; ?>\models\search;

use Yii;
use yii\db\ActiveQuery;
use chulakov\model\models\search\SearchForm;
use <?= $generator->moduleNamespace; ?>\models\<?= $modelClassName; ?>;

class <?= $className; ?> extends SearchForm
{
<?php foreach ($properties as $data) : ?>
    /**
     * @var <?= "{$data['type']}\n"; ?>
     */
    public $<?= "{$data['name']}"; ?>;
<?php endforeach; ?>
<?php if (!empty($apply)) : ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= empty($rules) ? '' : ("\n            " . implode(",\n            ", $rules) . ",\n        "); ?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($properties as $data) : $label = empty($data['comment']) ? $data['name'] : "'{$data['comment']}'"; ?>
            '<?= $data['name']; ?>' => Yii::t('<?= TranslationsHelper::getTranslatePath($label, $generator->moduleID); ?>', '<?= TranslationsHelper::formatTitle($label);?>'),
<?php endforeach; ?>
        ];
    }

    /**
     * @inheritdoc
     */
    protected function applyFilter(ActiveQuery $query)
    {
        $query
            <?= implode("\n            ", array_map(function($item) { return "->andFilterWhere({$item})"; }, $apply)); ?>;
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    protected function buildSort()
    {
        return [
           'defaultOrder' => [
               '<?= $sort['main']; ?>' => SORT_ASC,
           ],
           'attributes' => ['<?= implode('\', \'', $sort['list']); ?>'],
        ];
    }

    /**
     * @inheritdoc
     */
    protected function buildQuery()
    {
        return <?= $modelClassName; ?>::find();
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return '';
    }
}
