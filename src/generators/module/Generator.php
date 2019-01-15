<?php
/**
 * Файл класса Generator
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\gii\generators\module;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\Html;
use chulakov\gii\helpers\ModuleGeneratorTrait;

/**
 * This generator will generate the skeleton code needed by a module.
 *
 * @property string $controllerNamespace The controller namespace of the module. This property is read-only.
 */
class Generator extends \yii\gii\Generator
{
    use ModuleGeneratorTrait;

    public $moduleClass = 'Module';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Module Generator';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'This generator helps you to generate the skeleton code needed by a Yii module.';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['moduleID', 'moduleClass', 'modulePath'], 'filter', 'filter' => 'trim'],
            [['moduleID', 'moduleClass', 'modulePath'], 'required'],
            [['moduleID'], 'match', 'pattern' => '/^[\w\\-]+$/', 'message' => 'Only word characters and dashes are allowed.'],
            [['moduleClass'], 'match', 'pattern' => '/^[\w]*$/', 'message' => 'Only word characters are allowed.'],
            [['modulePath'], 'match', 'pattern' => '/^[\w\/]*$/', 'message' => 'Only word characters and slashes are allowed.'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'moduleID' => 'Module ID',
            'moduleClass' => 'Module Class',
            'modulePath' => 'Modules location path',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function hints()
    {
        return [
            'moduleID' => 'This refers to the ID of the module, e.g., <code>page</code>.',
            'moduleClass' => 'This is the class name of the module, e.g., <code>Module</code>.',
            'modulePath' => 'This is the relation path to the modules location, e.g., <code>common/modules</code>.',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function successMessage()
    {
        if (Yii::$app->hasModule($this->moduleID)) {
            $link = Html::a('try it now', Yii::$app->getUrlManager()->createUrl($this->moduleID), ['target' => '_blank']);

            return "The module has been generated successfully. You may $link.";
        }

        $output = <<<EOD
<p>The module has been generated successfully.</p>
<p>To access the module, you need to add this to your application configuration:</p>
EOD;
        $code = <<<EOD
<?php
    ......
    'modules' => [
        '{$this->moduleID}' => '{$this->moduleClass}',
    ],
    ......
EOD;

        return $output . '<pre>' . highlight_string($code, true) . '</pre>';
    }

    /**
     * {@inheritdoc}
     */
    public function requiredTemplates()
    {
        return ['module.php'];
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $files = [];

        $files[] = new CodeFile(
            $this->fullModulePath . '/' . $this->moduleClass . '.php',
            $this->render('module.php')
        );

        return $files;
    }
}
