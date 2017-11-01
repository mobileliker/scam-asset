<?php

namespace App\Traits;

use Illuminate\Http\Request;


trait AttachmentTraits
{
    private function getPath()
    {
        if(isset($this->attachementPath)){
            return $this->attachementPath;
        }else{
            return 'upload/image';
        }
    }

    public function image(Request $request)
    {
        $file = $request->file('file');
        if ($file != null && $file->isValid()) {
            //$mimeType = $file -> getMimeType();
            $entension = $file->getClientOriginalExtension();
            //$pic_name = md5(date('ymdhis') . $file->getClientOriginalName()) . '.' . $entension;
            $pic_name = date('Ymdhis') . substr(md5(date('ymdhis') . $file->getClientOriginalName()), 0, 4) . '.' . $entension;
            $path = $file->move('Storage/' . $this->getPath(), $pic_name);
            $path = studly_case(str_replace("\\", "/", ucfirst($path)));
            //Log::info($path);
            return response()->json([
                'name' => $pic_name,
                'url' => '' . $path
            ]);
            //return url($path);
            //return response()->file($path);
        } else {
            abort(500, '上传失败');
        }
    }
}