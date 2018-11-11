<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Region;
use App\Models\Territory;
use Illuminate\Http\Request;
use App\Models\DistributionHouse;
use App\Http\Controllers\Controller;
use Exception;
use Auth;
use DB;

class DistributionHousesController extends Controller
{

    /**
     * Display a listing of the distribution houses.
     *
     * @return Illuminate\View\View
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $distributionHouses = DistributionHouse::select('distribution_houses.*',
            DB::raw('(select COUNT(DISTINCT raso.id) from users raso where raso.distribution_house_id=distribution_houses.id AND raso.user_type="market")  as taso'),
            DB::raw('(select COUNT(DISTINCT rr.id) from routes rr where rr.distribution_houses_id=distribution_houses.id)  as aroute'))
            ->groupBy('distribution_houses.id')->with('zone','region','territory')->paginate(25);

        return view('distribution_houses.index', compact('distributionHouses'));
    }

    /**
     * Show the form for creating a new distribution house.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $zones = Zone::pluck('zone_name','id')->all();
$regions = Region::pluck('region_name','id')->all();
$territories = Territory::pluck('territory_name','id')->all();
        
        return view('distribution_houses.create', compact('zones','regions','territories'));
    }

    /**
     * Store a new distribution house in the storage.
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
            DistributionHouse::create($data);

            return redirect()->route('distribution_houses.distribution_house.index')
                             ->with('success_message', 'Distribution House was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified distribution house.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $distributionHouse = DistributionHouse::with('zone','region','territory')->findOrFail($id);

        return view('distribution_houses.show', compact('distributionHouse'));
    }

    /**
     * Show the form for editing the specified distribution house.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $distributionHouse = DistributionHouse::findOrFail($id);
        $zones = Zone::pluck('zone_name','id')->all();
$regions = Region::pluck('region_name','id')->all();
$territories = Territory::pluck('territory_name','id')->all();

        return view('distribution_houses.edit', compact('distributionHouse','zones','regions','territories'));
    }

    /**
     * Update the specified distribution house in the storage.
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
            $distributionHouse = DistributionHouse::findOrFail($id);
            $distributionHouse->update($data);

            return redirect()->route('distribution_houses.distribution_house.index')
                             ->with('success_message', 'Distribution House was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }        
    }

    /**
     * Remove the specified distribution house from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $distributionHouse = DistributionHouse::findOrFail($id);
            $distributionHouse->delete();

            return redirect()->route('distribution_houses.distribution_house.index')
                             ->with('success_message', 'Distribution House was successfully deleted!');

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
            'zones_id' => 'required',
            'regions_id' => 'required',
            'territories_id' => 'required',
            'market_name' => 'required|string|min:1|max:255',
            'code' => 'required|string|min:1|max:255',
            'point_name' => 'required|string|min:1|max:255',
     
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
