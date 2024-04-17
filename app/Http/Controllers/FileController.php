<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
    use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;


class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userGroupId = Auth::user()->group_id;
        $files = File::where('group_id', $userGroupId)->get();
        return view('files.files', compact('files'));
    }

    public function downloadFile($fileId)
    {
        $userGroupId = Auth::user()->group_id;
        if (!$fileId) {
            Session::flash('errors', 'Error : please select avilable files');
            return redirect()->route('files');
        }

        $fileDetails = File::find($fileId);
        if ($fileDetails->group_id != $userGroupId) {
            Session::flash('errors', 'Error : You dont have permission to access to this file');
            return redirect()->route('files');
        }
        return Response::download('uplodedFiles/' . $fileDetails->path, $fileDetails->name);

        // =======
    }

    public function addFile()
    {
        // return view('files.test');
        return view('files.add-file');
    }
    public function saveFile(Request $request)
    {
        $validated =  Validator::make($request->all(), [
            'filename' => 'required|unique:files,name|max:255',
            'file' => 'required|file|mimes:doc,docx,txt,php,json,html,css,js',
        ]);
        if ($validated->fails()) {
            return redirect('addFile')
                ->withErrors($validated)
                ->withInput();
        }

        $fileName = time() . '.' . $request->file->extension();
        $request->file->move(public_path('uplodedFiles'), $fileName);
        $userGroupId = Auth::user()->group_id;

        File::create([
            'name' => $request->filename,
            'description' => $request->description,
            'group_id' => $userGroupId,
            'user_id' => 0,
            'avilable' => 1,
            'path' => $fileName
        ]);

        Session::flash('success', 'Success: Files has been uploaded successfully');
        return redirect()->route('files');
    }

    public function updateFile(Request $request)
    {
        $validated =  Validator::make($request->all(), [
            'fileId' => 'required|integer|exists:files,id',
            'file' => 'required|file|mimes:doc,docx,txt,php,json,html,css,js',
        ]);
        if ($validated->fails()) {
            return response()->json(['status' => 0, 'msg' => 'Error in postData'], 400);
        }

        $fileDetails = File::find($request->fileId);
        if (Auth::user()->group_id != $fileDetails->group_id)
            return response()->json(['status' => 0, 'msg' => 'No permission'], 400);

        $fileName = time() . '.' . $request->file->extension();
        $request->file->move(public_path('uplodedFiles'), $fileName);
        // $userGroupId = Auth::user()->group_id;
        $fileDetails->update([
            // 'user_id' => 0,
            // 'avilable' => 1,
            'path' => $fileName
        ]);
        return response()->json(['status' => 1, 'msg' => 'File has been uploded successfully'], 200);

        // File::create([
        //     'name' => $request->filename,
        //     'description' => $request->description,
        //     'group_id' => $userGroupId,
        //     'user_id' => 0,
        //     'avilable' => 1,
        //     'path' => $fileName
        // ]);

        Session::flash('success', 'Success: Files has been uploaded successfully');
        return redirect()->route('files');
    }

    public function checkInFiles(Request $request)
    {

        $userGroupId = Auth::user()->group_id;
        $fileIds = $request->fileIds;
        if (!$fileIds || empty($fileIds)) {
            Session::flash('errors', 'Error : please select avilable files');
            return redirect()->route('files');
        }
        $results = DB::table('files')
            ->whereIn('id', $fileIds)
            ->where('user_id', 0)
            ->where('avilable', 1)
            ->where('group_id', $userGroupId)
            ->get();
        if (count($results) != count($fileIds)) {
            Session::flash('errors', 'Error : please select avilable files');
            return redirect()->route('files');
        }
        DB::beginTransaction();
        try {
            $lockedRows = DB::table('files')
                ->whereIn('id', $fileIds)
                ->where('user_id', 0)
                ->where('avilable', 1)
                ->where('group_id', $userGroupId)
                ->lockForUpdate();

            $lockedRows->update(array(
                'user_id' => Auth::user()->id,
                'avilable' => 0,
            ));

            DB::commit();
        } catch (\Exception $e) {
            die("server error");
            DB::rollback();
        }
        $this->_addToReservations($fileIds, Auth::user()->id, 'checkin');
        Session::flash('success', 'Success: Files has been reservation successfully');
        return redirect()->route('files');
    }


    public function checkOutFile(Request $request)
    {
        $fileId = $request->fileId;
        $fileDetails = File::find($fileId);
        if (!$fileDetails || !$fileDetails->user_id || $fileDetails->avilable) {
            return response()->json(array('status' => 0, 'msg' => 'Error in postData'));
        }
        $fileDetails->update(array(
            'avilable' => 1,
            'user_id' => 0
        ));
        $this->_addToReservations(array($fileId), Auth::user()->id, 'checkout');
        return response()->json(array('status' => 1, 'msg' => 'File has been updated suucessfully'));
    }

    private function _addToReservations($fileIds = array(), $userId, $service)
    {
        if ($fileIds && !empty($fileIds)) {
            foreach ($fileIds as $fileId) {
                $resData = array(
                    'user_id' => $userId,
                    'file_id' => $fileId,
                    'service' => $service
                );
                DB::table('reservations')->insert($resData);
            }
        }
        return true;
    }

    public function filesAuditReport()
    {
        $query = DB::table('reservations')
            ->select('users.id AS userId', 'users.email', 'files.id AS fileId', 'files.name', 'reservations.*')
            ->join('files', 'files.id', '=', 'reservations.file_id')
            ->join('users', 'users.id', '=', 'reservations.user_id');
        $reservations = $query->get();
        $files = File::get();
        $users = User::get();
        return view('files.files_report', compact('reservations', 'files', 'users'));

        var_dump(json_encode($reservations));
    }

    public function getFilesAuditReport(Request $request)
    {
        $fileId = ($request->fileId) ? $request->fileId : 0;
        $userId = ($request->userId) ? $request->userId : 0;
        $query = DB::table('reservations')
            ->select('users.id AS userId', 'users.email', 'files.id AS fileId', 'files.name', 'reservations.*')
            ->join('files', 'files.id', '=', 'reservations.file_id')
            ->join('users', 'users.id', '=', 'reservations.user_id');
        if ($fileId)
            $query->where('files.id', $fileId);
        if ($userId)
            $query->where('users.id', $userId);
        $reservations = $query->get();
        $files = File::get();
        $users = User::get();
        return view('files.files_report', compact('reservations', 'files', 'users'));

        var_dump(json_encode($reservations));
    }
}
