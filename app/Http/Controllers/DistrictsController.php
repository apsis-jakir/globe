<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Country;
use App\Models\District;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;

class DistrictsController extends Controller
{

    /**
     * Display a listing of the districts.
     *
     * @return Illuminate\View\View
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $districts = District::with('division')->paginate(25);

        return view('districts.index', compact('districts'));
    }

    /**
     * Show the form for creating a new district.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $divisions = Division::pluck('name','id')->all();
$creators = User::pluck('name','id')->all();
$updaters = User::pluck('name','id')->all();
$countries = Country::pluck('name','id')->all();
        
        return view('districts.create', compact('divisions','creators','updaters','countries'));
    }

    /**
     * Store a new district in the storage.
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
            District::create($data);

            return redirect()->route('districts.district.index')
                             ->with('success_message', 'District was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified district.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $district = District::with('division','creator','updater','country')->findOrFail($id);

        return view('districts.show', compact('district'));
    }

    /**
     * Show the form for editing the specified district.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $district = District::findOrFail($id);
        $divisions = Division::pluck('name','id')->all();
$creators = User::pluck('name','id')->all();
$updaters = User::pluck('name','id')->all();
$countries = Country::pluck('name','id')->all();

        return view('districts.edit', compact('district','divisions','creators','updaters','countries'));
    }

    /**
     * Update the specified district in the storage.
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
            $district = District::findOrFail($id);
            $district->update($data);

            return redirect()->route('districts.district.index')
                             ->with('success_message', 'District was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }        
    }

    /**
     * Remove the specified district from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $district = District::findOrFail($id);
            $district->delete();

            return redirect()->route('districts.district.index')
                             ->with('success_message', 'District was successfully deleted!');

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
            'name' => 'nullable|string|min:0|max:255',
            'divisions_id' => 'nullable',
            'description' => 'nullable|string|min:0|max:1000',
            'created_by' => 'nullable',
            'updated_by' => 'nullable',
            'is_active' => 'nullable|boolean',
            'countries_id' => 'nullable|numeric|min:0|max:4294967295',
     
        ];
        
        $data = $request->validate($rules);

        $data['is_active'] = $request->has('is_active');

        return $data;
    }

}
