<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\ChartOfAccount;
use App\Models\CustomField;
use App\Exports\ProductServiceExport;
use App\Imports\ProductServiceImport;
use App\Models\ManufacturingProduct;
use App\Models\ProductAttribute;
use App\Models\ProductRawMaterial;
use App\Models\ProductService;
use App\Models\ProductServiceCategory;
use App\Models\ProductServiceUnit;
use App\Models\RawMaterial;
use App\Models\Tax;
use App\Models\User;
use App\Models\Utility;
use App\Models\Vender;
use App\Models\WarehouseProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;



class ProductServiceController extends Controller
{
    public function index(Request $request)
    {
        $role = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', \Auth::user()->id)
            ->where('model_has_roles.model_type', \App\Models\User::class)
            ->select('roles.name', 'roles.id as role_id')
            ->first();

        if (\Auth::user()->can('manage product & service')) {
            $category = ProductServiceCategory::where('type', '=', 'product & service')->get()->pluck('name', 'id');
            $category->prepend('Select Category', '');

            // Get the current user's branch ID
            $branchId = \Auth::user()->employee->branch_id ?? null;
            
            // Get all products
            if (!empty($request->category)) {
                $productServices = ProductService::where('category_id', $request->category)
                                    ->with(['taxes', 'unit', 'category'])
                                    ->get();
            } else {
                $productServices = ProductService::with(['taxes', 'unit', 'category'])->get();
            }
            
            // Get warehouse stock for current branch
            $branchStock = [];
            if ($branchId) {
                $warehouseProducts = WarehouseProduct::with(['stockProduct', 'warehouse'])
                    ->whereHas('warehouse', function ($query) use ($branchId) {
                        $query->where('branch_id', $branchId);
                    })
                    ->get();
                    
                // Create a map of product_id => quantity for the current branch
                foreach ($warehouseProducts as $wp) {
                    if (isset($branchStock[$wp->product_id])) {
                        $branchStock[$wp->product_id] += $wp->quantity;
                    } else {
                        $branchStock[$wp->product_id] = $wp->quantity;
                    }
                }
            }
            
            // Add branch stock to each product
            foreach ($productServices as $product) {
                $product->branch_quantity = $branchStock[$product->id] ?? 0;
            }
            
            // Sort products - stocked items first
            $productServices = $productServices->sortByDesc('branch_quantity')->values();

            $raw_materials = RawMaterial::get()->pluck('name', 'id');

            return view('productservice.index', compact('productServices', 'category', 'raw_materials', 'branchId', 'role'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create product & service')) {
            $customFields = CustomField::where('module', '=', 'product')->get();
            $category = ProductServiceCategory::where('type', '=', 'product & service')->get()->pluck('name', 'id');
            $unit = ProductServiceUnit::get()->pluck('name', 'id');
            $tax = Tax::get()->pluck('name', 'id');
            $raw_materials = RawMaterial::get()->pluck('name', 'id');
            $incomeChartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
                ->leftjoin('chart_of_account_types', 'chart_of_account_types.id', 'chart_of_accounts.type')
                ->where('chart_of_account_types.name', 'income')
                ->where('parent', '=', 0)
                ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
                ->pluck('code_name', 'id');
            $incomeChartAccounts->prepend('Select Account', 0);

            $incomeSubAccounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name,chart_of_accounts.id, chart_of_accounts.code, chart_of_account_parents.account'));
            $incomeSubAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
            $incomeSubAccounts->leftjoin('chart_of_account_types', 'chart_of_account_types.id', 'chart_of_accounts.type');
            $incomeSubAccounts->where('chart_of_account_types.name', 'income');
            $incomeSubAccounts->where('chart_of_accounts.parent', '!=', 0);
            $incomeSubAccounts->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
            $incomeSubAccounts = $incomeSubAccounts->get()->toArray();


            $expenseChartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
                ->leftjoin('chart_of_account_types', 'chart_of_account_types.id', 'chart_of_accounts.type')
                ->whereIn('chart_of_account_types.name', ['Expenses', 'Costs of Goods Sold'])
                ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
                ->pluck('code_name', 'id');
            $expenseChartAccounts->prepend('Select Account', '');

            $expenseSubAccounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name,chart_of_accounts.id, chart_of_accounts.code, chart_of_account_parents.account'));
            $expenseSubAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
            $expenseSubAccounts->leftjoin('chart_of_account_types', 'chart_of_account_types.id', 'chart_of_accounts.type');
            $expenseSubAccounts->whereIn('chart_of_account_types.name', ['Expenses', 'Costs of Goods Sold']);
            $expenseSubAccounts->where('chart_of_accounts.parent', '!=', 0);
            $expenseSubAccounts->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
            $expenseSubAccounts = $expenseSubAccounts->get()->toArray();
            $attributes = Attribute::with('values')->get();


            return view('productservice.create', compact('category', 'unit', 'tax', 'customFields', 'incomeChartAccounts', 'incomeSubAccounts', 'expenseChartAccounts', 'expenseSubAccounts', 'raw_materials', 'attributes'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create product & service')) {

            $rules = [
                'name' => 'required',
                'slug' => 'required|unique:product_services',
                'sku' => [
                    'required',
                    Rule::unique('product_services')->where(function ($query) {
                        return $query->where('created_by', \Auth::user()->id);
                    })
                ],
                'sale_price' => 'required|numeric',
                // 'purchase_price' => 'required|numeric',
                'category_id' => 'required',
                'unit_id' => 'required',
                'type' => 'required',
            ];

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('productservice.index')->with('error', $messages->first());
            }

            if ($request->type == 'raw_material') {
                $raw_material = new RawMaterial();
                $raw_material->name = $request->name;
                $raw_material->description = $request->description;
                $raw_material->sku = $request->sku;
                $raw_material->sale_price = $request->sale_price;
                $raw_material->purchase_price = $request->purchase_price;
                $raw_material->tax_id = !empty($request->tax_id) ? implode(',', $request->tax_id) : '';
                $raw_material->unit_id = $request->unit_id;
                if (!empty($request->quantity)) {
                    $raw_material->quantity = $request->quantity;
                } else {
                    $raw_material->quantity = 0;
                }
                $raw_material->type = $request->type;
                $raw_material->sale_chartaccount_id = $request->sale_chartaccount_id;
                $raw_material->expense_chartaccount_id = $request->expense_chartaccount_id;
                $raw_material->category_id = $request->category_id;

                if (!empty($request->pro_image)) {

                    if ($raw_material->pro_image) {
                        $path = storage_path('uploads/pro_image' . $raw_material->pro_image);
                        if (file_exists($path)) {
                            \File::delete($path);
                        }
                    }
                    $fileName = $request->pro_image->getClientOriginalName();
                    $raw_material->pro_image = $fileName;
                    $dir = 'uploads/pro_image';
                    $path = Utility::upload_file($request, 'pro_image', $fileName, $dir, []);

                    $raw_material->save();
                }

                $raw_material->created_by = \Auth::user()->creatorId();
                $raw_material->save();
                CustomField::saveData($raw_material, $request->customField);

                return redirect()->route('productservice.raw_material.index')->with('success', __('Raw Material successfully created.'));
            } else {
                // foreach ($request->raw_material_id as $key => $raw_material_id) {

                //     $raw_material = RawMaterial::find($raw_material_id);
                // }
                $productService = new ProductService();
                $productService->name = $request->name;
                $productService->slug = $request->slug;
                $productService->description = $request->description;
                $productService->sku = $request->sku;
                $productService->sale_price = $request->sale_price;
                $productService->purchase_price = $request->purchase_price;
                $productService->discount_price = $request->discount_price;
                $productService->tax_id = !empty($request->tax_id) ? implode(',', $request->tax_id) : '';
                $productService->unit_id = $request->unit_id;
                if (!empty($request->quantity)) {
                    $productService->quantity = $request->quantity;
                } else {
                    $productService->quantity = 0;
                }
                $productService->type = $request->type;
                $productService->sale_chartaccount_id = $request->sale_chartaccount_id;
                $productService->expense_chartaccount_id = $request->expense_chartaccount_id;
                $productService->category_id = $request->category_id;
                $productService->is_featured = $request->is_featured ?? 0;

                if (!empty($request->pro_image)) {

                    if ($productService->pro_image) {
                        $path = storage_path('uploads/pro_image' . $productService->pro_image);
                        if (file_exists($path)) {
                            \File::delete($path);
                        }
                    }
                    $fileName = $request->pro_image->getClientOriginalName();
                    $productService->pro_image = $fileName;
                    $dir = 'uploads/pro_image';
                    $path = Utility::upload_file($request, 'pro_image', $fileName, $dir, []);
                    $request->pro_image = '';
                    $productService->save();
                }

                $productService->created_by = \Auth::user()->creatorId();

                $productService->save();

                foreach ($request->attributeValues as $value) {
                    $productAttribute = new ProductAttribute();
                    $productAttribute->product_id = $productService->id;
                    $productAttribute->attribute_value_id = $value;

                    $productAttribute->save();
                }
                CustomField::saveData($productService, $request->customField);

                return redirect()->route('productservice.index')->with('success', __('Product successfully created.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show()
    {
        return redirect()->route('productservice.index');
    }


    public function edit($id)
    {
        $productService = ProductService::find($id);

        if (\Auth::user()->can('edit product & service')) {
            $category = ProductServiceCategory::where('type', '=', 'product & service')->get()->pluck('name', 'id');
            $unit = ProductServiceUnit::get()->pluck('name', 'id');
            $tax = Tax::get()->pluck('name', 'id');

            $productService->customField = CustomField::getData($productService, 'product');
            $customFields = CustomField::where('module', '=', 'product')->get();
            $productService->tax_id = explode(',', $productService->tax_id);
            $incomeChartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
                ->leftjoin('chart_of_account_types', 'chart_of_account_types.id', 'chart_of_accounts.type')
                ->where('chart_of_account_types.name', 'income')
                ->where('parent', '=', 0)
                ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
                ->pluck('code_name', 'id');
            $incomeChartAccounts->prepend('Select Account', 0);

            $incomeSubAccounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name,chart_of_accounts.id, chart_of_accounts.code, chart_of_account_parents.account'));
            $incomeSubAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
            $incomeSubAccounts->leftjoin('chart_of_account_types', 'chart_of_account_types.id', 'chart_of_accounts.type');
            $incomeSubAccounts->where('chart_of_account_types.name', 'income');
            $incomeSubAccounts->where('chart_of_accounts.parent', '!=', 0);
            $incomeSubAccounts->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
            $incomeSubAccounts = $incomeSubAccounts->get()->toArray();


            $expenseChartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
                ->leftjoin('chart_of_account_types', 'chart_of_account_types.id', 'chart_of_accounts.type')
                ->whereIn('chart_of_account_types.name', ['Expenses', 'Costs of Goods Sold'])
                ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
                ->pluck('code_name', 'id');
            $expenseChartAccounts->prepend('Select Account', '');

            $expenseSubAccounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name,chart_of_accounts.id, chart_of_accounts.code, chart_of_account_parents.account'));
            $expenseSubAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
            $expenseSubAccounts->leftjoin('chart_of_account_types', 'chart_of_account_types.id', 'chart_of_accounts.type');
            $expenseSubAccounts->whereIn('chart_of_account_types.name', ['Expenses', 'Costs of Goods Sold']);
            $expenseSubAccounts->where('chart_of_accounts.parent', '!=', 0);
            $expenseSubAccounts->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
            $expenseSubAccounts = $expenseSubAccounts->get()->toArray();

            $attributes = Attribute::with('values')->get();
            $productAttributes = ProductAttribute::where('product_id', $id)
                ->with('attributeValue.attribute')
                ->get();

            return view('productservice.edit', compact('category', 'unit', 'tax', 'productService', 'customFields', 'incomeChartAccounts', 'expenseChartAccounts', 'incomeSubAccounts', 'expenseSubAccounts', 'attributes', 'productAttributes'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit product & service')) {
            $productService = ProductService::find($id);
            if ($productService->created_by == \Auth::user()->creatorId()) {
                $rules = [
                    'name' => 'required',
                    'sku' => 'required',
                    Rule::unique('product_services')->ignore($productService->id),
                    'sale_price' => 'required|numeric',
                    'purchase_price' => 'required|numeric',
                    'category_id' => 'required',
                    'unit_id' => 'required',
                    'type' => 'required',

                ];

                $validator = \Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('productservice.index')->with('error', $messages->first());
                }

                $productService->name = $request->name;
                // $productService->slug           = $request->slug;
                $productService->description = $request->description;
                $productService->sku = $request->sku;
                $productService->sale_price = $request->sale_price;
                $productService->purchase_price = $request->purchase_price;
                $productService->discount_price = $request->discount_price;
                $productService->tax_id = !empty($request->tax_id) ? implode(',', $request->tax_id) : '';
                $productService->unit_id = $request->unit_id;

                if (!empty($request->quantity)) {
                    $productService->quantity = $request->quantity;
                } else {
                    $productService->quantity = 0;
                }
                $productService->type = $request->type;
                $productService->sale_chartaccount_id = $request->sale_chartaccount_id;
                $productService->expense_chartaccount_id = $request->expense_chartaccount_id;
                $productService->category_id = $request->category_id;
                $productService->is_featured = $request->is_featured;
                if (!empty($request->pro_image)) {

                    if ($productService->pro_image) {
                        $path = storage_path('uploads/pro_image' . $productService->pro_image);
                        if (file_exists($path)) {
                            \File::delete($path);
                        }
                    }
                    $fileName = $request->pro_image->getClientOriginalName();
                    $productService->pro_image = $fileName;
                    $dir = 'uploads/pro_image';
                    $path = Utility::upload_file($request, 'pro_image', $fileName, $dir, []);
                    $request->pro_image = '';
                }



                $productService->created_by = \Auth::user()->creatorId();
                $productService->save();

                ProductAttribute::where('product_id', $id)->delete();

                foreach ($request->attributeValues as $value) {
                    $productAttribute = new ProductAttribute();
                    $productAttribute->product_id = $productService->id;
                    $productAttribute->attribute_value_id = $value;

                    $productAttribute->save();
                }

                CustomField::saveData($productService, $request->customField);

                return redirect()->route('productservice.index')->with('success', __('Product successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy($id)
    {
        if (\Auth::user()->can('delete product & service')) {
            $productService = ProductService::find($id);
            if ($productService->created_by == \Auth::user()->creatorId()) {
                $productService->delete();

                return redirect()->route('productservice.index')->with('success', __('Product successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function export()
    {
        $name = 'product_service_' . date('Y-m-d i:h:s');
        $data = Excel::download(new ProductServiceExport(), $name . '.xlsx');

        return $data;
    }

    public function importFile()
    {
        return view('productservice.import');
    }

    public function import(Request $request)
    {

        $rules = [
            'file' => 'required|mimes:csv,txt',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $products = (new ProductServiceImport)->toArray(request()->file('file'))[0];


        $totalProduct = count($products) - 1;
        $errorArray = [];
        for ($i = 1; $i <= count($products) - 1; $i++) {
            $items = $products[$i];

            $taxes = explode(';', $items[5]);

            $taxesData = [];
            foreach ($taxes as $tax) {
                $taxes = Tax::where('id', $tax)->first();
                //                $taxesData[] = $taxes->id;
                $taxesData[] = !empty($taxes->id) ? $taxes->id : 0;
            }

            $taxData = implode(',', $taxesData);
            //            dd($taxData);

            if (!empty($productBySku)) {
                $productService = $productBySku;
            } else {
                $productService = new ProductService();
            }

            $productService->name = $items[0];
            $productService->sku = $items[1];
            $productService->sale_price = $items[2];
            $productService->purchase_price = $items[3];
            $productService->quantity = $items[4];
            $productService->tax_id = $items[5];
            $productService->category_id = $items[6];
            $productService->unit_id = $items[7];
            $productService->type = $items[8];
            $productService->description = $items[9];
            $productService->created_by = \Auth::user()->creatorId();

            if (empty($productService)) {
                $errorArray[] = $productService;
            } else {
                $productService->save();
            }
        }

        $errorRecord = [];
        if (empty($errorArray)) {

            $data['status'] = 'success';
            $data['msg'] = __('Record successfully imported');
        } else {
            $data['status'] = 'error';
            $data['msg'] = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalProduct . ' ' . 'record');


            foreach ($errorArray as $errorData) {

                $errorRecord[] = implode(',', $errorData);
            }

            \Session::put('errorArray', $errorRecord);
        }

        return redirect()->back()->with($data['status'], $data['msg']);
    }

    public function warehouseDetail($id)
    {
        $products = WarehouseProduct::with(['warehouse'])->where('product_id', '=', $id)->get();
        return view('productservice.detail', compact('products'));
    }

    public function add_raw_materials($id)
    {
        $product = ProductService::where('id', '=', $id)->first();
        $raw_materials = RawMaterial::get()->pluck('name', 'id');
        $units = ProductServiceUnit::get()->pluck('name', 'id');
        $product_raw_materials = ManufacturingProduct::where('product_id', $id)->get();

        return view('productservice.add_raw_materials', compact('product', 'raw_materials', 'units', 'product_raw_materials'));
    }

    public function store_raw_materials(Request $request, $id)
    {
        if (\Auth::user()->can('create product & service')) {

            $rules = [
                'raw_material_id' => 'required|array|min:1',
                'raw_material_id.*' => 'required|exists:raw_materials,id',
                'quantity' => 'required|array|min:1',
                'quantity.*' => 'required|numeric|min:0.01'
            ];

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('productservice.index')->with('error', $messages->first());
            }

            $purchase_price = 0;

            // delete old data

            ManufacturingProduct::where('product_id', $id)->delete();

            foreach ($request->raw_material_id as $key => $raw_material_id) {
                $manufacturing_product = new ManufacturingProduct();
                $manufacturing_product->raw_material_id = $raw_material_id;
                $manufacturing_product->product_id = $id;
                $manufacturing_product->quantity = $request->quantity[$key];
                $manufacturing_product->save();

                $purchase_price += $request->quantity[$key] * RawMaterial::find($raw_material_id)->purchase_price;
            }

            // purchase price update of product

            $productService = ProductService::find($id);
            $productService->update(['purchase_price' => $purchase_price]);

            // CustomField::saveData($productService, $request->customField);

            return redirect()->route('productservice.index')->with('success', __('Raw Material successfully added.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function searchProducts(Request $request)
    {

        $lastsegment = $request->session_key;

        if (Auth::user()->can('manage pos') && $request->ajax() && isset($lastsegment) && !empty($lastsegment)) {

            if ($request->has('war_id')) {
                $request->session()->put('pos_warehouse_id', $request->war_id);
            }

            $output = "";
            if ($request->war_id == '0') {
                $ids = WarehouseProduct::where('warehouse_id', 1)->get()->pluck('product_id')->toArray();

                if ($request->cat_id !== '' && $request->search == '') {
                    if ($request->cat_id == '0') {
                        $products = ProductService::getallproducts()->whereIn('product_services.id', $ids)->with(['unit'])->get();
                    } else {
                        $products = ProductService::getallproducts()->where('category_id', $request->cat_id)->whereIn('product_services.id', $ids)->with(['unit'])->get();
                    }
                } else {
                    if ($request->cat_id == '0') {
                        $products = ProductService::getallproducts()->where('product_services.name', 'LIKE', "%{$request->search}%")->with(['unit'])->get();
                    } else {
                        $products = ProductService::getallproducts()->where('product_services.name', 'LIKE', "%{$request->search}%")->orWhere('category_id', $request->cat_id)->with(['unit'])->get();
                    }
                }
            } else {
                $ids = WarehouseProduct::where('warehouse_id', $request->war_id)->get()->pluck('product_id')->toArray();

                if ($request->cat_id == '0') {
                    $products = ProductService::getallproducts()->whereIn('product_services.id', $ids)->with(['unit'])->get();
                } else {
                    $products = ProductService::getallproducts()->whereIn('product_services.id', $ids)->where('category_id', $request->cat_id)->with(['unit'])->get();
                }
            }


            if (count($products) > 0) {
                foreach ($products as $key => $product) {
                    $quantity = $product->warehouseProduct($product->id, $request->war_id != 0 ? $request->war_id : 1);

                    $unit = (!empty($product) && !empty($product->unit)) ? $product->unit->name : '';
                    if (!empty($product->pro_image)) {
                        $image_url = ('uploads/pro_image') . '/' . $product->pro_image;
                    } else {
                        $image_url = ('uploads/pro_image') . '/default.png';
                    }
                    if ($request->session_key == 'purchases') {
                        $productprice = $product->purchase_price != 0 ? $product->purchase_price : 0;
                    } else if ($request->session_key == 'pos') {
                        $productprice = $product->sale_price != 0 ? $product->sale_price : 0;
                    } else {
                        $productprice = $product->sale_price != 0 ? $product->sale_price : $product->purchase_price;
                    }
                    $output .= '

                                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-12">
                                        <div class="tab-pane fade show active toacart w-100" data-url="' . url('erp/add-to-cart/' . $product->id . '/' . $lastsegment . '?war_id=' . $request->war_id) . '">
                                            <div class="position-relative card">
                                                <img alt="Image placeholder" src="' . asset(Storage::url($image_url)) . '" class="card-image avatar shadow hover-shadow-lg" style=" height: 6rem; width: 100%;">
                                                <div class="p-0 custom-card-body card-body d-flex ">
                                                    <div class="card-body my-2 p-2 text-left card-bottom-content">
                                                        <h6 class="mb-2 text-dark product-title-name">' . $product->name . '</h6>
                                                        <small class="badge badge-primary mb-0">' . Auth::user()->priceFormat($productprice) . '</small>

                                                        <small class="top-badge badge badge-danger mb-0">' . $quantity . ' ' . $unit . '</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                            ';
                }
                return Response($output);
            } else {
                $output = '<div class="card card-body col-12 text-center">
                    <h5>' . __("No Product Available") . '</h5>
                    </div>';
                return Response($output);
            }
        }
    }


    public function addToCart(Request $request, $id, $session_key)
    {
        $war_id = $request->query('war_id');

        if (Auth::user()->can('manage product & service') && $request->ajax()) {
            $product = ProductService::find($id);
            $warehouseProduct = WarehouseProduct::where('warehouse_id', $war_id)->where('product_id', $id)->first();

            $productquantity = $warehouseProduct->quantity;

            // if ($product) {
            //     $productquantity = $product->getTotalProductQuantity();
            // }

            if (!$product || ($session_key == 'pos' && $productquantity == 0)) {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ],
                    404
                );
            }

            $productname = $product->name;

            if ($session_key == 'purchases') {

                $productprice = $product->purchase_price != 0 ? $product->purchase_price : 0;
            } else if ($session_key == 'pos') {

                $productprice = $product->sale_price != 0 ? $product->sale_price : 0;
            } else {

                $productprice = $product->sale_price != 0 ? $product->sale_price : $product->purchase_price;
            }

            $originalquantity = (int) $productquantity;


            //            $tax = ProductService::where('product_services.id', $id)->leftJoin(
            //                'taxes',
            //                function ($join) {
            //                    $join->on('taxes.id', '=', 'product_services.tax_id')
            //                        ->where('taxes.created_by', '=', Auth::user()->creatorId())
            //                        ->orWhereNull('product_services.tax_id');
            //                }
            //            )->select(DB::Raw('IFNULL( `taxes`.`rate` , 0 ) as rate'))->first();

            $taxes = Utility::tax($product->tax_id);

            $totalTaxRate = Utility::totalTaxRate($product->tax_id);

            $product_tax = '';
            $product_tax_id = [];
            foreach ($taxes as $tax) {
                $product_tax .= !empty($tax) ? "<span class='badge badge-primary'>" . $tax->name . ' (' . $tax->rate . '%)' . "</span><br>" : '';
                $product_tax_id[] = !empty($tax) ? $tax->id : 0;
            }

            if (empty($product_tax)) {
                $product_tax = "-";
            }
            $producttax = $totalTaxRate;


            $tax = ($productprice * $producttax) / 100;

            $subtotal = $productprice + $tax;
            //            dd($subtotal);
            $cart = session()->get($session_key);
            //            $image_url       = (!empty($product->image) && Storage::exists($product->image)) ? $product->image : 'logo/placeholder.png';
            $image_url = (!empty($product->pro_image) && Storage::exists($product->pro_image)) ? $product->pro_image : 'uploads/pro_image/' . $product->pro_image;


            $model_delete_id = 'delete-form-' . $id;

            $carthtml = '';

            $carthtml .= '<tr data-product-id="' . $id . '" id="product-id-' . $id . '">
                            <td class="cart-images">
                                <img alt="Image placeholder" src="' . asset(Storage::url($image_url)) . '" class="card-image avatar shadow hover-shadow-lg">
                            </td>

                            <td class="name">' . $productname . '</td>

                            <td class="">
                                   <span class="quantity buttons_added">
                                         <input type="button" value="-" class="minus">
                                         <input type="number" step="1" min="1" max="" name="quantity" title="' . __('Quantity') . '" class="input-number" size="4" data-url="' . url('update-cart/') . '" data-id="' . $id . '">
                                         <input type="button" value="+" class="plus">
                                   </span>
                            </td>


                            <td class="tax">' . $product_tax . '</td>

                            <td class="price">' . Auth::user()->priceFormat($productprice) . '</td>

                            <td class="subtotal">' . Auth::user()->priceFormat($subtotal) . '</td>

                            <td class="">
                                 <a href="#" class="action-btn bg-danger bs-pass-para-pos" data-confirm="' . __("Are You Sure?") . '" data-text="' . __("This action can not be undone. Do you want to continue?") . '" data-confirm-yes=' . $model_delete_id . ' title="' . __('Delete') . '}" data-id="' . $id . '" title="' . __('Delete') . '"   >
                                   <span class=""><i class="ti ti-trash btn btn-sm text-white"></i></span>
                                 </a>
                                 <form method="post" action="' . url('remove-from-cart') . '"  accept-charset="UTF-8" id="' . $model_delete_id . '">
                                      <input name="_method" type="hidden" value="DELETE">
                                      <input name="_token" type="hidden" value="' . csrf_token() . '">
                                      <input type="hidden" name="session_key" value="' . $session_key . '">
                                      <input type="hidden" name="id" value="' . $id . '">
                                 </form>

                            </td>
                        </td>';



            // if cart is empty then this the first product
            if (!$cart) {
                $cart = [
                    $id => [
                        "name" => $productname,
                        "quantity" => 1,
                        "price" => $productprice,
                        "id" => $id,
                        "tax" => $producttax,
                        "subtotal" => $subtotal,
                        "originalquantity" => $originalquantity,
                        "product_tax" => $product_tax,
                        "product_tax_id" => !empty($product_tax_id) ? implode(',', $product_tax_id) : 0,
                    ],
                ];


                if ($originalquantity < $cart[$id]['quantity'] && $session_key == 'pos') {
                    return response()->json(
                        [
                            'code' => 404,
                            'status' => 'Error',
                            'error' => __('This product is out of stock!'),
                        ],
                        404
                    );
                }

                session()->put($session_key, $cart);

                return response()->json(
                    [
                        'code' => 200,
                        'status' => 'Success',
                        'success' => $productname . __(' added to cart successfully!'),
                        'product' => $cart[$id],
                        'carthtml' => $carthtml,
                    ]
                );
            }

            // if cart not empty then check if this product exist then increment quantity
            if (isset($cart[$id])) {

                $cart[$id]['quantity']++;
                $cart[$id]['id'] = $id;

                $subtotal = $cart[$id]["price"] * $cart[$id]["quantity"];
                $tax = ($subtotal * $cart[$id]["tax"]) / 100;

                $cart[$id]["subtotal"] = $subtotal + $tax;
                $cart[$id]["originalquantity"] = $originalquantity;

                if ($originalquantity < $cart[$id]['quantity'] && $session_key == 'pos') {
                    return response()->json(
                        [
                            'code' => 404,
                            'status' => 'Error',
                            'error' => __('This product is out of stock!'),
                        ],
                        404
                    );
                }

                session()->put($session_key, $cart);

                return response()->json(
                    [
                        'code' => 200,
                        'status' => 'Success',
                        'success' => $productname . __(' added to cart successfully!'),
                        'product' => $cart[$id],
                        'carttotal' => $cart,
                    ]
                );
            }

            // if item not exist in cart then add to cart with quantity = 1
            $cart[$id] = [
                "name" => $productname,
                "quantity" => 1,
                "price" => $productprice,
                "tax" => $producttax,
                "subtotal" => $subtotal,
                "id" => $id,
                "originalquantity" => $originalquantity,
                "product_tax" => $product_tax,
            ];

            if ($originalquantity < $cart[$id]['quantity'] && $session_key == 'pos') {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ],
                    404
                );
            }

            session()->put($session_key, $cart);

            return response()->json(
                [
                    'code' => 200,
                    'status' => 'Success',
                    'success' => $productname . __(' added to cart successfully!'),
                    'product' => $cart[$id],
                    'carthtml' => $carthtml,
                    'carttotal' => $cart,
                ]
            );
        } else {
            return response()->json(
                [
                    'code' => 404,
                    'status' => 'Error',
                    'error' => __('This Product is not found!'),
                ],
                404
            );
        }
    }

    public function updateCart(Request $request)
    {


        $id = $request->id;
        $quantity = $request->quantity;
        $discount = $request->discount;
        $session_key = $request->session_key;


        if (Auth::user()->can('manage product & service') && $request->ajax() && isset($id) && !empty($id) && isset($session_key) && !empty($session_key)) {
            $cart = session()->get($session_key);



            if (isset($cart[$id]) && $quantity == 0) {
                unset($cart[$id]);
            }

            if ($quantity) {

                $cart[$id]["quantity"] = $quantity;

                $producttax = isset($cart[$id]["tax"]) ? $cart[$id]["tax"] : 0;
                $productprice = isset($cart[$id]["price"]) ? $cart[$id]["price"] : 0;

                $subtotal = $productprice * $quantity;
                $tax = ($subtotal * $producttax) / 100;

                $cart[$id]["subtotal"] = $subtotal + $tax;
            }

            if (isset($cart[$id]) && ($cart[$id]["originalquantity"]) < $cart[$id]['quantity'] && $session_key == 'pos') {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ],
                    404
                );
            }

            $subtotal = array_sum(array_column($cart, 'subtotal'));
            $discount = $request->discount;
            $total = $subtotal - $discount;
            $totalDiscount = User::priceFormats($total);
            $discount = $totalDiscount;


            session()->put($session_key, $cart);

            return response()->json(
                [
                    'code' => 200,
                    'success' => __('Cart updated successfully!'),
                    'product' => $cart,
                    'discount' => $discount,
                ]
            );
        } else {
            return response()->json(
                [
                    'code' => 404,
                    'status' => 'Error',
                    'error' => __('This Product is not found!'),
                ],
                404
            );
        }
    }

    public function emptyCart(Request $request)
    {
        $session_key = $request->session_key;

        if (Auth::user()->can('manage product & service') && isset($session_key) && !empty($session_key)) {
            $cart = session()->get($session_key);
            if (isset($cart) && count($cart) > 0) {
                session()->forget($session_key);
            }

            return redirect()->back()->with('error', __('Cart is empty!'));
        } else {
            return redirect()->back()->with('error', __('Cart cannot be empty!.'));
        }
    }

    public function warehouseemptyCart(Request $request)
    {
        $session_key = $request->session_key;

        $cart = session()->get($session_key);
        if (isset($cart) && count($cart) > 0) {
            session()->forget($session_key);
        }

        return response()->json();
    }

    public function removeFromCart(Request $request)
    {
        $id = $request->id;
        $session_key = $request->session_key;
        if (Auth::user()->can('manage product & service') && isset($id) && !empty($id) && isset($session_key) && !empty($session_key)) {
            $cart = session()->get($session_key);
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put($session_key, $cart);
            }

            return redirect()->back()->with('error', __('Product removed from cart!'));
        } else {
            return redirect()->back()->with('error', __('This Product is not found!'));
        }
    }
}
