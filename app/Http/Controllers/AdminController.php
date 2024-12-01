<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $data = Admin::select('*');
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $action = "";
                    $action .= "<a href='" . route('superadmin.admin.edit', $data->id) . "' class='btn btn-primary me-2 _effect--ripple waves-effect waves-light'><i class='fa fa-pencil'></i></a>";
                    $action .= "<form action='" . route('superadmin.admin.destroy', $data->id) . "' method='POST' style='display:inline-block;'>
                                " . csrf_field() . "
                                " . method_field('DELETE') . "
                                <button type='submit' class='btn btn-danger _effect--ripple waves-effect waves-light' onclick='return confirm(\"Are you sure?\")'><i class='fa fa-trash'></i></button>
                            </form>";
                    return $action;
                })
                ->make(true);
        }

        return view("superadmin.admin.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view("superadmin.admin.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6',
        ]);

        // Create a new admin using the validated data
        DB::beginTransaction();
        try {
            $admin = new Admin();
            $admin->name = $validatedData['name'];
            $admin->email = $validatedData['email'];
            $admin->password = Hash::make($validatedData['password']);
            $admin->created_by = auth()->guard('superadmin')->user()->id;;
            $admin->save();
            $admin->assignRole('admin');
            DB::commit();
            return redirect()->route('superadmin.admin.index')->with('success', 'Admin created successfully!');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error creating admin: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong, please try again!');
            // something went wrong
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        //
        return view("superadmin.admin.edit",compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' =>  [
                'required',
                'email',
                Rule::unique('admins')->ignore($admin->id),
            ],
            'password' => 'nullable|string|min:6',
        ]);

        // Create a new admin using the validated data
        DB::beginTransaction();
        try {
            $admin->name = $validatedData['name'];
            $admin->email = $validatedData['email'];
            if(isset($validatedData['password']) && !empty($validatedData['password'])){
                $admin->password = Hash::make($validatedData['password']);
            }
            $admin->save();
            DB::commit();
            return redirect()->route('superadmin.admin.index')->with('success', 'Admin edited successfully!');
            // all good
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            \Log::error('Error editing admin: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong, please try again!');
            // something went wrong
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //
        $admin->delete();
        return redirect()->route('superadmin.admin.index')->with('success', 'Admin deleted successfully!');
    }
}
