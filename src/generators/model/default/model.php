<?php
/**
 * This is the template for generating the model class of a specified table.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\model\Generator
 *
 * @var $tableName string full table name
 * @var $className string class name
 * @var $queryClassName string query class name
 * @var $mapperClassName string mapper class name
 * @var $properties array list of properties (property => [type, name. comment])
 * @var $behaviors array list behaviors
 * @var $relations array list of relations (name => relation declaration)
 */

use chulakov\gii\helpers\ModuleHelper;

echo ModuleHelper::copyright('Файл модели ' . $className);
?>
namespace <?= $generator->moduleNamespace; ?>\models;

use Yii;
<?= !empty($relations) ? "use yii\db\ActiveQuery;\n" : ''; ?>
<?php foreach ($behaviors as $behavior) : ?>
use <?= $behavior['namespace']; ?>\<?= $behavior['class']; ?>;
<?php endforeach; ?>
use chulakov\components\models\ActiveRecord;
use <?= $generator->moduleNamespace; ?>\models\scopes\<?= $queryClassName; ?>;
use <?= $generator->moduleNamespace; ?>\models\mappers\<?= $mapperClassName; ?>;

/**
 * Класс модели для работы с данными таблицы "<?= $tableName; ?>".
 *
<?php foreach ($properties as $data) : ?>
 * @property <?= "{$data['type']} \${$data['name']}\n"; ?>
<?php endforeach; ?>
<?php if (!empty($relations)) : ?>
 *
<?php foreach ($relations as $name => $relation) : ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n"; ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className; ?> extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db') : ?>

    /**
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db; ?>');
    }
<?php endif; ?>
<?php if (!empty($behaviors)) : ?>

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
<?php foreach ($behaviors as $behavior) : ?>
            <?= $behavior['class']; ?>::class,
<?php endforeach; ?>
        ];
    }
<?php endif; ?>
<?php foreach ($relations as $name => $relation) : ?>

    /**
     * @return ActiveQuery
     */
    public function get<?= $name; ?>()
    {
        <?= $relation[0] . "\n"; ?>
    }
<?php endforeach; ?>

    /**
     * @return <?= $queryClassName . "\n"; ?>
     */
    public static function find()
    {
        return new <?= $queryClassName; ?>(get_called_class());
    }

    /**
     * @return <?= $mapperClassName . "\n"; ?>
     */
    public static function mapper()
    {
        return <?= $mapperClassName; ?>::instance();
    }
}
