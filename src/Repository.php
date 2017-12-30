<?php
/**
 * @author Mahabubul Hasan <codehasan@gmail.com>
 * Date: 10/24/2017
 * Time: 12:36 PM
 */

namespace Uzzal\Crud;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface Repository
{
    const OPTION_VALUE = 'OPTION_VALUE';
    const IS_ACTIVE = 'IS_ACTIVE';
    const DEFAULT_ACTIVE_ROWS = 'DEFAULT_ACTIVE_ROWS';

    /**
     * @param array $config
     * @return void
     */
    function setConfig(array $config);

    /**
     * @param Request|array $data
     * @param $except
     * @param bool $isUpdate
     * @return Validator
     */
    function validator($data, $except='', $isUpdate=false);

    /**
     * @return Collection
     */
    function getAllRows();

    /**
     * @param $id
     * @return Model
     */
    function getRow($id);

    /**
     * @return Builder
     */
    function getModel();

    /**
     * @return Repository
     */
    function getRows();

    /**
     * @param $column
     * @param null $operator
     * @param null $value
     * @param string $boolean
     * @return Repository
     */
    function where($column, $operator = null, $value = null, $boolean = 'and');

    /**
     * @param string $sel
     * @return string
     */
    function asOptions($sel='');

    /**
     * @param Request|array $data
     * @return mixed
     */
    function insert($data);

    /**
     * @param Request|array $data
     * @param $id
     * @return mixed
     */
    function update($data, $id);

    /**
     * @param $id
     * @return mixed
     */
    function delete($id);

    /**
     * @param $id
     * @param $state
     * @return mixed
     */
    function activate($id, $state);
}