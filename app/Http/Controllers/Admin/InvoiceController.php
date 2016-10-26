<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Invoice;
use IQuery;

class InvoiceController extends Controller
{
    //
    public function index(Request $request)
    {
        $request->flash();

        $invoices = Invoice::whereRaw('1 = 1');

        //文本查询
        $query_text=$request->input('query_text');
        if($query_text != null && $request != ''){
            $texts=  explode(' ', $query_text);
            foreach($texts as $text)
            {
                $invoices = $invoices->where('name', 'like', '%'.$text.'%');
            }

        }

        $post_date_start=$request->input('post_date_start');
        if($post_date_start != null && $post_date_start != ''){
            $invoices = $invoices->where('post_date', '>=', $post_date_start);
        }

        $post_date_end = $request->input('post_date_end');
        if($post_date_end != null && $post_date_end != ''){
            $invoices = $invoices->where('post_date', '<=', $post_date_end);
        }

        $type = $request->input('type');
        if($type != null && $type != ''){
            $invoices = $invoices->where('type', '=', $type);
        }


        IQuery::ofOrder($invoices, $request);


        $invoices = $invoices->paginate(10);

        if($invoices == null || count($invoices) == 0){
            return view(config('app.theme').'.admin.invoice.index')->withInvoices($invoices)->with('status', '查询结果为空');
        }else{
            return view(config('app.theme').'.admin.invoice.index')->withInvoices($invoices);
        }
    }

    public function export($id)
    {
        $invoice = Invoice::find($id);
        $invoice->export();
    }
}
