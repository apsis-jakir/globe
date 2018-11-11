<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Upazila;
use App\Models\Country;
use App\Models\Division;
use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;

class UpazilasController extends Controller
{

    /**
     * Display a listing of the upazilas.
     *
     * @return Illuminate\View\View
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $upazilas = Upazila::with('country','division','district','creator','updater')->paginate(25);

        return view('upazilas.index', compact('upazilas'));
    }

    /**
     * Show the form for creating a new upazila.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $countries = Country::pluck('name','id')->all();
$divisions = Division::pluck('name','id')->all();
$districts = District::pluck('name','id')->all();
$creators = User::pluck('name','id')->all();
$updaters = User::pluck('name','id')->all();
        
        return view('upazilas.create', compact('countries','divisions','districts','creators','updaters'));
    }

    /**
     * Store a new upazila in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            $data['created_by'] = Auth::Id();
            Upazila::create($data);

            return redirect()->route('upazilas.upazila.index')
                             ->with('success_message', 'Upazila was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified upazila.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $upazila = Upazila::with('country','division','district','creator','updater')->findOrFail($id);

        return view('upazilas.show', compact('upazila'));
    }

    /**
     * Show the form for editing the specified upazila.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $upazila = Upazila::findOrFail($id);
        $countries = Country::pluck('name','id')->all();
$divisions = Division::pluck('name','id')->all();
$districts = District::pluck('name','id')->all();
$creators = User::pluck('name','id')->all();
$updaters = User::pluck('name','id')->all();

        return view('upazilas.edit', compact('upazila','countries','divisions','districts','creators','updaters'));
    }

    /**
     * Update the specified upazila in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        try {
            
            $data = $this->getData($request);
            $data['updated_by'] = Auth::Id();
            $upazila = Upazila::findOrFail($id);
            $upazila->update($data);

            return redirect()->route('upazilas.upazila.index')
                             ->with('success_message', 'Upazila was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }        
    }

    /**
     * Remove the specified upazila from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $upazila = Upazila::findOrFail($id);
            $upazila->delete();

            return redirect()->route('upazilas.upazila.index')
                             ->with('success_message', 'Upazila was successfully deleted!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    
    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request 
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
            'name' => 'string|min:1|max:255|nullable',
            'countries_id' => 'numeric|min:0|max:4294967295|nullable',
            'divisions_id' => 'nullable',
            'districts_id' => 'nullable',
            'description' => 'string|min:1|max:1000|nullable',
            'created_by' => 'nullable',
            'updated_by' => 'nullable',
            'is_active' => 'boolean|nullable',
     
        ];

        
        $data = $request->validate($rules);


        $data['is_active'] = $request->has('is_active');


        return $data;
    }

}
