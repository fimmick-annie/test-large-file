<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadFileLog;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CampaignFileUploadController extends Controller
{
    protected $allowExtensions = [
        'jpg',
        'jpeg',
        'png',

        'csv',
        'zip',
    ];

    protected $maxFilesize = 10 * 1024 * 1024;

    public function upload(Request $request)
    {
        $uniqid   = uniqid('upload_file_', true);
        $file     = $request->file('file');
        $response = [
            'status' => 'error',
        ];

        // Check uploaded file.
        if (empty($file)) {
            $response['message'] = 'Please upload a file...';

            return $response;
        }

        $originalFilename = $file->getClientOriginalName();
        $extension        = $file->getClientOriginalExtension();
        $filesize         = $file->getClientSize();

        // Check if uploaded file's extension is allowed.
        if (! in_array($extension, $this->allowExtensions)) {
            $response['message'] = 'Uploaded file\'s extension must be '.implode(', ', $this->allowExtensions).'.';

            return $response;
        }

        // Check if uploaded file' size is within max filesize.
        if ($filesize > 10 * 1024 * 1024) {
            $response['message'] = 'Uploaded file\'s size must be within 10KB.';

            return $response;
        }

        $filename = $this->filename($originalFilename);

        // Move uploaded file to upload folder.
        // This folder is not public.
        $result = $file->move(storage_path('app/uploads'), $filename);

        if ($result) {
            // Save new log for uploaded file.
            $log = new UploadFileLog;
            $log->uniqid        = $uniqid;
            $log->name          = $filename;
            $log->size          = $filesize;
            $log->extension     = $extension;
            $log->original_name = $originalFilename;
            $log->created_by    = $this->getIp();

            if ($log->save()) {
                $response['status']   = 'ok';
                $response['uniqid']   = $uniqid;
                $response['filename'] = $originalFilename;
				$response["serverFilename"] = $filename;
                return $response;
            }
        }

        $response['message'] = 'Cannot upload file! Please try again!';

        return $response;
    }

    public function moveFile($uniqid, $disk, $path = null)
    {
        $file = UploadFileLog::where('uniqid', $uniqid)->first();

        if (! $file) {
            return false;
        }

        $filename = $file->name;

        if (! is_null($path)) {
            $filename = Str::finish($path, '/').$filename;
        }

        // Get file from uploads folder.
        $uploadedFile = Storage::disk('upload')->get($file->name);

        // Put this file to the special path.
        Storage::disk($disk)->put($filename, $uploadedFile);

        // Update the record as moved.
        $file->is_moved   = true;
        $file->updated_by = 'moveFile';
        $file->save();

        // Delete this file from uploads folder.
        Storage::disk('upload')->delete($file->name);

        return $path.$file->name;
    }

    protected function filename($filename)
    {
        return now()->format('YmdHis_').md5($filename.time()).'.'.File::extension($filename);
    }

    protected function getIp()
    {
        $list = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];

        foreach ($list as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }

        return request()->ip();
    }
}
