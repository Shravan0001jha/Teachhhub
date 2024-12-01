<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Batch;
use App\Models\StudentBatch;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $data = Student::select('*');
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $action = "";
                    $action .= "<a href='" . route('admin.student.edit', $data->id) . "' class='btn btn-primary me-2 _effect--ripple waves-effect waves-light'><i class='fa fa-pencil'></i></a>";
                    $action .= "<form action='" . route('admin.student.destroy', $data->id) . "' method='POST' style='display:inline-block;'>
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

        return view("admin.student.index");
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $batches=Batch::where('status','1')->get();
        return view("admin.student.create",compact('batches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|string|min:6',
        ]);

        // Create a new student using the validated data
        DB::beginTransaction();
        try {
            $student = new Student();
            $student->name = $validatedData['name'];
            $student->email = $validatedData['email'];
            $student->password = Hash::make($validatedData['password']);
            $student->created_by = auth()->guard('admin')->user()->id;
            $student->save();
         
            $student->assignRole('student');

            $this->syncBatch($student,$request['batch_ids']);

            DB::commit();
            return redirect()->route('admin.student.index')->with('success', 'Student created successfully!');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error creating student: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong, please try again!');
            // something went wrong
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
        $batches=Batch::where('status','1')->get();
        return view("admin.student.edit",compact('student','batches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' =>  [
                'required',
                'email',
                Rule::unique('students')->ignore($student->id),
            ],
            'password' => 'nullable|string|min:6',
        ]);

        // Create a new student using the validated data
        DB::beginTransaction();
        try {
            $student->name = $validatedData['name'];
            $student->email = $validatedData['email'];
            if(isset($validatedData['password']) && !empty($validatedData['password'])){
                $student->password = Hash::make($validatedData['password']);
            }
            $student->save();

            $this->syncBatch($student,$request['batch_ids']);

            DB::commit();
            return redirect()->route('admin.student.index')->with('success', 'student edited successfully!');
            // all good
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            \Log::error('Error editing student: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong, please try again!');
            // something went wrong
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
        $student->delete();
        return redirect()->route('admin.student.index')->with('success', 'Student deleted successfully!');
    }

    public function syncBatch($student,$batch_ids){
        // Get current batch_ids
        $currentBatchIds = $student->batches()->pluck('batch_id')->toArray();

        // Update or create based on request data
        foreach ($batch_ids as $batchId) {
            if (!in_array($batchId, $currentBatchIds)) {
                $student->batches()->create(['batch_id' => $batchId]);
            }
        }

        // Delete batches that are not in request data
        $student->batches()->whereNotIn('batch_id', $batch_ids)->delete();
    }
}
