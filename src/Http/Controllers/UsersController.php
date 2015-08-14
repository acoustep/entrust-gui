<?php namespace Acoustep\EntrustGui\Http\Controllers;

/**
 *
 * @author Mitch Stanley <acoustep+entrustgui@fastmail.co.uk>
 */


use App\Http\Controllers\Controller;
use Acoustep\EntrustGui\Gateways\UserGateway;
use Illuminate\Http\Request;
use Watson\Validating\ValidationException;
use Illuminate\Config\Repository as Config;

class UsersController extends Controller
{

    protected $gateway;
    protected $request;
    protected $role;
    protected $config;

    public function __construct(Request $request, UserGateway $gateway, Config $config)
    {
        $this->config = $config;
        $this->request = $request;
        $this->gateway = $gateway;
        $role_class = $this->config->get('entrust.role');
        $this->role = new $role_class;
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index()
    {
        $users = $this->gateway->paginate($this->config->get('entrust-gui.pagination.users'));

        return view('entrust-gui::users.index', compact(
            'users'
        ));
    }

    public function create()
    {
        $user_class = $this->config->get('auth.model');
        $user = new $user_class;
        $roles = $this->role->lists('name', 'id');

        return view('entrust-gui::users.create', compact(
            'user',
            'roles'
        ));
  
    }

    public function store()
    {
        try {
            $user = $this->gateway->create($this->request);
        } catch (ValidationException $e) {
            return back()->withErrors($e->getErrors());
        }
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
        try {
            $this->gateway->update($this->request, $id);
        } catch (ValidationException $e) {
            return back()->withErrors($e->getErrors());
        }
        return redirect(route('entrust-gui::users.index'))->withSuccess(trans('entrust-gui::users.updated'));
    }

    public function destroy($id)
    {
        $this->gateway->delete($id);
        return redirect(route('entrust-gui::users.index'))->withSuccess(trans('entrust-gui::users.destroyed'));
    }
}
