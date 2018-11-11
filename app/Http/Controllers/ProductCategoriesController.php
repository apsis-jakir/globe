<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;

class ProductCategoriesController extends Controller
{

    /**
     * Display a listing of the product categories.
     *
     * @return Illuminate\View\View
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $productCategories = ProductCategory::with('productbrand','creator','updater')->paginate(25);

        return view('product_categories.index', compact('productCategories'));
    }

    /**
     * Show the form for creating a new product category.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $productBrands = ProductBrand::pluck('name','id')->all();
$creators = User::pluck('name','id')->all();
$updaters = User::pluck('name','id')->all();
        
        return view('product_categories.create', compact('productBrands','creators','updaters'));
    }

    /**
     * Store a new product category in the storage.
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
            ProductCategory::create($data);

            return redirect()->route('product_categories.product_category.index')
                             ->with('success_message', 'Product Category was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified product category.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $productCategory = ProductCategory::with('productbrand','creator','updater')->findOrFail($id);

        return view('product_categories.show', compact('productCategory'));
    }

    /**
     * Show the form for editing the specified product category.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $productCategory = ProductCategory::findOrFail($id);
        $productBrands = ProductBrand::pluck('name','id')->all();
$creators = User::pluck('name','id')->all();
$updaters = User::pluck('name','id')->all();

        return view('product_categories.edit', compact('productCategory','productBrands','creators','updaters'));
    }

    /**
     * Update the specified product category in the storage.
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
            $productCategory = ProductCategory::findOrFail($id);
            $productCategory->update($data);

            return redirect()->route('product_categories.product_category.index')
                             ->with('success_message', 'Product Category was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }        
    }

    /**
     * Remove the specified product category from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $productCategory = ProductCategory::findOrFail($id);
            $productCategory->delete();

            return redirect()->route('product_categories.product_category.index')
                             ->with('success_message', 'Product Category was successfully deleted!');

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
            'product_brands_id' => 'nullable',
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
