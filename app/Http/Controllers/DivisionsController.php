<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Country;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;

class DivisionsController extends Controller
{

    /**
     * Display a listing of the divisions.
     *
     * @return Illuminate\View\View
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $divisions = Division::with('country','creator','updater')->paginate(25);

        return view('divisions.index', compact('divisions'));
    }

    /**
     * Show the form for creating a new division.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $countries = Country::pluck('name','id')->all();
$creators = User::pluck('name','id')->all();
$updaters = User::pluck('name','id')->all();
        
        return view('divisions.create', compact('countries','creators','updaters'));
    }

    /**
     * Store a new division in the storage.
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
            Division::create($data);

            return redirect()->route('divisions.division.index')
                             ->with('success_message', 'Division was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified division.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $division = Division::with('country','creator','updater')->findOrFail($id);

        return view('divisions.show', compact('division'));
    }

    /**
     * Show the form for editing the specified division.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $division = Division::findOrFail($id);
        $countries = Country::pluck('name','id')->all();
$creators = User::pluck('name','id')->all();
$updaters = User::pluck('name','id')->all();

        return view('divisions.edit', compact('division','countries','creators','updaters'));
    }

    /**
     * Update the specified division in the storage.
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
            $division = Division::findOrFail($id);
            $division->update($data);

            return redirect()->route('divisions.division.index')
                             ->with('success_message', 'Division was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }        
    }

    /**
     * Remove the specified division from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $division = Division::findOrFail($id);
            $division->delete();

            return redirect()->route('divisions.division.index')
                             ->with('success_message', 'Division was successfully deleted!');

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
