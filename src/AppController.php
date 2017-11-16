<?php
/**
 * @author Mahabubul Hasan <codehasan@gmail.com>
 * Date: 10/24/2017
 * Time: 12:38 PM
 */

namespace Uzzal\Crud;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

abstract class AppController extends Controller
{
    /**
     * @var Repository
     */
    protected $_repository;
    protected $_viewPath;
    private $_controllerName;

    /**
     * AppController constructor.
     * @param Repository $repo
     * @param $controllerName (example: Home)
     * @example
     * <code>
     * parent::__construct($repo, 'User'); //view: user
     * or
     * parent::__construct($repo, 'User\Setting'); //view: user.setting
     * or
     * parent::__construct($repo, 'User\AdminUser'); //view: user.admin-user
     * </code>
     */
    public function __construct(Repository $repo, $controllerName)
    {
        $this->_repository = $repo;
        $this->_controllerName = $controllerName;
        $this->_viewPath = str_replace('\-','.', kebab_case($controllerName));
    }

    public function index(){
        return view($this->_viewPath.
            '.index',[
            'rows'=>$this->_repository->getAllRows()
        ]);
    }

    public function create(){
        return view($this->_viewPath.'.create');
    }

    /**
     * @param Request $req
     * @return array
     */
    public function store(Request $req){
        $this->_repository->validator($req->all())->validate();
        $this->_repository->insert($req->all());
        return redirect($req->path())->with('msg', $this->_controllerName.' created successfully!');;
    }

    public function edit($id){
        return view($this->_viewPath.'.edit',[
            'row'=>$this->_repository->getRow($id)
        ]);
    }

    public function update($id, Request $req){
        $this->_repository->validator($req->all(), true)->validate();
        $this->_repository->update($req->all(), $id);
        return redirect(str_replace('/'.$id, '', $req->path()))
            ->with('msg', $this->_controllerName.' updated successfully!');
    }

    public function activate($id, $state){
        $this->_repository->activate($id, $state);
        return redirect()->back()->with('msg', $this->_controllerName.' '.$state.' successfully!');;

    }

    public function destroy($id){
        $this->_repository->delete($id);
        return redirect()->back()->with('msg', $this->_controllerName.' deleted successfully!');;
    }
}