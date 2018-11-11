<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Union;
use App\Models\Country;
use App\Models\Upazila;
use App\Models\Division;
use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;

class UnionsController extends Controller
{

    /**
     * Display a listing of the unions.
     *
     * @return Illuminate\View\View
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $unions = Union::with('country','division','district','upazila','creator','updater')->paginate(25);

        return view('unions.index', compact('unions'));
    }

    /**
     * Show the form for creating a new union.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $countries = Country::pluck('name','id')->all();
$divisions = Division::pluck('name','id')->all();
$districts = District::pluck('name','id')->all();
$upazilas = Upazila::pluck('name','id')->all();
$creators = User::pluck('name','id')->all();
$updaters = User::pluck('name','id')->all();
        
        return view('unions.create', compact('countries','divisions','districts','upazilas','creators','updaters'));
    }

    /**
     * Store a new union in the storage.
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
            Union::create($data);

            return redirect()->route('unions.union.index')
                             ->with('success_message', 'Union was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified union.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $union = Union::with('country','division','district','upazila','creator','updater')->findOrFail($id);

        return view('unions.show', compact('union'));
    }

    /**
     * Show the form for editing the specified union.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $union = Union::findOrFail($id);
        $countries = Country::pluck('name','id')->all();
$divisions = Division::pluck('name','id')->all();
$districts = District::pluck('name','id')->all();
$upazilas = Upazila::pluck('name','id')->all();
$creators = User::pluck('name','id')->all();
$updaters = User::pluck('name','id')->all();

        return view('unions.edit', compact('union','countries','divisions','districts','upazilas','creators','updaters'));
    }

    /**
     * Update the specified union in the storage.
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
            $union = Union::findOrFail($id);
            $union->update($data);

            return redirect()->route('unions.union.index')
                             ->with('success_message', 'Union was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }        
    }

    /**
     * Remove the specified union from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $union = Union::findOrFail($id);
            $union->delete();

            return redirect()->route('unions.union.index')
                             ->with('success_message', 'Union was successfully deleted!');

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
            'upazilas_id' => 'nullable',
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
