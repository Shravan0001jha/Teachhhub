<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Validation\Rule;
use App\Models\Batch;
use App\Models\StudentBatch;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $data = Teacher::select('*');
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $action = "";
                    $action .= "<a href='" . route('admin.teacher.edit', $data->id) . "' class='btn btn-primary me-2 _effect--ripple waves-effect waves-light'><i class='fa fa-pencil'></i></a>";
                    $action .= "<form action='" . route('admin.teacher.destroy', $data->id) . "' method='POST' style='display:inline-block;'>
                                " . csrf_field() . "
                                " . method_field('DELETE') . "
                                <button type='submit' class='btn btn-danger _effect--ripple waves-effect waves-light' onclick='return confirm(\"Are you sure?\")'><i class='fa fa-trash'></i></button>
                            </form>";
                    return $action;
                })
                ->addColumn('batches',function ($data){
                    $batches = "";
                    foreach($data->batches as $batch){
                        $batches .= "<span class='badge bg-primary me-1'>" . $batch->batch->name 
                        . "</span>";
                    }
                    return $batches;
                })
                ->rawColumns(['batches','action'])
                ->make(true);
        }

        return view("admin.teacher.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $batches=Batch::where('status','1')->get();
        return view("admin.teacher.create",compact('batches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'password' => 'required|string|min:6',
        ]);

        // Create a new teacher using the validated data
        DB::beginTransaction();
        try {
            $teacher = new Teacher();
            $teacher->name = $validatedData['name'];
            $teacher->email = $validatedData['email'];
            $teacher->password = Hash::make($validatedData['password']);
            $teacher->created_by = auth()->guard('admin')->user()->id;;
            $teacher->save();
            $teacher->assignRole('teacher');
            $this->syncBatch($teacher,$request['batch_ids']);
            DB::commit();
            return redirect()->route('admin.teacher.index')->with('success', 'Teacher created successfully!');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error creating teacher: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong, please try again!');
            // something went wrong
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        //
        $batches=Batch::where('status','1')->get();
        return view("admin.teacher.edit",compact('teacher','batches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' =>  [
                'required',
                'email',
                Rule::unique('teachers')->ignore($teacher->id),
            ],
            'password' => 'nullable|string|min:6',
        ]);

        // Create a new teacher using the validated data
        DB::beginTransaction();
        try {
            $teacher->name = $validatedData['name'];
            $teacher->email = $validatedData['email'];
            if(isset($validatedData['password']) && !empty($validatedData['password'])){
                $teacher->password = Hash::make($validatedData['password']);
            }
            $teacher->save();
            $this->syncBatch($teacher,$request['batch_ids']);
            DB::commit();
            return redirect()->route('admin.teacher.index')->with('success', 'teacher edited successfully!');
            // all good
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            \Log::error('Error editing teacher: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong, please try again!');
            // something went wrong
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        //
        $teacher->delete();
        return redirect()->route('admin.teacher.index')->with('success', 'Teacher deleted successfully!');
    }
    public function syncBatch($teacher,$batch_ids){
        // Get current batch_ids
        $currentBatchIds = $teacher->batches()->pluck('batch_id')->toArray();

        // Update or create based on request data
        foreach ($batch_ids as $batchId) {
            if (!in_array($batchId, $currentBatchIds)) {
                $teacher->batches()->create(['batch_id' => $batchId]);
            }
        }

        // Delete batches that are not in request data
        $teacher->batches()->whereNotIn('batch_id', $batch_ids)->delete();
    }
}
