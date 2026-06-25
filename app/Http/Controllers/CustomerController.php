<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerDocument;
use App\Models\CustomerGuarantor;
use App\Trait\FileHandler;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public $fileHandler;

    public function __construct(FileHandler $fileHandler)
    {
        $this->fileHandler = $fileHandler;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('customer_view'), 403);
        if ($request->ajax()) {
            $customers = Customer::latest()->get();
            return DataTables::of($customers)
                ->addIndexColumn()
                ->addColumn('name', fn($data) => $data->name)
                ->addColumn('phone', fn($data) => $data->phone)
                ->addColumn('address', fn($data) => $data->address)
                ->addColumn('created_at', fn($data) => $data->created_at->format('d M, Y')) // Using Carbon for formatting
                ->addColumn('action', function ($data) {
                    $actions = table_actions();

                    if (auth()->user()->can('customer_view')) {
                        $actions->link(route('backend.admin.customers.show', $data->id), 'View', 'fas fa-eye');
                    }

                    if (auth()->user()->can('customer_update')) {
                        $actions->link(route('backend.admin.customers.edit', $data->id), 'Edit', 'fas fa-edit');
                    }

                    if (auth()->user()->can('customer_delete')) {
                        $actions->delete(
                            route('backend.admin.customers.destroy', $data->id),
                            $data->id == 1 ? 'System customer cannot be deleted' : 'Delete',
                            'Are you sure?',
                            $data->id == 1
                        );
                    }

                    if (auth()->user()->can('customer_sales')) {
                        $actions->link(route('backend.admin.customers.orders', $data->id), 'Sales', 'fas fa-cart-plus');
                    }

                    return $actions->render();
                })

                ->rawColumns(['name', 'phone', 'address', 'created_at', 'action'])
                ->toJson();
        }


        return view('backend.customers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        abort_if(!auth()->user()->can('customer_create'), 403);
        return view('backend.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        abort_if(!auth()->user()->can('customer_create'), 403);

        if ($request->wantsJson()) {
            $request->validate([
                'name' => 'required|string',
            ]);

            $customer = Customer::create([
                'name' => $request->name,
            ]);

            return response()->json($customer);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone',
            'cnic' => 'nullable|string|max:30|unique:customers,cnic',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'cnic_front' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'cnic_back' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'utility_bill' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'guarantor_name' => 'required|array|min:1',
            'guarantor_name.0' => 'required|string|max:255',
            'guarantor_cnic.0' => 'required|string|max:30',
            'guarantor_phone.0' => 'required|string|max:20',
            'guarantor_address.0' => 'nullable|string|max:255',
            'guarantor_relationship.0' => 'nullable|string|max:100',
            'guarantor_notes.0' => 'nullable|string',
            'guarantor_document.0' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'guarantor_name.*' => 'nullable|string|max:255',
            'guarantor_cnic.*' => 'nullable|string|max:30',
            'guarantor_phone.*' => 'nullable|string|max:20',
            'guarantor_address.*' => 'nullable|string|max:255',
            'guarantor_relationship.*' => 'nullable|string|max:100',
            'guarantor_notes.*' => 'nullable|string',
            'guarantor_document.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $customer = Customer::create($request->only(['name', 'phone', 'cnic', 'address']));

        $this->storeCustomerVerificationFiles($customer, $request);
        $this->storeCustomerGuarantors($customer, $request);

        session()->flash('success', 'Customer created successfully.');
        return to_route('backend.admin.customers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        abort_if(!auth()->user()->can('customer_view'), 403);
        $customer->load(['documents', 'guarantors.documents']);
        return view('backend.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        abort_if(!auth()->user()->can('customer_update'), 403);
        $customer = Customer::with('documents', 'guarantors.documents')->findOrFail($id);
        return view('backend.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        abort_if(!auth()->user()->can('customer_update'), 403);
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone,' . $customer->id,
            'cnic' => 'nullable|string|max:30|unique:customers,cnic,' . $customer->id,
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'cnic_front' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'cnic_back' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'utility_bill' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'guarantor_name' => 'required|array|min:1',
            'guarantor_name.0' => 'required|string|max:255',
            'guarantor_cnic.0' => 'required|string|max:30',
            'guarantor_phone.0' => 'required|string|max:20',
            'guarantor_address.0' => 'nullable|string|max:255',
            'guarantor_relationship.0' => 'nullable|string|max:100',
            'guarantor_notes.0' => 'nullable|string',
            'guarantor_document.0' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'guarantor_name.*' => 'nullable|string|max:255',
            'guarantor_cnic.*' => 'nullable|string|max:30',
            'guarantor_phone.*' => 'nullable|string|max:20',
            'guarantor_address.*' => 'nullable|string|max:255',
            'guarantor_relationship.*' => 'nullable|string|max:100',
            'guarantor_notes.*' => 'nullable|string',
            'guarantor_document.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $customer->update($request->only(['name', 'phone', 'cnic', 'address']));

        $this->storeCustomerVerificationFiles($customer, $request);
        $this->storeCustomerGuarantors($customer, $request);

        session()->flash('success', 'Customer updated successfully.');
        return to_route('backend.admin.customers.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        abort_if(!auth()->user()->can('customer_delete'), 403);
        $customer = Customer::findOrFail($id);
        $customer->delete();
        session()->flash('success', 'Customer deleted successfully.');
        return to_route('backend.admin.customers.index');
    }
    public function getCustomers(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(Customer::latest()->get());
        }
    }

    public function getGuarantors($id, Request $request)
    {
        $customer = Customer::findOrFail($id);
        if ($request->wantsJson()) {
            return response()->json($customer->guarantors()->get());
        }
        return response()->json($customer->guarantors()->get());
    }
    //get orders by customer id
    public function orders($id)
    {
        $customer = Customer::findOrFail($id);
        $orders = $customer->orders()->paginate(100);
        return view('backend.orders.index', compact('orders'));
    }

    protected function storeCustomerVerificationFiles(Customer $customer, Request $request)
    {
        $uploadTypes = [
            'photo' => CustomerDocument::TYPE_CUSTOMER_PHOTO,
            'cnic_front' => CustomerDocument::TYPE_CNIC_FRONT,
            'cnic_back' => CustomerDocument::TYPE_CNIC_BACK,
            'utility_bill' => CustomerDocument::TYPE_UTILITY_BILL,
        ];

        foreach ($uploadTypes as $field => $type) {
            if ($request->hasFile($field)) {
                $customer->documents()->create([
                    'document_type' => $type,
                    'file_path' => $this->fileHandler->fileUploadAndGetPath($request->file($field), '/public/media/customer_documents'),
                    'original_name' => $request->file($field)->getClientOriginalName(),
                    'mime_type' => $request->file($field)->getClientMimeType(),
                    'size' => $request->file($field)->getSize(),
                ]);
            }
        }
    }

    protected function storeCustomerGuarantors(Customer $customer, Request $request)
    {
        if (!$request->has('guarantor_name')) {
            return;
        }

        foreach ($request->input('guarantor_name', []) as $index => $name) {
            if (empty($name)) {
                continue;
            }

            $guarantor = $customer->guarantors()->create([
                'name' => $name,
                'cnic' => $request->input('guarantor_cnic.' . $index),
                'phone' => $request->input('guarantor_phone.' . $index),
                'address' => $request->input('guarantor_address.' . $index),
                'relationship' => $request->input('guarantor_relationship.' . $index),
                'notes' => $request->input('guarantor_notes.' . $index),
            ]);

            if ($request->hasFile('guarantor_document.' . $index)) {
                $customer->documents()->create([
                    'customer_guarantor_id' => $guarantor->id,
                    'document_type' => CustomerDocument::TYPE_GUARANTOR_DOCUMENT,
                    'file_path' => $this->fileHandler->fileUploadAndGetPath($request->file('guarantor_document.' . $index), '/public/media/customer_documents'),
                    'original_name' => $request->file('guarantor_document.' . $index)->getClientOriginalName(),
                    'mime_type' => $request->file('guarantor_document.' . $index)->getClientMimeType(),
                    'size' => $request->file('guarantor_document.' . $index)->getSize(),
                ]);
            }
        }
    }
}
