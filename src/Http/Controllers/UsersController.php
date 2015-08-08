<?php namespace Acoustep\EntrustGui\Http\Controllers;
/**
 * 
 * @author Mitch Stanley <acoustep+entrustgui@fastmail.co.uk>
 */


use App\Http\Controllers\Controller;
use Acoustep\EntrustGui\Gateways\UserGateway;
use Illuminate\Http\Request;


class UsersController extends Controller
{

  protected $gateway;
  protected $request;
  protected $role;

  public function __construct(Request $request, UserGateway $gateway)
  {
    $this->request = $request;
    $this->gateway = $gateway;
    $this->role = $this->gateway->newRoleInstance();
  }

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
    $users = $this->gateway->paginate(5);

    return view('entrust-gui::users.index', compact(
      'users'
    ));
	}

  public function create()
  {
    $user = $this->gateway->newUserInstance();
    $roles = $this->role->lists('name', 'id');

    return view('entrust-gui::users.create', compact(
      'user',
      'roles'
    ));
  
  }

  public function store()
  {
    $user = $this->gateway->create($this->request);
    return redirect(route('entrust-gui::users.index'))->withSuccess(trans('entrust-gui::users.created'));
  
  }

  public function edit($id)
  {
    $user = $this->gateway->find($id);
    $roles = $this->role->lists('name', 'id');

    return view('entrust-gui::users.edit', compact(
      'user',
      'roles'
    ));
  
  }

  public function update($id)
  {
    $this->gateway->update($this->request, $id);
    return redirect(route('entrust-gui::users.index'))->withSuccess(trans('entrust-gui::users.updated'));
  }

  public function destroy($id)
  {
    $this->gateway->delete($id);
    return redirect(route('entrust-gui::users.index'))->withSuccess(trans('entrust-gui::users.destroyed'));
  }

}
