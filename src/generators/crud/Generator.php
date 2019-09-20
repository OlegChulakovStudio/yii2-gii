<?php
/**
 * Файл класса Generator
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\gii\generators\crud;

use Yii;
use yii\gii\CodeFile;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use chulakov\gii\helpers\ModuleGeneratorTrait;

/**
 * Generates CRUD
 */
class Generator extends \yii\gii\generators\crud\Generator
{
    use ModuleGeneratorTrait;

    const IMAGE_TYPE_KEY = 'Image';
    const COLOR_TYPE_KEY = 'color';

    public $modelClass;
    public $controllerClass = 'DefaultController';
    public $controllerAccess = '@';
    public $searchModelClass;
    public $viewPath;
    public $enablePjax = false;
    public $enableI18N = false;
    public $imageProperties = false;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'CRUD Generator';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete)
            operations for the specified data model.';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['template'], 'required', 'message' => 'A code template must be selected.'],
            [['template'], 'validateTemplate'],

            [['controllerClass', 'modelClass', 'searchModelClass', 'moduleID', 'modulePath', 'imageProperties'], 'filter', 'filter' => 'trim'],
            [['modelClass', 'controllerClass', 'moduleID', 'modulePath'], 'required'],


            [['moduleID'], 'match', 'pattern' => '/^[\w\\-]+$/', 'message' => 'Only word characters and dashes are allowed.'],
            [['modulePath'], 'match', 'pattern' => '/^[\w\/]*$/', 'message' => 'Only word characters and slashes are allowed.'],
            [['controllerClass'], 'match', 'pattern' => '/Controller$/', 'message' => 'Controller class name must be suffixed with "Controller".'],
            [['controllerClass'], 'match', 'pattern' => '/(^|\\\\)[A-Z][^\\\\]+Controller$/', 'message' => 'Controller class name must start with an uppercase letter.'],
            [['modelClass', 'controllerClass', 'searchModelClass'], 'match', 'pattern' => '/^\w+$/', 'message' => 'Only word characters are allowed.'],

            [['searchModelClass'], 'compare', 'compareAttribute' => 'modelClass', 'operator' => '!==', 'message' => 'Search Model Class must not be equal to Model Class.'],
            // [['modelClass'], 'validateModelClass'],
            [['enablePjax'], 'boolean'],
            ['viewPath', 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'moduleID' => 'Module ID',
            'modulePath' => 'Modules location path',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'moduleID' => 'This refers to the ID of the module, e.g., <code>page</code>.',
            'modulePath' => 'This is the relation path to the modules location, e.g., <code>common/modules</code>.',
            'modelClass' => 'This is the ActiveRecord class associated with the table that CRUD will be built upon.
                You should provide a class name, e.g., <code>Page</code>.',
            'controllerClass' => 'This is the name of the controller class to be generated. You should
                provide a class name, e.g. <code>PageController</code>.',
            'searchModelClass' => 'This is the name of the search model class to be generated. You should provide a 
                class name, e.g., <code>PageSearch</code>.',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function requiredTemplates()
    {
        return ['controller.php'];
    }

    /**
     * {@inheritdoc}
     */
    public function stickyAttributes()
    {
        return ['template', 'enableI18N', 'messageCategory'];
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $files = [];

        $properties = $this->getGridProperties();

        $modelClassName = $this->modelClass;
        $bootstrapClassName = $this->generateBootstrapClassName($modelClassName);
        $mapperClassName = $this->generateMapperClassName($modelClassName);
        $factoryClassName = $this->generateFactoryClassName($modelClassName);
        $searchClassName = $this->generateSearchClassName($modelClassName);
        $formClassName = $this->generateFormClassName($modelClassName);
        $repositoryClassName = $this->generateRepositoryClassName($modelClassName);
        $serviceClassName = $this->generateServiceClassName($modelClassName);

        $files[] = new CodeFile(
            $this->fullModulePath . '/bootstrap/' . $bootstrapClassName . '.php',
            $this->render('bootstrap.php', [
                'className' => $bootstrapClassName,
                'mapperClassName' => $mapperClassName,
                'factoryClassName' => $factoryClassName,
                'searchClassName' => $searchClassName,
                'formClassName' => $formClassName,
                'repositoryClassName' => $repositoryClassName,
                'serviceClassName' => $serviceClassName,
            ])
        );

        $files[] = new CodeFile(
            $this->fullModulePath . '/controllers/' . $this->controllerClass . '.php',
            $this->render('controller.php', [
                'actionAccess' => '@',
                'properties' => $properties,
                'bootstrapClassName' => $bootstrapClassName,
            ])
        );

        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath() . '/views';
        foreach (scandir($templatePath) as $file) {
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile(
                    "$viewPath/$file",
                    $this->render("views/$file", [
                        'searchModelClass' => $searchClassName,
                        'formModelClass' => $formClassName,
                        'properties' => $properties,
                    ])
                );
            }
        }

        return $files;
    }

    /**
     * @return string
     */
    public function getViewPath()
    {
        if (empty($this->viewPath)) {
            return $this->fullModulePath . '/views/' . $this->getControllerID();
        }
        return Yii::getAlias(str_replace('\\', '/', $this->viewPath));
    }

    /**
     * @return string the controller ID (without the module ID prefix)
     */
    public function getControllerID()
    {
        $class = substr($this->controllerClass, 0, -10);
        return Inflector::camel2id($class);
    }

    /**
     * Generates URL parameters
     * @return string
     */
    public function generateUrlParams()
    {
        /* @var $class ActiveRecord */
        $class = $this->getFullModelClass();
        $pks = $class::primaryKey();
        if (count($pks) === 1) {
            if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                return "'id' => (string)\$model->{$pks[0]}";
            }

            return "'id' => \$model->{$pks[0]}";
        }

        $params = [];
        foreach ($pks as $pk) {
            if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                $params[] = "'$pk' => (string)\$model->$pk";
            } else {
                $params[] = "'$pk' => \$model->$pk";
            }
        }

        return implode(', ', $params);
    }

    /**
     * @return array model column names
     */
    public function getColumnNames()
    {
        /* @var $class ActiveRecord */
        $class = $this->getFullModelClass();
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema()->getColumnNames();
        }

        /* @var $model \yii\base\Model */
        $model = new $class();

        return $model->attributes();
    }

    /**
     * Returns table schema for current model class or false if it is not an active record
     * @return bool|\yii\db\TableSchema
     */
    public function getTableSchema()
    {
        /* @var $class ActiveRecord */
        $class = $this->getFullModelClass();
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema();
        } else {
            return false;
        }
    }

    /**
     * Generates code for active field
     * @param string $attribute
     * @return string
     */
    public function generateActiveField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                return "\$form->field(\$model, '$attribute')->passwordInput()";
            }

            return "\$form->field(\$model, '$attribute')->textInput();";
        }
        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean') {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        }

        if ($column->type === 'text') {
            return "\$form->field(\$model, '$attribute')->textarea(['rows' => 6])";
        }

        if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
            $input = 'passwordInput';
        } else {
            $input = 'textInput';
        }

        if (is_array($column->enumValues) && count($column->enumValues) > 0) {
            $dropDownOptions = [];
            foreach ($column->enumValues as $enumValue) {
                $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
            }
            return "\$form->field(\$model, '$attribute')->dropDownList("
                . preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)).", ['prompt' => ''])";
        }

        if ($column->phpType !== 'string' || $column->size === null) {
            return "\$form->field(\$model, '$attribute')->$input()";
        }

        return "\$form->field(\$model, '$attribute')->$input(['maxlength' => true])";
    }

    /**
     * Generates code for active search field
     *
     * @param string $attribute
     * @param string $margin
     * @return string
     */
    public function generateActiveSearchField($attribute, $margin = "\n")
    {
        if ($attribute == 'is_active') {
            return "->dropDownList([{$margin}    '' => 'Любой статус активности',{$margin}    '0' => 'Только не активные',{$margin}    '1' => 'Только активные',{$margin}])";
        }
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false) {
            return "->textInput([{$margin}    'placeholder' => \$model->getAttributeLabel('{$attribute}'),{$margin}]);";
        }

        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean') {
            return "->checkbox()";
        }

        return "->textInput([{$margin}    'placeholder' => \$model->getAttributeLabel('{$attribute}'),{$margin}]);";
    }

    /**
     * Generate properties for gridView
     *
     * @return array
     */
    public function getGridProperties()
    {
        $properties = [];

        $privateAttributes = [
            'id', 'created_by', 'updated_at', 'updated_by',
        ];
        if (($tableSchema = $this->getTableSchema()) === false) {
            foreach ($this->getColumnNames() as $name) {
                if (in_array($name, $privateAttributes)) {
                    continue;
                }
                $properties[$name] = [
                    'name' => $name,
                    'type' => 'text',
                ];
            }
        } else {
            foreach ($tableSchema->columns as $column) {
                if (in_array($column->name, $privateAttributes)) {
                    continue;
                }
                $properties[$column->name] = [
                    'name' => $column->name,
                    'type' => $this->generateColumnFormat($column),
                ];
            }
        }
        foreach ($this->imageProperties() as $imageProperty) {
            $properties[$imageProperty] = [
                'name' => $imageProperty,
                'type' => 'Image',
            ];
        }
        return $properties;
    }

    /**
     * Generate image properties.
     *
     * @return array
     */
    protected function imageProperties()
    {
        return $this->imageProperties 
            ? array_filter(explode(',', $this->imageProperties)) 
            : [];
    }

    /**
     * @inheritDoc
     */
    public function generateColumnFormat($column)
    {
        if ($this->isColorProperty($column)) {
            return 'color';
        }
        return parent::generateColumnFormat($column);
    }

    /**
     * Returns true, if the property is a color property
     * 
     * @param \yii\db\ColumnSchema $column
     * @return bool
     */
    public function isColorProperty($column)
    {
        if (stripos($column->name, self::COLOR_TYPE_KEY) !== false && $column->phpType === 'string') {
            return true;
        }
        return false;
    }

    /**
     * Returns true, if the property is a Image property
     *
     * @param \yii\db\ColumnSchema $column
     * @return bool
     */
    public function isImageProperty($column)
    {
        return $column['type'] == self::IMAGE_TYPE_KEY ? true : false;
    }

    /**
     * Generates a class name for Search
     *
     * @param string $modelClassName
     * @return string
     */
    protected function generateSearchClassName($modelClassName)
    {
        if ($this->searchModelClass) {
            return $this->searchModelClass;
        }
        return $modelClassName . 'Search';
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        foreach ($this->getColumnNames() as $name) {
            if (!strcasecmp($name, 'name') || !strcasecmp($name, 'title')) {
                return $name;
            }
        }
        /* @var $class \yii\db\ActiveRecord */
        $class = $this->getFullModelClass();
        $pk = $class::primaryKey();

        return $pk[0];
    }
}
