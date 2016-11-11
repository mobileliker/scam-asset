<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Asset;
use Auth;
use Excel;

class Invoice extends Model
{
    //

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public static function generate(Asset $asset, $number)
    {
        $m_invoice = new Invoice;

        $m_invoice->post_date = $asset->post_date;
        $m_invoice->number = $number;
        $m_invoice->name = $asset->name;
        $m_invoice->serial = $asset->serial;
        $m_invoice->course = $asset->course;
        $m_invoice->model = $asset->model;
        $m_invoice->size = $asset->size;
        $m_invoice->consumer_company = $asset->consumer_company;
        $m_invoice->factory = $asset->factory;
        $m_invoice->provider = $asset->provider;
        $m_invoice->country = $asset->country;
        $m_invoice->storage_location = $asset->storage_location;
        $m_invoice->application = $asset->application;
        $m_invoice->invoice = $asset->invoice;
        $m_invoice->purchase_number = $asset->purchase_number;
        $m_invoice->purchase_date = $asset->purchase_date;
        $m_invoice->card = $asset->card;
        $m_invoice->price = $asset->price;
        $m_invoice->amount = $asset->amount;
        $m_invoice->sum = $asset->sum;
        $m_invoice->entry = $asset->entry;
        $m_invoice->consumer = $asset->consumer->name;
        $m_invoice->handler = $asset->handler->name;
        $m_invoice->user_id = Auth::user()->id;

        $m_invoice->save();

        return $m_invoice;
    }

    public function export()
    {

        $filePath = resource_path('assets/template/export.xls');
        $distPath = storage_path('excel/exports/'.$this->post_date.$this->serial.'.xls');
        copy($filePath, $distPath);

        Excel::load($filePath, function($reader) {

            $post_date = $this->post_date;
            $number = $this->number;
            $name = $this->name;
            $serial = $this->serial;
            $course = $this->course;
            $model = $this->model;
            $size = $this->size;
            $consumer_company = $this->consumer_company;
            $factory = $this->factory;
            $provider = $this->provider;
            $country = $this->country;
            $storage_location = $this->storage_location;
            $application = $this->application;
            $invoice = $this->invoice;
            $purchase_number = $this->purchase_number;
            $purchase_date = $this->purchase_date;
            $card = $this->card;
            $price = $this->price;
            $amount = $this->amount;
            $sum = $this->sum;
            $entry = $this->entry;

            $sheet = $reader->getActiveSheet();
            $sheet->setCellValue('B2', $post_date);
            $sheet->setCellValue('J2', $number);
            $sheet->setCellValue('B3', $name);
            $sheet->setCellValue('F3', $serial);
            $sheet->setCellValue('I3', $course);
            $sheet->setCellValue('B4', $model);
            $sheet->setCellValue('F4', $size);
            $sheet->setCellValue('I4', $consumer_company);
            $sheet->setCellValue('B5', $factory);
            $sheet->setCellValue('F5', $provider);
            $sheet->setCellValue('I5', $country);
            $sheet->setCellValue('B6', $storage_location);
            $sheet->setCellValue('F6', $application);
            $sheet->setCellValue('I6', $invoice);
            $sheet->setCellValue('B7', $purchase_number);
            $sheet->setCellValue('F7', $purchase_date);
            $sheet->setCellValue('I7', $card);
            $sheet->setCellValue('B8', $price);
            $sheet->setCellValue('F8', $amount);
            $sheet->setCellValue('I8', $sum);
            $sheet->setCellValue('D10', $entry);

        })->export('xls');
    }
    public function store()
    {

        $filePath = resource_path('assets/template/export.xls');
        $distPath = storage_path('excel/exports/'.$this->post_date.$this->serial.'.xls');
        copy($filePath, $distPath);

        Excel::load($filePath, function($reader) {

            $post_date = $this->post_date;
            $number = $this->number;
            $name = $this->name;
            $serial = $this->serial;
            $course = $this->course;
            $model = $this->model;
            $size = $this->size;
            $consumer_company = $this->consumer_company;
            $factory = $this->factory;
            $provider = $this->provider;
            $country = $this->country;
            $storage_location = $this->storage_location;
            $application = $this->application;
            $invoice = $this->invoice;
            $purchase_number = $this->purchase_number;
            $purchase_date = $this->purchase_date;
            $card = $this->card;
            $price = $this->price;
            $amount = $this->amount;
            $sum = $this->sum;
            $entry = $this->entry;

            $sheet = $reader->getActiveSheet();
            $sheet->setCellValue('B2', $post_date);
            $sheet->setCellValue('J2', $number);
            $sheet->setCellValue('B3', $name);
            $sheet->setCellValue('F3', $serial);
            $sheet->setCellValue('I3', $course);
            $sheet->setCellValue('B4', $model);
            $sheet->setCellValue('F4', $size);
            $sheet->setCellValue('I4', $consumer_company);
            $sheet->setCellValue('B5', $factory);
            $sheet->setCellValue('F5', $provider);
            $sheet->setCellValue('I5', $country);
            $sheet->setCellValue('B6', $storage_location);
            $sheet->setCellValue('F6', $application);
            $sheet->setCellValue('I6', $invoice);
            $sheet->setCellValue('B7', $purchase_number);
            $sheet->setCellValue('F7', $purchase_date);
            $sheet->setCellValue('I7', $card);
            $sheet->setCellValue('B8', $price);
            $sheet->setCellValue('F8', $amount);
            $sheet->setCellValue('I8', $sum);
            $sheet->setCellValue('D10', $entry);

        })->store('xls');
    }
}
