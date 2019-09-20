<?php
/**
 * This is the template for generating a CRUD controller class file.
 *
 * @var $this yii\web\View
 * @var $generator chulakov\gii\generators\crud\Generator
 * @var $actionAccess string
 * @var $bootstrapClassName string
 * @var $properties array
 */

use chulakov\gii\helpers\ModuleHelper;

echo ModuleHelper::copyright('Файл контроллера ' . $generator->controllerClass);
?>
namespace <?= $generator->moduleNamespace; ?>\controllers;

use Yii;
use yii\base\Module;
<?php if (isset($properties['sort'])) : ?>
use sem\sortable\enums\MoveDirection;
<?php endif; ?>
use chulakov\web\Controller;
use <?= $generator->moduleNamespace; ?>\bootstrap\<?= $bootstrapClassName; ?>;
use <?= $generator->moduleNamespace; ?>\models\<?= $generator->modelClass; ?>;

class <?= $generator->controllerClass; ?> extends Controller
{
    /**
     * Конструктор контроллера
     *
     * @param string $id
     * @param Module $module
     * @param <?= $bootstrapClassName; ?> $bootstrap
     * @param array $config
     */
    public function __construct($id, Module $module, <?= $bootstrapClassName; ?> $bootstrap, array $config = [])
    {
        $bootstrap->bootstrap(Yii::$app);
        parent::__construct($id, $module, $config);
    }

    /**
     * @inheritdoc
     */
    public function accessRules()
    {
        return [
            'index'  => $this->createAccess('get', true, '<?= $actionAccess; ?>'),
            'view'   => $this->createAccess('get', true, '<?= $actionAccess; ?>'),
            'create' => $this->createAccess('get, post', true, '<?= $actionAccess; ?>'),
            'update' => $this->createAccess('get, post', true, '<?= $actionAccess; ?>'),
            'delete' => $this->createAccess('post', true, '<?= $actionAccess; ?>'),
<?php if (isset($properties['is_active'])) : ?>
            'active' => $this->createAccess('post', true, '<?= $actionAccess; ?>'),
<?php endif; ?>
<?php if (isset($properties['sort'])) : ?>
            'up'     => $this->createAccess('post', true, '<?= $actionAccess; ?>'),
            'down'   => $this->createAccess('post', true, '<?= $actionAccess; ?>'),
<?php if ($generator->enablePjax) : ?>
            'swap'   => $this->createAccess('get, post', true, '<?= $actionAccess; ?>'),
<?php endif; ?>
<?php endif; ?>
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index'  => 'chulakov\web\actions\IndexAction',
            'view'   => 'chulakov\web\actions\ViewAction',
            'create' => 'chulakov\web\actions\CreateAction',
            'update' => 'chulakov\web\actions\UpdateAction',
            'delete' => 'chulakov\web\actions\DeleteAction',
<?php if (isset($properties['is_active'])) : ?>
            'active' => 'chulakov\web\actions\ToggleActivityAction',
<?php endif; ?>
<?php if (isset($properties['sort'])) : ?>
            'up'     => [
                'class' => 'sem\sortable\actions\StepMoveAction',
                'modelClass' => <?= $generator->modelClass; ?>::class,
                'direction' => MoveDirection::UP,
            ],
            'down'  => [
                'class' => 'sem\sortable\actions\StepMoveAction',
                'modelClass' => <?= $generator->modelClass; ?>::class,
                'direction' => MoveDirection::DOWN,
            ],
<?php if ($generator->enablePjax) : ?>
            'swap'  => [
                'class' => 'sem\sortable\actions\DragDropMoveAction',
                'modelClass' => <?= $generator->modelClass; ?>::class,
            ],
<?php endif; ?>
<?php endif; ?>
        ];
    }
}
