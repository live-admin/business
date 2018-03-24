<?php

/**
 * This is the model class for table "rpt_report_framework".
 *
 * The followings are the available columns in table 'rpt_report_framework':
 * @property string $id
 * @property string $name
 * @property string $description
 * @property integer $type
 * @property string $parameters
 * @property string $sql_script
 * @property integer $category_id
 * @property integer $is_hidden
 */
class ReportFramework extends CActiveRecord
{
    private static $reportFetchRowLimit = 5000;   //一次读取最大行数
    
    /**
     * 根据ID和分类名称查找报表
     * @param unknown $id
     * @param unknown $categoryName
     */
    public static function findByIdAndCategory($id, $categoryName)
    {
        $cid = ReportCategory::getCategoryIdByName($categoryName);
        $model = ReportFramework::model();
        return $model->findByPk($id, "category_id=:cid", array('cid' => $cid));
    }
    
    public static function getSearchMode($categoryName)
    {
        $model = new self();
        $cid = ReportCategory::getCategoryIdByName($categoryName);
        $model->dbCriteria->addCondition("category_id=$cid");
        return $model;
    }
    
//     public function ExecuteComplexReport($params)
//     {
//     	$queryProcReg = '/\{([^\s]*?)\((\s*(\w*)\s*(,\s*(\w+?)\s*)*)?\)\}/';   //查询过程正则，报表名称中间不能有空格, 匹配如：'{E基金_项目列表(a, b)}';
    	
//     }
    
    /**
     * 执行查询
     * @param array $params 报表需要使用的参数
     * @param ReportFrameworkVisitor $reportVisitor 报表数据访问者
     */
    public function ExecuteQuery($params, $reportVisitor)
    {
        if($this->isNewRecord)
        {
            return array();
        }
        if($this->type != 0)
        {
            return array();
        }
        
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        
        $sql = $this->sql_script;
        
        if(!is_a($reportVisitor, "ReportFrameworkVisitor"))
        {
            throw new Exception("传入参数错误ReportFramework::ExecuteQuery");
        }
        
        $offset = 0;
        $rows = self::ExecuteReportForSqlQuery($sql, $params, self::$reportFetchRowLimit, $offset);
        
        $reportVisitor->begin(count($rows) > 0);
        
        if(count($rows) > 0)
        {
            $header = array_keys($rows[0]);
        }
        else
        {
            $header = array();
        }
        
        $reportVisitor->buildHeader($header);
        
        while (count($rows) > 0)
        {
            $reportVisitor->buildBody($rows, $offset);
            if(count($rows) < self::$reportFetchRowLimit) break;
            $offset += count($rows);
            $rows = self::ExecuteReportForSqlQuery($sql, $params, self::$reportFetchRowLimit, $offset);
        }
        
        $reportVisitor->end();
    }

    private static function ExecuteReportForSqlQuery($sql, $params, $limit = -1, $offset = -1)
    {
        if (is_array($params) && count($params) > 0)
        {
            foreach ($params as $p)
            {
                if($p['type'] == 'number')
                {
                    $sql = preg_replace('/:' . $p['name'] . '\b/', floatval($p['value']), $sql);
                }
                else
                {
                    $sql = preg_replace('/:' . $p['name'] . '\b/', str_replace("'", "\\'", $p['value']), $sql);
                }
            }
        }
        $cmd = Yii::app()->db->createCommand();
               
        if($limit != -1)
        {
            $sql .= " LIMIT $limit ";
        }
        
        if($offset != -1)
        {
            $sql .= " OFFSET $offset " ;
        }

        $cmd->setText($sql);
        return $cmd->queryAll();
    }

    /**
     * 获取参数的数组形式
     *
     * @return array( array('name', 'type', 'require', 'desc')
     *         )
     */
    public function getParameterArray()
    {
        $params = array();
        if (! empty($this->parameters))
        {
            $str_parameters = $this->parameters;
            $str_parameters = str_replace(" ", "", $str_parameters);
            $str_parameters = str_replace("\r", "", $str_parameters);
            $str_parameters = str_replace("\n", "", $str_parameters);
            foreach (explode(",", $str_parameters) as $p)
            {
                $param = explode(":", $p);
                if (count($param) != 4)
                {
                    continue;
                }
                $params[] = $param;
            }
        }
        return $params;
    }

    /**
     *
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'rpt_report_framework';
    }

    /**
     *
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'name, description, sql_script',
                'required'
            ),
            array(
                'type, category_id, is_hidden',
                'numerical',
                'integerOnly' => true
            ),
            array(
                'name',
                'length',
                'max' => 128
            ),
            array(
                'name',
                'unique'
            ),
            array(
                'description',
                'length',
                'max' => 2000
            ),
            array(
                'name, description, sql_script, parameters',
                'safe'
            ),
            array('sql_script', "checkSqlSafe"),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'id, name, description, type, category_id, is_hidden, parameters, sql_script',
                'safe',
                'on' => 'search'
            )
        );
    }
    
    public function checkSqlSafe()
    {
        
        if(preg_match('/\b(insert|update|delete|create|drop|alter|grant)\b/i', $this->sql_script))
        {
            $this->addError("sql_script", "只能编写查询SQL语句.不能编写增删改SQL");
        }
    }

    /**
     *
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        		'category' => array(self::BELONGS_TO, 'ReportCategory', 'category_id'),
        );
    }

    /**
     *
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '名称', // 报表或存储过程名称,请使用英文字母
            'description' => '说明', // 报表作用说明
            'type' => '脚本类型', // . 0-SQL查询, 1-存储过程, 3-函数
            'parameters' => '参数', // . 格式"参数名1:说明,参数名2:说明"
            'sql_script' => '脚本', 
        	'category_id' => '分类',
        	'is_hidden' => '隐藏', 
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     *         based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria = new CDbCriteria();
        
        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name,true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('type', $this->type, true);
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className
     *            active record class name.
     * @return ReportFramework the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getTypeText()
    {
        if ($this->type == 0)
        {
            return "SQL查询";
        }
        if ($this->type == 1)
        {
            return "存储过程";
        }
        if ($this->type == 3)
        {
            return "函数";
        }
        if ($this->type == 10)
        {
            return "复杂报表";
        }
    }
}
