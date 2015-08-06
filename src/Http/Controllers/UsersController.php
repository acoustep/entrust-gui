<?php namespace Acoustep\EntrustGui\Http\Controllers;
/**
 * 
 * @author Mitch Stanley <acoustep+entrustgui@fastmail.co.uk>
 */


use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Config;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;


class UsersController extends Controller
{

  protected $user;
  protected $config;
  protected $request;

  public function __construct(Config $config, Request $request)
  {
    $this->config = $config;
    $this->request = $request;
    $user_class = $this->config->get('auth.model');

    $this->user = new $user_class;
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
    $user_class = $this->config->get('auth.model');
    $user = new $user_class;

    return view('entrust-gui::users.create', compact(
      'user'
    ));
  
  }
  public function store()
  {
    $this->user->create($this->request->all());
    return redirect(route('entrust-gui.users.index'))->withSuccess(trans('entrust-gui::users.created'));
  
  }

  public function edit($id)
  {
    $user = $this->user->find($id);

    return view('entrust-gui::users.edit', compact(
      'user'
    ));
  
  }
  public function update($id)
  {
    $user = $this->user->find($id);
    $user->update($this->request->all());
    return redirect(route('entrust-gui.users.index'))->withSuccess(trans('entrust-gui::users.updated'));
  
  }
  public function destroy($id)
  {
    $this->user->destroy($id);
    return redirect(route('entrust-gui.users.index'))->withSuccess(trans('entrust-gui::users.destroyed'));
  
  }
}
