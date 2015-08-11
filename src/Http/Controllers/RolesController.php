<?php namespace Acoustep\EntrustGui\Http\Controllers;
/**
 * 
 * @author Mitch Stanley <acoustep+entrustgui@fastmail.co.uk>
 */


use App\Http\Controllers\Controller;
use Acoustep\EntrustGui\Gateways\RoleGateway;
use Illuminate\Http\Request;
use Watson\Validating\ValidationException;
use Illuminate\Config\Repository as Config;


class RolesController extends Controller
{

  protected $request;
  protected $gateway;
  protected $permission;
  protected $config;

  public function __construct(Request $request, RoleGateway $gateway, Config $config)
  {
    $this->config = $config;
    $this->request = $request;
    $this->gateway = $gateway;
    $permission_class = $this->config->get('entrust.permission');
    $this->permission = new $permission_class;
  }

	/**
	 * Show the application welcome screen to the role.
	 *
	 * @return Response
	 */
	public function index()
	{
    $roles = $this->gateway->paginate($this->config->get('entrust-gui.pagination.roles'));

    return view('entrust-gui::roles.index', compact(
      'roles'
    ));
	}

  public function create()
  {
    $role_class = $this->config->get('entrust.role');
    $role = new $role_class;
    $permissions = $this->permission->lists('name', 'id');

    return view('entrust-gui::roles.create', compact(
      'role',
      'permissions'
    ));
  }

  public function store()
  {
    try {
      $this->gateway->create($this->request);
    } catch(ValidationException $e) {
      return back()->withErrors($e->getErrors());
    }
    return redirect(route('entrust-gui::roles.index'))->withSuccess(trans('entrust-gui::roles.created'));
  }

  public function edit($id)
  {
    $role = $this->gateway->find($id);
    $permissions = $this->permission->lists('name', 'id');

    return view('entrust-gui::roles.edit', compact(
      'role',
      'permissions'
    ));
  }

  public function update($id)
  {
    try {
      $role = $this->gateway->update($this->request, $id);
    } catch(ValidationException $e) {
      return back()->withErrors($e->getErrors());
    }
    return redirect(route('entrust-gui::roles.index'))->withSuccess(trans('entrust-gui::roles.updated'));
  }
  
  public function destroy($id)
  {
    $this->gateway->delete($id);
    return redirect(route('entrust-gui::roles.index'))->withSuccess(trans('entrust-gui::roles.destroyed'));
  }
}
