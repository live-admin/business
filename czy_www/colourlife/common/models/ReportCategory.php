<?php

/**
 * This is the model class for table "rpt_report_category".
 *
 * The followings are the available columns in table 'rpt_report_category':
 * @property string $id
 * @property string $category_name
 */
class ReportCategory extends CActiveRecord
{
  
    public function tableName()
    {
        return 'rpt_report_category';
    }

    public function rules()
    {
        return array(
            array('category_name','required'),            
            array('category_name', 'unique'),
            array('category_name', 'length', 'max' => 50),
            array('id, category_name, description, type, parameters, sql_script','safe','on' => 'search')
        );
    }
    
    /**
     * 根据分类名称获得分类ID.如果未找到范围-1
     * @param string $categoryName
     * @return integer 分类ID,如果未找到范围-1
     */
    public static function getCategoryIdByName($categoryName)
    {
        $model = self::findByCategoryName($categoryName);
        return $model == null ? -1 : $model->id;
    }
    
    public static function findByCategoryName($categoryName)
    {
        return ReportCategory::model()->find("category_name=:cat", array('cat' => $categoryName));
    }

    /**
     *
     * @return array 数据库关联关系
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        		"reports" => array(self::HAS_MANY, 'ReportFramework', 'category_id'),
        );
    }

    /**
     *
     * @return array 标签名称
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'category_name' => '分类名称', // 报表或存储过程名称,请使用英文字母
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
        $criteria = new CDbCriteria();
        
        $criteria->compare('id', $this->id, true);
        $criteria->compare('category_name', $this->category_name, true);
        
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
}
