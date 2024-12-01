<?php

namespace App\Http\Controllers;

use App\Models\StudyMaterial;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Batch;
use App\Models\StudyMaterialBatch;
use App\Models\TeacherBatch;
use Illuminate\Support\Facades\Validator;
use App\Models\StudyMaterialFile;

class StudyMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $data = StudyMaterial::select('*');
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $action = "";
                    $action .= "<a href='" . route('teacher.studyMaterial.edit', $data->id) . "' class='btn btn-primary me-2 _effect--ripple waves-effect waves-light'><i class='fa fa-pencil'></i></a>";
                    $action .= "<form action='" . route('teacher.studyMaterial.destroy', $data->id) . "' method='POST' style='display:inline-block;'>
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

        return view("teacher.studyMaterial.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        //get guard teacher id 
        $teacher_id=auth()->guard('teacher')->user()->id;
        $batch_ids = TeacherBatch::where('teacher_id',$teacher_id)->pluck('batch_id');
        $batches=Batch::where('status','1')->whereIn('id',$batch_ids)->get();
        return view("teacher.studyMaterial.create",compact('batches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'visibility' => 'required|in:public,private',
            'description' => 'nullable|string|max:1000',
            'batch_ids' => 'required|array|min:1',
            'batch_ids.*' => 'integer|exists:batches,id',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $input = $request->all();
        // Create a new studyMaterial using the validated data
        DB::beginTransaction();
        try {
            $studyMaterial = new StudyMaterial();
            $studyMaterial->role_id = auth()->guard('teacher')->user()->roles->first()->id;
            $studyMaterial->created_by = auth()->guard('teacher')->user()->id;
            $studyMaterial->title = $input['title'];
            $studyMaterial->visibility = $input['visibility'];
            $studyMaterial->description =$input['description'];
            $studyMaterial->save();
            $this->syncBatch($studyMaterial,$request['batch_ids']);
            if($request->hasFile("files")) {
                $images = $request->file("files");
                $imageCount = 1;
                foreach ($images as $image) {
                    $filename = intval(microtime(true)) . "_" . ($imageCount++) . "." . $image->getClientOriginalExtension();
                    $image->move(public_path("Media/StudyMaterials"), $filename);
    
                    $file=new StudyMaterialFile();
                    $file->study_material_id= $studyMaterial->id;
                    $file->file = "Media/StudyMaterials/" . $filename;
                    $file->save();
                }
            } 

            DB::commit();
            return redirect()->route('teacher.studyMaterial.index')->with('success', 'Study Material created successfully!');
            // all good
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            \Log::error('Error creating studyMaterial: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong, please try again!');
            // something went wrong
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StudyMaterial $studyMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudyMaterial $studyMaterial)
    {
        //
        $teacher_id=auth()->guard('teacher')->user()->id;
        $batch_ids = TeacherBatch::where('teacher_id',$teacher_id)->pluck('batch_id');
        $batches=Batch::where('status','1')->whereIn('id',$batch_ids)->get();
        return view("teacher.studyMaterial.edit",compact('studyMaterial','batches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudyMaterial $studyMaterial)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'visibility' => 'required|in:public,private',
            'description' => 'nullable|string|max:1000',
            'batch_ids' => 'required|array|min:1',
            'batch_ids.*' => 'integer|exists:batches,id',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $input = $request->all();

        DB::beginTransaction();
        try {
            $studyMaterial->role_id = auth()->guard('teacher')->user()->roles->first()->id;
            $studyMaterial->created_by = auth()->guard('teacher')->user()->id;
            $studyMaterial->title = $input['title'];
            $studyMaterial->visibility = $input['visibility'];
            $studyMaterial->description =$input['description'];
            $studyMaterial->save();
            $this->syncBatch($studyMaterial,$request['batch_ids']);
            if($request->hasFile("files")) {
                $images = $request->file("files");
                $imageCount = 1;
                foreach ($images as $image) {
                    $filename = intval(microtime(true)) . "_" . ($imageCount++) . "." . $image->getClientOriginalExtension();
                    $image->move(public_path("Media/StudyMaterials"), $filename);
    
                    $file=new StudyMaterialFile();
                    $file->study_material_id= $studyMaterial->id;
                    $file->file = "Media/StudyMaterials/" . $filename;
                    $file->save();
                }
            } 

            DB::commit();
            return redirect()->route('teacher.studyMaterial.index')->with('success', 'Study Material updated successfully!');
            // all good
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            \Log::error('Error creating studyMaterial: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong, please try again!');
            // something went wrong
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudyMaterial $studyMaterial)
    {
        //
        $studyMaterial->delete();
        return redirect()->route('teacher.studyMaterial.index')->with('success', 'StudyMaterial deleted successfully!');
    }
    
    public function syncBatch($studyMaterial,$batch_ids){
        // Get current batch_ids
        $currentBatchIds = $studyMaterial->batches()->pluck('batch_id')->toArray();

        // Update or create based on request data
        foreach ($batch_ids as $batchId) {
            if (!in_array($batchId, $currentBatchIds)) {
                $studyMaterial->batches()->create(['batch_id' => $batchId]);
            }
        }

        // Delete batches that are not in request data
        $studyMaterial->batches()->whereNotIn('batch_id', $batch_ids)->delete();
    }
}
