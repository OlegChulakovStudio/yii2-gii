<?php
/**
 * Файл класса Generator
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\gii\generators\model;

use yii\db\Schema;
use yii\gii\CodeFile;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\base\NotSupportedException;
use chulakov\gii\helpers\ModuleGeneratorTrait;

/**
 * This generator will generate one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\generators\model\Generator
{
    use ModuleGeneratorTrait;

    public $generateRelations = self::RELATIONS_ALL;
    public $generateRelationsFromCurrentSchema = true;
    public $generateLabelsFromComments = true;
    public $useSchemaName = true;
    public $generateQuery = true;
    public $enableI18N = false;
    public $imageProperties = false;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Model Generator';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'This generator generates an ActiveRecord class for the specified database table and all needed components.';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['template'], 'required', 'message' => 'A code template must be selected.'],
            [['template'], 'validateTemplate'],

            [['db', 'tableName', 'modelClass', 'baseClass', 'moduleID', 'modulePath', 'imageProperties'], 'filter', 'filter' => 'trim'],
            [['db', 'tableName', 'baseClass', 'moduleID', 'modulePath'], 'required'],

            [['moduleID'], 'match', 'pattern' => '/^[\w\\-]+$/', 'message' => 'Only word characters and dashes are allowed.'],
            [['modulePath'], 'match', 'pattern' => '/^[\w\/]*$/', 'message' => 'Only word characters and slashes are allowed.'],
            [['db', 'modelClass'], 'match', 'pattern' => '/^\w+$/', 'message' => 'Only word characters are allowed.'],
            [['baseClass'], 'match', 'pattern' => '/^[\w\\\\]+$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['tableName'], 'match', 'pattern' => '/^([\w ]+\.)?([\w\* ]+)$/', 'message' => 'Only word characters, and optionally spaces, an asterisk and/or a dot are allowed.'],

            [['db'], 'validateDb'],
            [['tableName'], 'validateTableName'],
            [['modelClass'], 'validateModelClass', 'skipOnEmpty' => false],
            [['baseClass'], 'validateClass', 'params' => ['extends' => ActiveRecord::class]],
            [['generateRelations'], 'in', 'range' => [self::RELATIONS_NONE, self::RELATIONS_ALL, self::RELATIONS_ALL_INVERSE]],
            [['useSchemaName', 'generateQuery'], 'boolean'],
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
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function requiredTemplates()
    {
        return [
            'model.php', 'query.php', 'mapper.php',
            'form.php', 'search.php', 'factory.php',
            'repository.php', 'service.php',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function stickyAttributes()
    {
        return [
            'template', 'db', 'baseClass', 'moduleID', 'modulePath'
        ];
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function generate()
    {
        $files = [];
        $relations = $this->generateRelations();
        $db = $this->getDbConnection();
        $this->imageProperties = $this->imageProperties();

        foreach ($this->getTableNames() as $tableName) {
            // components classes :
            $modelClassName = $this->generateClassName($tableName);
            $queryClassName = $this->generateQueryClassName($modelClassName);
            $mapperClassName = $this->generateMapperClassName($modelClassName);
            $factoryClassName = $this->generateFactoryClassName($modelClassName);
            $searchClassName = $this->generateSearchClassName($modelClassName);
            $formClassName = $this->generateFormClassName($modelClassName);
            $repositoryClassName = $this->generateRepositoryClassName($modelClassName);
            $serviceClassName = $this->generateServiceClassName($modelClassName);

            $tableSchema = $db->getTableSchema($tableName);
            $properties = $this->generateProperties($tableSchema);
            $behaviors = $this->generateBehaviors($tableSchema);

            // model :
            $files[] = new CodeFile(
                $this->fullModulePath . '/models/' . $modelClassName . '.php',
                $this->render('model.php', [
                    'tableName' => $tableName,
                    'className' => $modelClassName,
                    'queryClassName' => $queryClassName,
                    'mapperClassName' => $mapperClassName,
                    'properties' => $properties,
                    'behaviors' => $behaviors,
                    'relations' => isset($relations[$tableName]) ? $relations[$tableName] : [],
                ])
            );

            // query :
            $files[] = new CodeFile(
                $this->fullModulePath . '/models/scopes/' . $queryClassName . '.php',
                $this->render('query.php', [
                    'className' => $queryClassName,
                    'queryTraits' => $this->generateQueryTraits($tableSchema),
                ])
            );

            // mapper :
            $files[] = new CodeFile(
                $this->fullModulePath . '/models/mappers/' . $mapperClassName . '.php',
                $this->render('mapper.php', [
                    'className' => $mapperClassName,
                    'modelClassName' => $modelClassName,
                    'properties' => $properties,
                    'fields' => $this->generateFillAttributes($tableSchema),
                    'labels' => $this->generateLabels($tableSchema),
                    'rules' => $this->generateRules($tableSchema),
                ])
            );

            // form :
            $files[] = new CodeFile(
                $this->fullModulePath . '/models/forms/' . $formClassName . '.php',
                $this->render('form.php', [
                    'className' => $formClassName,
                    'modelClassName' => $modelClassName,
                    'properties' => $this->filtrateProperties($properties),
                ])
            );

            // search :
            list($searchFields, $searchRules, $searchApply, $searchSort) = $this->generateSearchProperties($properties);
            $files[] = new CodeFile(
                $this->fullModulePath . '/models/search/' . $searchClassName . '.php',
                $this->render('search.php', [
                    'className' => $searchClassName,
                    'modelClassName' => $modelClassName,
                    'properties' => $searchFields,
                    'rules' => $searchRules,
                    'apply' => $searchApply,
                    'sort' => $searchSort,
                ])
            );

            // factory :
            $files[] = new CodeFile(
                $this->fullModulePath . '/models/factories/' . $factoryClassName . '.php',
                $this->render('factory.php', [
                    'className' => $factoryClassName,
                    'modelClassName' => $modelClassName,
                    'mapperClassName' => $mapperClassName,
                    'searchClassName' => $searchClassName,
                    'formClassName' => $formClassName,
                ])
            );

            // repository :
            $files[] = new CodeFile(
                $this->fullModulePath . '/repositories/' . $repositoryClassName . '.php',
                $this->render('repository.php', [
                    'className' => $repositoryClassName,
                    'modelClassName' => $modelClassName,
                    'queryClassName' => $queryClassName,
                ])
            );

            // service :
            $files[] = new CodeFile(
                $this->fullModulePath . '/services/' . $serviceClassName . '.php',
                $this->render('service.php', [
                    'className' => $serviceClassName,
                    'repositoryClassName' => $repositoryClassName,
                    'factoryClassName' => $factoryClassName,
                    'mapperClassName' => $mapperClassName,
                ])
            );

            $translate = new CodeFile(
                $this->fullModulePath . '/messages/ru/translate.php',
                $this->render('translate.php', [
                    'className' => $modelClassName,
                ])
            );
            if ($translate->operation == CodeFile::OP_CREATE) {
                $files[] = $translate;
            }
        }

        return $files;
    }

    /**
     * Generates the properties for the specified table.
     *
     * @param \yii\db\TableSchema $table
     * @return array
     */
    protected function generateProperties($table)
    {
        $properties = [];
        foreach ($table->columns as $column) {
            $required = false;
            if (!$column->allowNull && $column->defaultValue === null) {
                $required = true;
            }
            $properties[$column->name] = [
                'required' => $required,
                'type' => $column->phpType,
                'size' => $column->size,
                'name' => $column->name,
                'comment' => $column->comment,
            ];
        }

        if ($imageProperties = $this->imageProperties) {
            /** @var array $imageProperties */
            foreach ($imageProperties as $property) {
                $properties[$property] = [
                    'name' => $property,
                    'type' => 'Image',
                ];
            }
        }

        return $properties;
    }

    /**
     *  Generates the image properties for the model.
     *
     * @return array|null
     * @throws \Exception
     */
    protected function imageProperties()
    {
        if ($this->imageProperties) {
            if ($imageProperties = array_filter(explode(',', $this->imageProperties))) {
                $this->imageProperties = $imageProperties;
                return $this->imageProperties;
            }
            throw new \Exception("Failed to initialize parameter: --imageProperties={$this->imageProperties}");
        }
        return null;
    }

    /**
     * Generate the behaviors for model properties.
     *
     * @param \yii\db\TableSchema $table
     * @return array
     */
    protected function generateBehaviors($table)
    {
        $behaviors = [];
        foreach ($table->columns as $column) {
            if ($column->name == 'created_at') {
                $behaviors[] = [
                    'namespace' => 'yii\behaviors',
                    'class' => 'TimestampBehavior',
                ];
            }
            if ($column->name == 'created_by') {
                $behaviors[] = [
                    'namespace' => 'yii\behaviors',
                    'class' => 'BlameableBehavior'
                ];
            }
        }

        if ($this->imageProperties) {
            $behaviors[] = [
                'namespace' => 'chulakov\components\behaviors',
                'class' => 'common\components\FileRemoveBehavior',
                'options' => [
                    'attributes' => $this->imageProperties,
                ],
            ];
        }

        return $behaviors;
    }

    /**
     * Generate the traits list for query
     *
     * @param \yii\db\TableSchema $table
     * @return array
     */
    protected function generateQueryTraits($table)
    {
        $traits = [];

        $traitsList = [
            'id' =>        ['QueryIdTrait',     'chulakov\model\models\scopes\QueryIdTrait'],
            'slug' =>      ['QuerySlugTrait',   'chulakov\model\models\scopes\QuerySlugTrait'],
            'is_active' => ['QueryActiveTrait', 'chulakov\model\models\scopes\QueryActiveTrait'],
            'sort' =>      ['QuerySortTrait',   'chulakov\model\models\scopes\QuerySortTrait'],
        ];
        foreach ($traitsList as $key => $item) {
            if (isset($table->columns[$key])) {
                list($name, $className) = $item;
                $traits[$name] = $className;
            }
        }

        return $traits;
    }

    /**
     * Generate the fill attributes for mapper
     *
     * @param \yii\db\TableSchema $table
     * @return array
     */
    protected function generateFillAttributes($table)
    {
        $attributes = array_keys($table->columns);

        $attributes = $this->filtrateProperties(
            array_combine($attributes, $attributes)
        );

        return array_values($attributes);
    }

    /**
     * Generates the attribute labels for the specified table.
     *
     * @param \yii\db\TableSchema $table the table schema
     * @return array the generated attribute labels (name => label)
     */
    public function generateLabels($table)
    {
        $labels = [];
        foreach ($table->columns as $column) {
            if ($this->generateLabelsFromComments && !empty($column->comment)) {
                $labels[$column->name] = $column->comment;
            } elseif (!strcasecmp($column->name, 'id')) {
                $labels[$column->name] = 'ID';
            } else {
                $label = Inflector::camel2words($column->name);
                if (!empty($label) && substr_compare($label, ' id', -3, 3, true) === 0) {
                    $label = substr($label, 0, -3) . ' ID';
                }
                $labels[$column->name] = $label;
            }
        }

        return $labels;
    }

    /**
     * Generates validation rules for the specified table.
     *
     * @param \yii\db\TableSchema $table the table schema
     * @return array the generated validation rules
     */
    public function generateRules($table)
    {
        $types = [];
        $lengths = [];

        $privateAttributes = [
            'id', 'sort',
            'created_at', 'created_by',
            'updated_at', 'updated_by',
        ];
        foreach ($table->columns as $column) {
            if ($column->autoIncrement || in_array($column->name, $privateAttributes)) {
                continue;
            }
            if (!$column->allowNull && $column->defaultValue === null) {
                $types['required'][] = $column->name;
            }
            switch ($column->type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_TINYINT:
                    $types['integer'][] = $column->name;
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $column->name;
                    break;
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][] = $column->name;
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                case Schema::TYPE_JSON:
                    $types['safe'][] = $column->name;
                    break;
                default: // strings
                    if ($column->size > 0) {
                        $lengths[$column->size][] = $column->name;
                    } else {
                        $types['string'][] = $column->name;
                    }
            }
        }
        $rules = [];
        $driverName = $this->getDbDriverName();
        foreach ($types as $type => $columns) {
            if ($driverName === 'pgsql' && $type === 'integer') {
                $rules[] = "[['" . implode("', '", $columns) . "'], 'default', 'value' => null]";
            }
            $rules[] = "[['" . implode("', '", $columns) . "'], '$type']";
        }
        foreach ($lengths as $length => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], 'string', 'max' => $length]";
        }

        $db = $this->getDbConnection();

        // Unique indexes rules
        try {
            $uniqueIndexes = array_merge($db->getSchema()->findUniqueIndexes($table), [$table->primaryKey]);
            $uniqueIndexes = array_unique($uniqueIndexes, SORT_REGULAR);
            foreach ($uniqueIndexes as $uniqueColumns) {
                // Avoid validating auto incremental columns
                if (!$this->isColumnAutoIncremental($table, $uniqueColumns)) {
                    $attributesCount = count($uniqueColumns);

                    if ($attributesCount === 1) {
                        $rules[] = "[['" . $uniqueColumns[0] . "'], 'unique']";
                    } elseif ($attributesCount > 1) {
                        $columnsList = implode("', '", $uniqueColumns);
                        $rules[] = "[['$columnsList'], 'unique', 'targetAttribute' => ['$columnsList']]";
                    }
                }
            }
        } catch (NotSupportedException $e) {
            // doesn't support unique indexes information...do nothing
        }

        // Exist rules for foreign keys
        foreach ($table->foreignKeys as $refs) {
            $refTable = $refs[0];
            $refTableSchema = $db->getTableSchema($refTable);
            if ($refTableSchema === null) {
                // Foreign key could point to non-existing table: https://github.com/yiisoft/yii2-gii/issues/34
                continue;
            }
            $refClassName = $this->generateClassName($refTable);
            unset($refs[0]);
            $attributes = implode("', '", array_keys($refs));
            $targetAttributes = [];
            foreach ($refs as $key => $value) {
                $targetAttributes[] = "'$key' => '$value'";
            }
            $targetAttributes = implode(', ', $targetAttributes);
            $rules[] = "[['$attributes'], 'exist', 'skipOnError' => true, 'targetClass' => $refClassName::className(), 'targetAttribute' => [$targetAttributes]]";
        }

        return $rules;
    }

    /**
     * Generates the table name by considering table prefix.
     * @param string $tableName the table name (which may contain schema prefix)
     * @return string the generated table name
     */
    public function generateTableName($tableName)
    {
        $db = $this->getDbConnection();
        if (preg_match("/^{$db->tablePrefix}(.*?)$/", $tableName, $matches)) {
            $tableName = '{{%' . $matches[1] . '}}';
        } elseif (preg_match("/^(.*?){$db->tablePrefix}$/", $tableName, $matches)) {
            $tableName = '{{' . $matches[1] . '%}}';
        }
        return $tableName;
    }

    /**
     * Filtrate properties
     *
     * @param array $properties
     * @return array
     */
    protected function filtrateProperties($properties)
    {
        $privateAttributes = [
            'id', 'sort',
            'created_at', 'created_by',
            'updated_at', 'updated_by',
        ];
        foreach ($privateAttributes as $attribute) {
            if (isset($properties[$attribute])) {
                unset($properties[$attribute]);
            }
        }
        return $properties;
    }

    /**
     * Filtrate search properties
     *
     * @param array $properties
     * @return array
     */
    protected function generateSearchProperties($properties)
    {
        $fields = [];
        $rules = [];
        $apply = [];
        $sort = [
            'main' => 'id',
            'list' => [],
        ];

        if (isset($properties['is_active'])) {
            $fields['is_active'] = $properties['is_active'];
            $rules['is_active'] = "['{$properties['is_active']['name']}', 'boolean']";
            $apply['is_active'] = "['{$properties['is_active']['name']}' => \$this->{$properties['is_active']['name']}]";
        }

        foreach (['id', 'sort'] as $name) {
            if (isset($properties[$name])) {
                $sort['main'] = $name;
                $sort['list'][] = $name;
            }
        }

        $searchAttributes = ['title', 'name'];
        foreach ($searchAttributes as $attribute) {
            if (isset($properties[$attribute])) {
                $fields[$attribute] = $properties[$attribute];
                if ($properties[$attribute]['size'] > 0) {
                    $rules[$attribute] = "['{$properties[$attribute]['name']}', 'string', 'max' => {$properties[$attribute]['size']}]";
                } else {
                    $rules[$attribute] = "['{$properties[$attribute]['name']}', 'string']";
                }
                $apply[$attribute] = "['like', '{$properties[$attribute]['name']}', \$this->{$properties[$attribute]['name']}]";
                $sort['list'][] = $attribute;
                if (empty($sort['main'])) {
                    $sort['main'] = $attribute;
                }
                break;
            }
        }

        // TODO: datetime fields search (created_at, published_at)

        return [$fields, $rules, $apply, $sort];
    }

    /**
     * @inheritDoc
     */
    protected function generateRelations()
    {
        if ($this->generateRelations === self::RELATIONS_NONE) {
            return [];
        }

        $db = $this->getDbConnection();
        $relations = [];
        $schemaNames = $this->getSchemaNames();
        foreach ($schemaNames as $schemaName) {
            foreach ($db->getSchema()->getTableSchemas($schemaName) as $table) {
                $className = $this->generateClassName($table->fullName);
                foreach ($table->foreignKeys as $refs) {
                    $refTable = $refs[0];
                    $refTableSchema = $db->getTableSchema($refTable);
                    if ($refTableSchema === null) {
                        // Foreign key could point to non-existing table: https://github.com/yiisoft/yii2-gii/issues/34
                        continue;
                    }
                    unset($refs[0]);
                    $fks = array_keys($refs);
                    $refClassName = $this->generateClassName($refTable);

                    // Add relation for this table
                    $link = $this->generateRelationLink(array_flip($refs));
                    $relationName = $this->generateRelationName($relations, $table, $fks[0], false);
                    $relations[$table->fullName][$relationName] = [
                        "return \$this->hasOne($refClassName::class, $link);",
                        $refClassName,
                        false,
                    ];

                    // Add relation for the referenced table
                    $hasMany = $this->isHasManyRelation($table, $fks);
                    $link = $this->generateRelationLink($refs);
                    $relationName = $this->generateRelationName($relations, $refTableSchema, $className, $hasMany);
                    $relations[$refTableSchema->fullName][$relationName] = [
                        "return \$this->" . ($hasMany ? 'hasMany' : 'hasOne') . "($className::class, $link);",
                        $className,
                        $hasMany,
                    ];
                }

                if (($junctionFks = $this->checkJunctionTable($table)) === false) {
                    continue;
                }

                $relations = $this->generateManyManyRelations($table, $junctionFks, $relations);
            }
        }

        if ($this->generateRelations === self::RELATIONS_ALL_INVERSE) {
            return $this->addInverseRelations($relations);
        }

        return $relations;
    }

    /**
     * Generates relations using a junction table by adding an extra viaTable().
     * @param \yii\db\TableSchema the table being checked
     * @param array $fks obtained from the checkJunctionTable() method
     * @param array $relations
     * @return array modified $relations
     */
    protected function generateManyManyRelations($table, $fks, $relations)
    {
        $db = $this->getDbConnection();

        foreach ($fks as $pair) {
            list($firstKey, $secondKey) = $pair;
            $table0 = $firstKey[0];
            $table1 = $secondKey[0];
            unset($firstKey[0], $secondKey[0]);
            $className0 = $this->generateClassName($table0);
            $className1 = $this->generateClassName($table1);
            $table0Schema = $db->getTableSchema($table0);
            $table1Schema = $db->getTableSchema($table1);

            // @see https://github.com/yiisoft/yii2-gii/issues/166
            if ($table0Schema === null || $table1Schema === null) {
                continue;
            }

            $link = $this->generateRelationLink(array_flip($secondKey));
            $viaLink = $this->generateRelationLink($firstKey);
            $relationName = $this->generateRelationName($relations, $table0Schema, key($secondKey), true);
            $relations[$table0Schema->fullName][$relationName] = [
                "return \$this->hasMany($className1::className(), $link)->viaTable('"
                . $this->generateTableName($table->name) . "', $viaLink);",
                $className1,
                true,
            ];

            $link = $this->generateRelationLink(array_flip($firstKey));
            $viaLink = $this->generateRelationLink($secondKey);
            $relationName = $this->generateRelationName($relations, $table1Schema, key($firstKey), true);
            $relations[$table1Schema->fullName][$relationName] = [
                "return \$this->hasMany($className0::className(), $link)->viaTable('"
                . $this->generateTableName($table->name) . "', $viaLink);",
                $className0,
                true,
            ];
        }

        return $relations;
    }
}
