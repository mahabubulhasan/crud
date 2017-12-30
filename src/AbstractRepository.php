<?php
/**
 * @author Mahabubul Hasan <codehasan@gmail.com>
 * Date: 10/24/2017
 * Time: 12:36 PM
 */

namespace Uzzal\Crud;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Validator;

class AbstractRepository implements Repository
{
    protected $_primaryKey;

    /**
     * @var Builder
     */
    protected $_rows;

    /**
     * @var Builder
     */
    private $_model;

    private $_validation_rules=[];
    private $_update_validation_rules = [];

    protected $_config=[
        self::OPTION_VALUE => 'name',
        self::IS_ACTIVE => 'is_active',
        self::DEFAULT_ACTIVE_ROWS => false
    ];

    /**
     * AbstractRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->_model = $model;
        $this->_primaryKey = $model->getKeyName();
    }

    /**
     * @param array $config
     * @example
     * <code>
     * $obj->setConfig[Repository::OPTION_VALUE=>'name']
     * </code>
     */
    public function setConfig(array $config)
    {
        $this->_config = array_merge($this->_config, $config);
    }

    /**
     * @param array|\Illuminate\Http\Request $data
     * @param bool $isUpdate
     * @param $except
     * @return mixed
     */
    public function validator($data, $isUpdate = false, $except='')
    {
        if(!is_array($data)){
            $data = $data->all();
        }
        if($isUpdate){
            return Validator::make($data, $this->_update_validation_rules);
        }
        return Validator::make($data, $this->_validation_rules);
    }

    /**
     * @param array $rules
     */
    protected function _setValidationRule(array $rules){
        $this->_validation_rules = $rules;
    }

    /**
     * @param array $rules
     */
    protected function _setUpdateValidationRule(array $rules){
        $this->_update_validation_rules = $rules;
    }

    /**
     * @param bool $isActive
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllRows($isActive = false)
    {
        if($isActive){
            $field = $this->_config[self::IS_ACTIVE];
            return $this->_model->where($field, true)->get();
        }
        return $this->_model->get();
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|Model|null|static|static[]
     */
    public function getRow($id)
    {
        return $this->_model->find($id);
    }

    /**
     * @return Builder|Model
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @return $this
     */
    public function getRows()
    {
        $this->_rows = $this->getModel();
        return $this;
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        $this->_rows = $this->_rows->where($column, $operator, $value, $boolean);
        return $this;
    }

    /**
     * @des Default key=PrimaryKey, value=name, to change default value use setConfig()
     * @param string $sel
     * @return string
     */
    public function asOptions($sel = '')
    {
        if($this->_rows){
            $rows = $this->_rows->get();
        }else{
            $rows = $this->getAllRows($this->_config[self::DEFAULT_ACTIVE_ROWS]);
        }

        $key = $this->_primaryKey;
        $val = $this->_config[self::OPTION_VALUE];

        $opt = '';
        foreach($rows as $v){
            $attr = ($v->{$key}==$sel)?' selected="selected"':'';
            $opt.='<option value="'.$v->{$key}.'"'.$attr.'>'.$v->{$val}.'</option>';
        }
        return $opt;
    }

    /**
     * @param array|\Illuminate\Http\Request $data
     * @return $this|Model
     */
    public function insert($data)
    {
        if(!is_array($data)){
            $data = $data->all();
        }
        return $this->_model->create($data);
    }

    /**
     * @param array|\Illuminate\Http\Request $data
     * @param $id
     * @return bool|int
     */
    public function update($data, $id)
    {
        if(!is_array($data)){
            $data = $data->all();
        }
        return $this->_model->find($id)->update($data);
    }

    public function delete($id)
    {
        return $this->_model->find($id)->delete();
    }

    public function activate($id, $state)
    {
        return $this->_model->find($id)->update(['is_active'=>(($state=='true')?1:0)]);
    }

}