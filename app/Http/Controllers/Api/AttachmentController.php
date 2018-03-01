<?php

/**
 * 附件管理控制器
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/3/1
 * @description :
 * (1)基本功能；（2018/3/1）
 */

namespace App\Http\Controllers\Api;

use App\Attachment;
use App\Events\AttachmentEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use IQuery;

class AttachmentController extends Controller
{
    protected $model = Attachment::class;

    public function __construct()
    {
        $this->middleware('ability:Attachment|Method-Attachment-Index,true')->only('index');
        $this->middleware('ability:Attachment|Method-Attachment-Destroy,true')->only('destroy');
        $this->middleware('ability:Attachment|Method-Attachment-BatchDelete,true')->only('batchDelete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lists = Attachment::leftJoin('users', 'attachments.user_id', '=', 'users.id')
            ->select('attachments.*', 'users.name as user');

        if ($request->user_id != null && $request->user_id != '') {
            $lists = $lists->where('attachments.user_id', '=', $request->user_id);
        }

        if ($request->created_at_start != null && $request->created_at_start != '') {
            $lists = $lists->where('attachments.created_at', '>=', $request->created_at_start . ' 00:00:00')->where('attachments.created_at', '<=', $request->created_at_end . ' 23:59:59');
        }

        $order_params = [
            'id' => 'attachments.id',
            'created_at' => 'attachments.created_at',
            'name' => 'attachments.name',
            'path' => 'attachments.path',
            'user' => 'users.name'
        ];

        $text_params = [
            'name' => 'attachments.name',
        ];

        $lists = IQuery::ofDefault($lists, $request, $order_params, $text_params, 'attachments');

        return $lists;
    }

//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function create()
//    {
//        //
//    }

//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param  \Illuminate\Http\Request $request
//     * @return \Illuminate\Http\Response
//     */
//    public function store(Request $request)
//    {
//        //
//    }

//    /**
//     * Display the specified resource.
//     *
//     * @param  int $id
//     * @return \Illuminate\Http\Response
//     */
//    public function show($id)
//    {
//        //
//    }

//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  int $id
//     * @return \Illuminate\Http\Response
//     */
//    public function edit($id)
//    {
//        //
//    }

//    /**
//     * Update the specified resource in storage.
//     *
//     * @param  \Illuminate\Http\Request $request
//     * @param  int $id
//     * @return \Illuminate\Http\Response
//     */
//    public function update(Request $request, $id)
//    {
//        //
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $attachment = Attachment::findOrFail($id);
        if ($attachment->delete()) {
            $absolutePath = public_path($attachment->path);
            unlink($absolutePath);
            event(new AttachmentEvent('destroy', $request->getClientIp(), $attachment));
            return $attachment;
        } else {
            abort(500, '删除失败');
        }
    }
}
