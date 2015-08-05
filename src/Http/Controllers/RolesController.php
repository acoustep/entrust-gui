<?php namespace Acoustep\EntrustGui\Http\Controllers;
/**
 * 
 * @author Mitch Stanley <acoustep+entrustgui@fastmail.co.uk>
 */


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;


class RolesController extends Controller
{

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('entrust-gui::roles.index');
	}
}


