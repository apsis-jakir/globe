<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Models\product_brand;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;

class ProductBrandsController extends Controller
{

    /**
     * Display a listing of the product brands.
     *
     * @return Illuminate\View\View
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $productBrands = product_brand::paginate(25);

        return view('product_brands.index', compact('productBrands'));
    }

    /**
     * Show the form for creating a new product brand.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $creators = User::pluck('name','id')->all();
$updaters = User::pluck('name','id')->all();
        
        return view('product_brands.create', compact('creators','updaters'));
    }

    /**
     * Store a new product brand in the storage.
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
            product_brand::create($data);

            return redirect()->route('product_brands.product_brand.index')
                             ->with('success_message', 'Product Brand was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified product brand.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $productBrand = product_brand::with('creator','updater')->findOrFail($id);

        return view('product_brands.show', compact('productBrand'));
    }

    /**
     * Show the form for editing the specified product brand.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $productBrand = product_brand::findOrFail($id);
        $creators = User::pluck('name','id')->all();
$updaters = User::pluck('name','id')->all();

        return view('product_brands.edit', compact('productBrand','creators','updaters'));
    }

    /**
     * Update the specified product brand in the storage.
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
            $productBrand = product_brand::findOrFail($id);
            $productBrand->update($data);

            return redirect()->route('product_brands.product_brand.index')
                             ->with('success_message', 'Product Brand was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }        
    }

    /**
     * Remove the specified product brand from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $productBrand = product_brand::findOrFail($id);
            $productBrand->delete();

            return redirect()->route('product_brands.product_brand.index')
                             ->with('success_message', 'Product Brand was successfully deleted!');

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
            'description' => 'nullable|string|min:0|max:1000',
            'created_by' => 'nullable',
            'updated_by' => 'nullable',
            'is_active' => 'nullable|boolean',
     
        ];
        
        $data = $request->validate($rules);

        $data['is_active'] = $request->has('is_active');

        return $data;
    }

}
