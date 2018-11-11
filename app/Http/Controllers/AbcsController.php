<?php

namespace App\Http\Controllers;

use App\Models\Abc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class AbcsController extends Controller
{

    /**
     * Display a listing of the abcs.
     *
     * @return Illuminate\View\View
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $abcs = Abc::paginate(25);

        return view('abcs.index', compact('abcs'));
    }

    /**
     * Show the form for creating a new abc.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('abcs.create');
    }

    /**
     * Store a new abc in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            Abc::create($data);

            return redirect()->route('abcs.abc.index')
                             ->with('success_message', 'Abc was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified abc.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $abc = Abc::findOrFail($id);

        return view('abcs.show', compact('abc'));
    }

    /**
     * Show the form for editing the specified abc.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $abc = Abc::findOrFail($id);
        

        return view('abcs.edit', compact('abc'));
    }

    /**
     * Update the specified abc in the storage.
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
            
            $abc = Abc::findOrFail($id);
            $abc->update($data);

            return redirect()->route('abcs.abc.index')
                             ->with('success_message', 'Abc was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }        
    }

    /**
     * Remove the specified abc from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $abc = Abc::findOrFail($id);
            $abc->delete();

            return redirect()->route('abcs.abc.index')
                             ->with('success_message', 'Abc was successfully deleted!');

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
            'description' => 'string|min:1|max:1000|nullable',
            'is_active' => 'boolean|nullable',
     
        ];
        
        $data = $request->validate($rules);

        $data['is_active'] = $request->has('is_active');

        return $data;
    }

}
