<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\EmployeeServiceNotification;
use App\Models\Customer;
use App\Models\CustomerService;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\ProductService;
use App\Models\ServiceProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CustomerServiceController extends Controller
{
    public function index(Request $request)
    {

        if (\Auth::user()->can('manage customer services')) {
            if (Auth::user()->type == 'client') {
                $services = CustomerService::where('customer_id', '=', \Auth::user()->customer->id)->get();
            } else {
                $services = CustomerService::all();
            }

            $products = ProductService::get()->pluck('name', 'id');

            return view('customer_services.index', compact('services', 'products'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create customer services')) {
            // $customers = User::where('type', '=', 'client')->get()->pluck('name', 'id');
            $customers = Customer::get()->pluck('name', 'id');
            $customers->prepend('Select Customer', '');
            $employees = Employee::all()->pluck('name', 'id');
            $employees->prepend('Select Employee', '');

            return view('customer_services.create', compact('customers', 'employees'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function store(Request $request)
    {

        if (\Auth::user()->can('create customer services')) {

            if (\Auth::user()->type == 'client') {
                $rules = [
                    'phone_number' => 'required',
                    'address' => 'required',
                ];
            } else {
                $rules = [
                    'customer_id' => 'required',
                    'employee_id' => 'required',
                    'phone_number' => 'required',
                    'address' => 'required',
                    'due_date' => 'required',
                    'status' => 'required'
                ];
            }

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('customer_services.index')->with('error', $messages->first());
            }

            $customerService                      = new CustomerService();
            if (\Auth::user()->type != 'client') {
                $customerService->customer_id         = $request->customer_id;
            } else {
                $customerService->customer_id         = \Auth::user()->customer->id;
            }
            $customerService->employee_id         = $request->employee_id;
            $customerService->phone_number        = $request->phone_number;
            $customerService->address             = $request->address;
            $customerService->description         = $request->description;
            $customerService->due_date            = $request->due_date;
            $customerService->service_charge      = $request->service_charge;
            if (\Auth::user()->type != 'client') {
                $customerService->status              = $request->status;
                $customerService->created_by          = \Auth::user()->creatorId();
            }
            $customerService->save();
            if (\Auth::user()->type != 'client') {
                if ($customerService->employee->email != null) {
                    Mail::to($customerService->employee->email)->send(new EmployeeServiceNotification($customerService));
                }
            }
            return redirect()->route('customer_services.index')->with('success', __('Customer Service successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show($id)
    {
        $customer_service = CustomerService::find($id);

        return view('customer_services.detail', compact('customer_service'));
    }


    public function edit($id)
    {
        $customer_service = CustomerService::find($id);

        if (\Auth::user()->can('edit customer services')) {
            if ($customer_service->created_by == \Auth::user()->creatorId()) {
                $customers = Customer::get()->pluck('name', 'id');
                $customers->prepend('Select Customer', '');
                $employees = Employee::all()->pluck('name', 'id');
                $employees->prepend('Select Employee', '');

                return view('customer_services.edit', compact('customer_service', 'customers', 'employees'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $id)
    {

        if (\Auth::user()->can('edit customer services')) {
            $customer_service = CustomerService::find($id);
            if ($customer_service->created_by == \Auth::user()->creatorId()) {
                $rules = [
                    'customer_id' => 'required',
                    'employee_id' => 'required',
                    'phone_number' => 'required',
                    'address' => 'required',
                    'due_date' => 'required',
                    'status' => 'required',
                    'is_paid' => 'required'
                ];

                $validator = \Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('customer_services.index')->with('error', $messages->first());
                }

                $customer_service->customer_id         = $request->customer_id;
                $customer_service->employee_id         = $request->employee_id;
                $customer_service->phone_number        = $request->phone_number;
                $customer_service->address             = $request->address;
                $customer_service->description         = $request->description;
                $customer_service->due_date            = $request->due_date;
                $customer_service->status              = $request->status;
                $customer_service->service_charge      = $request->service_charge;
                $customer_service->is_paid             = $request->is_paid;
                $customer_service->save();

                // $invoice                 = new Invoice();
                // $invoice->invoice_id     = $this->invoiceNumber();
                // $invoice->customer_id    = $customer_service->customer_id;
                // $invoice->status         = 0;
                // $invoice->issue_date     = \Auth::user()->dateFormat($customer_service->created_at);
                // $invoice->due_date       = $customer_service->due_date;
                // $invoice->category_id    = $request->category_id;
                // $invoice->ref_number     = $request->ref_number;
                // $invoice->note           = $request->note;
                // // $invoice->discount_apply = isset($request->discount_apply) ? 1 : 0;
                // $invoice->created_by     = \Auth::user()->creatorId();
                // $invoice->salesman_id    = \Auth::user()->id;
                // $invoice->save();
                // CustomField::saveData($invoice, $request->customField);
                // $products = $request->items;

                // for ($i = 0; $i < count($products); $i++) {
                //     $invoiceProduct              = new InvoiceProduct();
                //     $invoiceProduct->invoice_id  = $invoice->id;
                //     $invoiceProduct->product_id  = $products[$i]['item'];
                //     $invoiceProduct->quantity    = $products[$i]['quantity'];
                //     $invoiceProduct->tax         = $products[$i]['tax'];
                //     //                $invoiceProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                //     $invoiceProduct->discount    = $products[$i]['discount'];
                //     $invoiceProduct->price       = $products[$i]['price'];
                //     $invoiceProduct->description = $products[$i]['description'];
                //     $invoiceProduct->save();

                //     //inventory management (Quantity)
                //     Utility::total_quantity('minus', $invoiceProduct->quantity, $invoiceProduct->product_id);

                //     //For Notification
                //     $setting  = Utility::settings(\Auth::user()->creatorId());
                //     $customer = Customer::find($request->customer_id);
                //     $invoiceNotificationArr = [
                //         'invoice_number' => \Auth::user()->invoiceNumberFormat($invoice->invoice_id),
                //         'user_name' => \Auth::user()->name,
                //         'invoice_issue_date' => $invoice->issue_date,
                //         'invoice_due_date' => $invoice->due_date,
                //         'customer_name' => $customer->name,
                //     ];
                //     //Twilio Notification
                //     // if (isset($setting['twilio_invoice_notification']) && $setting['twilio_invoice_notification'] == 1) {
                //     //     Utility::send_twilio_msg($customer->contact, 'new_invoice', $invoiceNotificationArr);
                //     // }

                //     //Product Stock Report
                //     $type = 'invoice';
                //     $type_id = $invoice->id;
                //     // StockReport::where('type', '=', 'invoice')->where('type_id', '=', $invoice->id)->delete();
                //     $description = $invoiceProduct->quantity . '  ' . __(' quantity sold in invoice') . ' ' . \Auth::user()->invoiceNumberFormat($invoice->invoice_id);
                //     Utility::addProductStock($invoiceProduct->product_id, $invoiceProduct->quantity, $type, $description, $type_id);
                // }

                return redirect()->route('customer_services.index')->with('success', __('Customer Service successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function invoiceNumber()
    {
        $latest = Invoice::latest()->first();
        if (!$latest) {
            return 1;
        }

        return $latest->invoice_id + 1;
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('delete customer services')) {
            $customer_service = CustomerService::find($id);

            if ($customer_service->created_by == \Auth::user()->creatorId()) {
                $customer_service->delete();
                return redirect()->route('customer_services.index')->with('success', __('Customer Service successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function service_products($id)
    {
        $customer_service = CustomerService::find($id);
        $service_products = $customer_service->service_products;
        $products = ProductService::get()->pluck('name', 'id');

        return view('customer_services.service_products', compact('customer_service', 'service_products', 'products'));
    }

    public function store_service_products(Request $request, $id)
    {
        if (\Auth::user()->can('edit customer services')) {

            $rules = [
                'product_id' => 'required|array|min:1',
                'product_id.*' => 'required|exists:product_services,id',
                'quantity' => 'required|array|min:1',
                'quantity.*' => 'required|numeric|min:0.01'
            ];

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('customer_services.index')->with('error', $messages->first());
            }

            $product_price = 0;

            // delete old data

            ServiceProduct::where('service_id', $id)->delete();

            foreach ($request->product_id as $key => $product_id) {
                $service_product                     = new ServiceProduct();
                $service_product->service_id         = $id;
                $service_product->product_id         = $product_id;
                $service_product->quantity           = $request->quantity[$key];
                $service_product->save();

                $product_price += $request->quantity[$key] * ProductService::find($product_id)->sale_price;
            }

            $service = CustomerService::find($id);
            $service->update(['product_price' => $product_price]);

            return redirect()->route('customer_services.index')->with('success', __('Service Products successfully added.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
