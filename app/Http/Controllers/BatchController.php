<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Services\ZoomTokenService;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $data = Batch::select('*');
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $action = "";
                    $action .= "<a href='" . route('admin.batch.edit', $data->id) . "' class='btn btn-primary me-2 _effect--ripple waves-effect waves-light'><i class='fa fa-pencil'></i></a>";
                    $action .= "<form action='" . route('admin.batch.destroy', $data->id) . "' method='POST' style='display:inline-block;'>
                                " . csrf_field() . "
                                " . method_field('DELETE') . "
                                <button type='submit' class='btn btn-danger _effect--ripple waves-effect waves-light' onclick='return confirm(\"Are you sure?\")'><i class='fa fa-trash'></i></button>
                            </form>";
                    return $action;
                })
                ->addColumn('status', function ($data) {
                    $status="";
                    if ($data->status == 1) {
                        $status ="checked";
                    }
                    $obj = '<div class="switch form-switch-custom switch-inline form-switch-success">
                                <input class="switch-input" type="checkbox" role="switch" id="form-custom-switch-success" data-id="'.$data->id.'" '.$status.'>
                            </div>';
                    return $obj;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view("admin.batch.index");
    }
    public function create()
    {
        //
        return view("admin.batch.create");
    }

    public function store(Request $request)
    {
        //
        // dd($request->all());
        $validatedData = $request->validate([
            'name' => 'required|unique:batches',
        ]);

        // Create a new batch using the validated data
        DB::beginTransaction();
        try {
            $batch = new Batch();
            $batch->admin_id = auth()->guard('admin')->user()->id;
            $batch->name = $validatedData['name'];
            $batch->status = 1;
            $batch->save();
            DB::commit();
            return redirect()->route('admin.batch.index')->with('success', 'Batch created successfully!');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error creating batch: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong, please try again!');
            // something went wrong
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Batch $batch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Batch $batch)
    {
        //
        return view("admin.batch.edit",compact('batch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Batch $batch)
    {
        //
        $validatedData = $request->validate([
            'name' =>  [
                'required',
                Rule::unique('batches')->ignore($batch->id),
                'max:255'
            ]
        ]);

        // Create a new batch using the validated data
        DB::beginTransaction();
        try {
            $batch->name = $validatedData['name'];
            $batch->save();
            DB::commit();
            return redirect()->route('admin.batch.index')->with('success', 'batch edited successfully!');
            // all good
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            \Log::error('Error editing batch: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong, please try again!');
            // something went wrong
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Batch $batch)
    {
        //
        $batch->delete();
        return redirect()->route('admin.batch.index')->with('success', 'Batch deleted successfully!');
    }

    public function zoomtest(){
        $zoomTokenService = new ZoomTokenService();
        $accessToken = $zoomTokenService->getAccessToken();
        dd($accessToken);
    }
}
