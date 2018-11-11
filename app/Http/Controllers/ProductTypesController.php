<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Auth;

class ProductTypesController extends Controller
{

    /**
     * Display a listing of the product types.
     *
     * @return Illuminate\View\View
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $productTypes = ProductType::paginate(25);

        return view('product_types.index', compact('productTypes'));
    }

    /**
     * Show the form for creating a new product type.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('product_types.create');
    }

    /**
     * Store a new product type in the storage.
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
            ProductType::create($data);

            return redirect()->route('product_types.product_type.index')
                             ->with('success_message', 'Product Type was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified product type.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $productType = ProductType::findOrFail($id);

        return view('product_types.show', compact('productType'));
    }

    /**
     * Show the form for editing the specified product type.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $productType = ProductType::findOrFail($id);
        

        return view('product_types.edit', compact('productType'));
    }

    /**
     * Update the specified product type in the storage.
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
            $productType = ProductType::findOrFail($id);
            $productType->update($data);

            return redirect()->route('product_types.product_type.index')
                             ->with('success_message', 'Product Type was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }        
    }

    /**
     * Remove the specified product type from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $productType = ProductType::findOrFail($id);
            $productType->delete();

            return redirect()->route('product_types.product_type.index')
                             ->with('success_message', 'Product Type was successfully deleted!');

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
            'category_name' => 'required|string|min:1|max:255',
            'is_active' => 'nullable|boolean',
     
        ];
        
        $data = $request->validate($rules);

        $data['is_active'] = $request->has('is_active');

        return $data;
    }

}
