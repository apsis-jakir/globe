<?php

namespace App\Http\Controllers;

use App\Models\Sms_outboxx;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class SmsOutboxxesController extends Controller
{

    /**
     * Display a listing of the sms outboxxes.
     *
     * @return Illuminate\View\View
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $smsOutboxxes = Sms_outboxx::paginate(25);

        return view('sms_outboxxes.index', compact('smsOutboxxes'));
    }

    /**
     * Show the form for creating a new sms outboxx.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('sms_outboxxes.create');
    }

    /**
     * Store a new sms outboxx in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            Sms_outboxx::create($data);

            return redirect()->route('sms_outboxxes.sms_outboxx.index')
                             ->with('success_message', 'Sms Outboxx was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified sms outboxx.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $smsOutboxx = Sms_outboxx::findOrFail($id);

        return view('sms_outboxxes.show', compact('smsOutboxx'));
    }

    /**
     * Show the form for editing the specified sms outboxx.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $smsOutboxx = Sms_outboxx::findOrFail($id);
        

        return view('sms_outboxxes.edit', compact('smsOutboxx'));
    }

    /**
     * Update the specified sms outboxx in the storage.
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
            
            $smsOutboxx = Sms_outboxx::findOrFail($id);
            $smsOutboxx->update($data);

            return redirect()->route('sms_outboxxes.sms_outboxx.index')
                             ->with('success_message', 'Sms Outboxx was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }        
    }

    /**
     * Remove the specified sms outboxx from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $smsOutboxx = Sms_outboxx::findOrFail($id);
            $smsOutboxx->delete();

            return redirect()->route('sms_outboxxes.sms_outboxx.index')
                             ->with('success_message', 'The message is successfully deleted!');

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
            'sms_receiver_number' => 'numeric|nullable',
            'sms_content' => 'string|min:1|nullable',
            'order_type' => 'string|min:1|nullable',
     
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

}
