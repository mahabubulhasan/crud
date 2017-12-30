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
    protected $_redirect=true;
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
            'rows'=>$this->_repository->getModel()->paginate(30)
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
        $this->_repository->validator($req)->validate();
        $resp = $this->_repository->insert($req);

        if($this->_redirect) {
            return redirect($req->path())->with('msg', $this->_controllerName . ' created successfully!');;
        }else{
            return $resp;
        }
    }

    public function edit($id){
        return view($this->_viewPath.'.edit',[
            'row'=>$this->_repository->getRow($id)
        ]);
    }

    public function update($id, Request $req){
        $this->_repository->validator($req, $id, true)->validate();
        $resp = $this->_repository->update($req, $id);
        if($this->_redirect) {
            return redirect(str_replace('/' . $id, '', $req->path()))
                ->with('msg', $this->_controllerName . ' updated successfully!');
        }else{
            return $resp;
        }
    }

    public function activate($id, $state){
        $resp = $this->_repository->activate($id, $state);
        if($this->_redirect) {
            return redirect()->back()->with('msg', $this->_controllerName . ' ' . $state . ' successfully!');
        }else{
            return $resp;
        }
    }

    public function destroy($id){
        $resp = $this->_repository->delete($id);
        if($this->_redirect) {
            return redirect()->back()->with('msg', $this->_controllerName . ' deleted successfully!');
        }else{
            return $resp;
        }
    }
}