<?php

namespace App\Http\Controllers;

use App\Models\meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\ZoomToken;
use App\Services\ZoomService;
use Carbon\Carbon;
use Symfony\Component\CssSelector\XPath\Extension\FunctionExtension;
use Yajra\DataTables\Facades\DataTables;

class MeetingController extends Controller
{
    protected $zoom;
    protected $zoomToken;
    protected $zoomService;
    public function __construct(ZoomController $zoom,ZoomService $zoomService)
    {
        $this->zoom = $zoom;
        $this->zoomService = $zoomService;
        $this->zoomToken = ZoomToken::latest()->first();
    }

    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $data = Meeting::where('teacher_id',auth()->guard('teacher')->user()->id)->get();
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $action = "";
                    // $action .= "<a href='" . route('teacher.meeting.edit', $data->id) . "' class='btn btn-primary me-2 _effect--ripple waves-effect waves-light'><i class='fa fa-pencil'></i></a>";
                    $action .= "<a href='".route('teacher.meeting.show',$data->id)."' class='btn btn-primary me-2 _effect--ripple waves-effect waves-light'><i class='fa fa-eye'></i></a>";
                    $action .= "<form action='" . route('teacher.meeting.destroy', $data->id) . "' method='POST' style='display:inline-block;'>
                                " . csrf_field() . "
                                " . method_field('DELETE') . "
                                <button type='submit' class='btn btn-danger _effect--ripple waves-effect waves-light' onclick='return confirm(\"Are you sure?\")'><i class='fa fa-trash'></i></button>
                            </form>";
                    return $action;
                })
                ->addColumn('batch',function($data){
                    return $data->batch->name;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view("teacher.meeting.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view("teacher.meeting.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string|max:255',
            'start_time' => 'required',
            'batch' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $input = $request->all();
        $startTime = Carbon::create($request->start_time, 'Asia/Kolkata'); // Local time in 'Asia/Kolkata' timezone
        $startTimeUTC = $startTime->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z'); // Convert to UTC and format as ISO 8601
        // Create a new meeting using the validated data
        DB::beginTransaction();
        try {
            $accessToken = $this->zoomToken->access_token;
            $duration = 30;
            // If token is missing, redirect to authorization
            if (!$accessToken) {
                return 'error';
                // return redirect()->route('home')->with('error', 'You need to authorize Zoom first.');
            }
            $topic = $request->topic;
            $start_time = $request->start_time;
            // Prepare meeting data
            $meetingData = [
                'topic' => $topic,
                'type' => 2, // Scheduled meeting
                'start_time' => $startTimeUTC, // ISO 8601 format required by Zoom
                'duration' => $duration, // Meeting duration in minutes
                'timezone' => 'UTC', // Optional timezone
            ];
            $meeting = $this->zoomService->createMeeting($accessToken, $meetingData);
            // dd($meeting['uuid'],$request->all());
            if(!empty($meeting['uuid'])){
                $record = new Meeting();
                $record->teacher_id = auth()->guard('teacher')->user()->id;
                $record->uuid = $meeting['uuid'];
                $record->zoom_id = $meeting['id'];
                $record->host_id = $meeting['host_id'];
                $record->topic = $meeting['topic'];
                $record->status = $meeting['status'];
                $record->start_time = $meeting['start_time'];
                $record->duration = $meeting['duration'];
                $record->start_url = $meeting['start_url'];
                $record->join_url = $meeting['join_url'];
                $record->password = $meeting['password'];
                $record->batch_id = $request->batch;
                $record->response = json_encode($meeting);
                $record->save();
            }
            DB::commit();
            return redirect()->route('teacher.meeting.index')->with('success', 'Meeting Created Successfully!');
            // all good
        }
        catch (\GuzzleHttp\Exception\RequestException $e) {
            // Check if the error is due to an expired token
            if ($e->getResponse()->getStatusCode() == 401) {
                // dd($e,'exception');
                $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);

                if (isset($responseBody['code']) && $responseBody['code'] == 124) {
                    return redirect()->route('teacher.meeting.index')->with('error', 'try to refresh Zoom access token');
                    // Access token expired, refresh the token using your existing method
                    $isRefreshed = $this->zoom->refreshAccessToken();
                    if ($isRefreshed) {
                        // Retry the request after token refresh
                        $response = $this->store($request);

                        // Debug: Dump the response after refreshing the token
                        dd($response,'exception1');
                    } else {
                        // Handle failure to refresh token (optional)
                        return redirect()->route('teacher.meeting.index')->with('error', 'Unable to refresh Zoom access token');
                    }
                }
            }

            // If it's not an expired token error, rethrow the exception
            throw $e;
        }
        catch (\Exception $e) {
            // dd($e,'meeting');
            DB::rollback();
            \Log::error('Error creating meeting: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong, please try again!');
            // something went wrong
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(meeting $meeting)
    {
        //
        return view("teacher.meeting.show",compact('meeting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(meeting $meeting)
    {
        //
        return view("teacher.meeting.edit",compact('meeting','batches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, meeting $meeting)
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
            $meeting->role_id = auth()->guard('teacher')->user()->roles->first()->id;
            $meeting->created_by = auth()->guard('teacher')->user()->id;
            $meeting->title = $input['title'];
            $meeting->visibility = $input['visibility'];
            $meeting->description =$input['description'];
            $meeting->save();
            $this->syncBatch($meeting,$request['batch_ids']);
            if($request->hasFile("files")) {
                $images = $request->file("files");
                $imageCount = 1;
                foreach ($images as $image) {
                    $filename = intval(microtime(true)) . "_" . ($imageCount++) . "." . $image->getClientOriginalExtension();
                    $image->move(public_path("Media/Meetings"), $filename);

                    $file=new MeetingFile();
                    $file->study_material_id= $meeting->id;
                    $file->file = "Media/Meetings/" . $filename;
                    $file->save();
                }
            }

            DB::commit();
            return redirect()->route('teacher.meeting.index')->with('success', 'Study Material updated successfully!');
            // all good
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            \Log::error('Error creating meeting: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong, please try again!');
            // something went wrong
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(meeting $meeting)
    {
        //
        $meeting->delete();
        return redirect()->route('teacher.meeting.index')->with('success', 'Meeting deleted successfully!');
    }

    public function studentGetMeeting(Request $request){
        if ($request->ajax()) {
            $batchIds = auth()->guard('student')->user()->batches->pluck('batch_id');
            $data = Meeting::whereIn('batch_id',$batchIds)->get();
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $action = "";
                    // $action .= "<a href='" . route('teacher.meeting.edit', $data->id) . "' class='btn btn-primary me-2 _effect--ripple waves-effect waves-light'><i class='fa fa-pencil'></i></a>";
                    $action .= "<a href='".route('student.show-meeting',$data->id)."' class='btn btn-primary me-2 _effect--ripple waves-effect waves-light'><i class='fa fa-eye'></i></a>";
                    // $action .= "<form action='" . route('teacher.meeting.destroy', $data->id) . "' method='POST' style='display:inline-block;'>
                    //             " . csrf_field() . "
                    //             " . method_field('DELETE') . "
                    //             <button type='submit' class='btn btn-danger _effect--ripple waves-effect waves-light' onclick='return confirm(\"Are you sure?\")'><i class='fa fa-trash'></i></button>
                    //         </form>";
                    return $action;
                })
                ->addColumn('batch',function($data){
                    return $data->batch->name;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view("student.meeting.index");
    }
    public function studentShowMeeting(Meeting $meeting){
        return view("student.meeting.show",compact('meeting'));
    }
}
