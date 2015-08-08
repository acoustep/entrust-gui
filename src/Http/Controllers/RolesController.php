<?php namespace Acoustep\EntrustGui\Http\Controllers;
/**
 * 
 * @author Mitch Stanley <acoustep+entrustgui@fastmail.co.uk>
 */


use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Config;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;


class RolesController extends Controller
{

  protected $role;
  protected $config;
  protected $request;

  public function __construct(Config $config, Request $request)
  {
    $this->config = $config;
    $this->request = $request;
    $role_class = $this->config->get('entrust.role');

    $this->role = new $role_class;
  }

	/**
	 * Show the application welcome screen to the role.
	 *
	 * @return Response
	 */
	public function index()
	{
    $roles = $this->role->paginate(5);

    return view('entrust-gui::roles.index', compact(
      'roles'
    ));
	}

  public function create()
  {
    $role_class = $this->config->get('entrust.role');
    $role = new $role_class;

    return view('entrust-gui::roles.create', compact(
      'role'
    ));
  
  }
  public function store()
  {
    $this->role->create($this->request->all());
    return redirect(route('entrust-gui::roles.index'))->withSuccess(trans('entrust-gui::roles.created'));
  
  }

  public function edit($id)
  {
    $role = $this->role->find($id);

    return view('entrust-gui::roles.edit', compact(
      'role'
    ));
  
  }
  public function update($id)
  {
    $role = $this->role->find($id);
    $role->update($this->request->all());
    return redirect(route('entrust-gui::roles.index'))->withSuccess(trans('entrust-gui::roles.updated'));
  
  }
  public function destroy($id)
  {
    $this->role->destroy($id);
    return redirect(route('entrust-gui::roles.index'))->withSuccess(trans('entrust-gui::roles.destroyed'));
  
  }
}
