<?php namespace Acoustep\EntrustGui\Http\Controllers;
/**
 * 
 * @author Mitch Stanley <acoustep+entrustgui@fastmail.co.uk>
 */


use App\Http\Controllers\Controller;
use Acoustep\EntrustGui\Gateways\PermissionGateway;
use Illuminate\Http\Request;
use Watson\Validating\ValidationException;
use Config;


class PermissionsController extends Controller
{

  protected $request;
  protected $gateway;

  public function __construct(Request $request, PermissionGateway $gateway)
  {
    $this->request = $request;
    $this->gateway = $gateway;
  }

	/**
	 * Show the application welcome screen to the permission.
	 *
	 * @return Response
	 */
	public function index()
	{
    $permissions = $this->gateway->paginate(Config::get('entrust-gui.pagination.permissions'));

    return view('entrust-gui::permissions.index', compact(
      'permissions'
    ));
	}

  public function create()
  {
    $permission = $this->gateway->newPermissionInstance();

    return view('entrust-gui::permissions.create', compact(
      'permission'
    ));
  }

  public function store()
  {
    try {
      $this->gateway->create($this->request);
    } catch(ValidationException $e) {
      return back()->withErrors($e->getErrors());
    }
    return redirect(route('entrust-gui::permissions.index'))->withSuccess(trans('entrust-gui::permissions.created'));
  }

  public function edit($id)
  {
    $permission = $this->gateway->find($id);

    return view('entrust-gui::permissions.edit', compact(
      'permission'
    ));
  }

  public function update($id)
  {
    try {
      $permission = $this->gateway->update($this->request, $id);
    } catch(ValidationException $e) {
      return back()->withErrors($e->getErrors());
    }
    return redirect(route('entrust-gui::permissions.index'))->withSuccess(trans('entrust-gui::permissions.updated'));
  }
  
  public function destroy($id)
  {
    $this->gateway->delete($id);
    return redirect(route('entrust-gui::permissions.index'))->withSuccess(trans('entrust-gui::permissions.destroyed'));
  }
}
