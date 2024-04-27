<?php

namespace App\Http\Controllers;

use App\Traits\APIResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;

class UploadFileS3Controller extends Controller
{

    use APIResponse;

    public function uploadFileToS3(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $name = time() . $file->getClientOriginalName();
            $filePath = 'linebot/' . $name;
            
            // Upload file to S3
            $data = Storage::disk('s3')->put($filePath, file_get_contents($file));
    
            // Set ACL to public-read
            Storage::disk('s3')->setVisibility($filePath, 'public');
    
            $url = Storage::disk('s3')->url($filePath);
        }
    
        $data = [
            'url' => $url ?? null
        ];
    
        return $this->responseSuccessWithData($data, 'Upload file to S3 successfully !');
    }

    public function deleteFileS3(Request $request)
    {
        // linebot/1710318263_bachkhoa.png
        Storage::disk('s3')->delete($request->key_image);

        return $this->responseSuccess('Image was deleted successfully !');
    }

}
