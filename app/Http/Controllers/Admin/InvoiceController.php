<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Invoice;

class InvoiceController extends Controller
{
    //
    public function index()
    {
        $invoices = Invoice::paginate(10);
        return view(config('app.theme').'.admin.invoice.index')->withInvoices($invoices);
    }

    public function export($id)
    {
        $invoice = Invoice::find($id);
        $invoice->export();
    }
}
