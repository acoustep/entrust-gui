<?php namespace Acoustep\EntrustGui\Http\Controllers;
/**
 * 
 * @author Mitch Stanley <acoustep+entrustgui@fastmail.co.uk>
 */


use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Config;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;
use Hash;


class UsersController extends Controller
{

  protected $user;
  protected $role;
  protected $config;
  protected $request;

  public function __construct(Config $config, Request $request)
  {
    $this->config = $config;
    $this->request = $request;
    $this->user = $this->newUserInstance();
    $this->role = $this->newRoleInstance();
  }

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
    $users = $this->user->paginate(5);

    return view('entrust-gui::users.index', compact(
      'users'
    ));
	}

  public function create()
  {
    $user = $this->newUserInstance();
    $roles = $this->role->lists('name', 'id');

    return view('entrust-gui::users.create', compact(
      'user',
      'roles'
    ));
  
  }
  public function store()
  {
    $data = $this->request->all();
    $data['password'] = Hash::make($data['password']);
    $user = $this->user->create($data);
    $user->roles()->sync($this->request->get('roles', []));
    return redirect(route('entrust-gui::users.index'))->withSuccess(trans('entrust-gui::users.created'));
  
  }

  public function edit($id)
  {
    $user = $this->user->with('roles')->find($id);
    $roles = $this->role->lists('name', 'id');

    return view('entrust-gui::users.edit', compact(
      'user',
      'roles'
    ));
  
  }
  public function update($id)
  {
    $user = $this->user->find($id);
    $data = $this->request->except('password');
    if($this->request->get('password', false)) {
      $data['password'] = Hash::make($this->request->get('password'));
    }
    $user->update($data);
    $user->roles()->sync($this->request->get('roles', []));
    return redirect(route('entrust-gui::users.index'))->withSuccess(trans('entrust-gui::users.updated'));
  
  }
  public function destroy($id)
  {
    $this->user->destroy($id);
    return redirect(route('entrust-gui::users.index'))->withSuccess(trans('entrust-gui::users.destroyed'));
  
  }

  protected function newUserInstance()
  {
    $user_class = $this->config->get('auth.model');

    return new $user_class;
  }

  protected function newRoleInstance()
  {
    $role_class = $this->config->get('entrust.role');

    return new $role_class;
  }
}
