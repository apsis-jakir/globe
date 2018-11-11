<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Auth;
use DB;

class RegionsController extends Controller
{

    /**
     * Display a listing of the regions.
     *
     * @return Illuminate\View\View
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $regions = Region::select('regions.*',DB::raw('COUNT(DISTINCT distribution_houses.territories_id) as tterritory'),
            DB::raw('(select COUNT(DISTINCT db.id) from distribution_houses db where db.regions_id=regions.id)  as tdbhouse'),
            DB::raw('(select COUNT(DISTINCT raso.id) from users raso where raso.regions_id=regions.id AND raso.user_type="market")  as taso'),
            DB::raw('(select COUNT(DISTINCT rr.id) from routes rr left join distribution_houses rdb on rdb.id=rr.distribution_houses_id where rdb.regions_id=regions.id)  as aroute'))

            ->leftJoin('distribution_houses','distribution_houses.regions_id','=','regions.id')
            ->groupBy('regions.id')->paginate(25);

        return view('regions.index', compact('regions'));
    }

    /**
     * Show the form for creating a new region.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('regions.create');
    }

    /**
     * Store a new region in the storage.
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
            Region::create($data);

            return redirect()->route('regions.region.index')
                             ->with('success_message', 'Region was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified region.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $region = Region::findOrFail($id);

        return view('regions.show', compact('region'));
    }

    /**
     * Show the form for editing the specified region.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $region = Region::findOrFail($id);
        

        return view('regions.edit', compact('region'));
    }

    /**
     * Update the specified region in the storage.
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
            $region = Region::findOrFail($id);
            $region->update($data);

            return redirect()->route('regions.region.index')
                             ->with('success_message', 'Region was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }        
    }

    /**
     * Remove the specified region from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $region = Region::findOrFail($id);
            $region->delete();

            return redirect()->route('regions.region.index')
                             ->with('success_message', 'Region was successfully deleted!');

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
            'region_name' => 'required|string|min:1|max:255',
     
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
