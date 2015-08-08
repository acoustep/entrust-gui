<?php namespace Acoustep\EntrustGui\Http\Controllers;
/**
 * 
 * @author Mitch Stanley <acoustep+entrustgui@fastmail.co.uk>
 */


use App\Http\Controllers\Controller;
use Acoustep\EntrustGui\Gateways\RoleGateway;
use Illuminate\Http\Request;


class RolesController extends Controller
{

  protected $request;
  protected $gateway;

  public function __construct(Request $request, RoleGateway $gateway)
  {
    $this->request = $request;
    $this->gateway = $gateway;
  }

	/**
	 * Show the application welcome screen to the role.
	 *
	 * @return Response
	 */
	public function index()
	{
    $roles = $this->gateway->paginate(5);

    return view('entrust-gui::roles.index', compact(
      'roles'
    ));
	}

  public function create()
  {
    $role = $this->gateway->newRoleInstance();

    return view('entrust-gui::roles.create', compact(
      'role'
    ));
  }

  public function store()
  {
    $this->gateway->create($this->request);
    return redirect(route('entrust-gui::roles.index'))->withSuccess(trans('entrust-gui::roles.created'));
  }

  public function edit($id)
  {
    $role = $this->gateway->find($id);

    return view('entrust-gui::roles.edit', compact(
      'role'
    ));
  }

  public function update($id)
  {
    $role = $this->gateway->update($this->request, $id);
    return redirect(route('entrust-gui::roles.index'))->withSuccess(trans('entrust-gui::roles.updated'));
  }
  
  public function destroy($id)
  {
    $this->gateway->delete($id);
    return redirect(route('entrust-gui::roles.index'))->withSuccess(trans('entrust-gui::roles.destroyed'));
  }
}
