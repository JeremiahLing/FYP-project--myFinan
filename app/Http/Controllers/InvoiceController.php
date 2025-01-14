<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Mail\SendInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    public function index()
    {
        // Fetch all invoices
        $invoices = Invoice::where('user_id', auth()->id())->get();
        return view('invoices.invoice', compact('invoices'));
    }

    public function create()
    {
        return view('invoices.create');
    }

    // Generate the next invoice number
    private function generateInvoiceNumber()
    {
        $latestInvoice = Invoice::orderBy('id', 'desc')->first();
        $nextNumber = $latestInvoice ? intval(substr($latestInvoice->invoice_number, 4)) + 1 : 1;
        return 'INV ' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    // Store a new invoice
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_unit_price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        $invoiceNumber = $this->generateInvoiceNumber();

        // Create the invoice
        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'subtotal' => $validated['subtotal'],
            'tax' => $validated['tax'],
            'total' => $validated['total'],
            'user_id' => auth()->id(),
        ]);
        
        // Save items for the invoice and calculate total for each item
        foreach ($validated['items'] as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_unit_price' => $item['quantity'] * $item['unit_price'],
                'invoice_id' => $invoice->id,
            ]);
        }

        // Redirect to the invoice list with a success message
        return redirect()->route('invoice')->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        // Authorize that the logged-in user owns this invoice
        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        $invoice->load('items');
        
        // Pass the invoice to the view
        return view('invoices.show', compact('invoice'));
    }

    public function design(Invoice $invoice)
    {
        // Authorize that the logged-in user owns this invoice
        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        $defaultColor = '#000000';

        // Pass the invoice details to the design view
        return view('invoices.design', compact('invoice'));
    }

    private function validateInvoiceDesign(Request $request)
    {
        return $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'template' => 'required|string',
            'color' => 'nullable|string',
            'logo' => 'nullable|file|image|max:2048',
        ]);
    }

    public function preview(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'items' => 'required|array',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_unit_price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'invoice_id' => 'required|exists:invoices,id',
            'template' => 'required|string',
            'color' => 'nullable|string',
            'logo' => 'nullable|file|image|max:2048',
        ]);

        // Add `invoice_id` to the validated array
        $invoice = Invoice::findOrFail($validated['invoice_id']);
        $validated['invoice_id'] = $invoice->id; // Ensure it exists in the array.

        // Handle logo upload (optional)
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = asset('storage/' . $logoPath);
        }
        
        // Pass the validated data to the preview page
        return view('invoices.preview', compact('validated'));
    }

    public function saveDesign(Request $request)
    {
        $validated = $this->validateInvoiceDesign($request);

        $invoice = Invoice::findOrFail($request->invoice_id);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($invoice->logo_url) {
                Storage::disk('public')->delete($invoice->logo_url); // Remove old file
            }
            $logoPath = $request->file('logo')->store('logos', 'public');
            $invoice->logo_url = asset('storage/' . $logoPath);
        }
        

        // Save template and color choices
        $invoice->template = $request->template;
        $invoice->primary_color = $request->color ?? '#000000'; // Default to black if no color selected

        $invoice->save();

        return redirect()->route('invoices.index')->with('success', 'Invoice design saved successfully.');
    }

    public function sendInvoice(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'client_email' => 'required|email',
            
        ]);

        $invoice = Invoice::with('items')->findOrFail($validated['invoice_id']);

        $invoiceData = $request->all();

         // Add sender information
        $invoiceData['sender_name'] = auth()->user()->name;
        $invoiceData['sender_email'] = auth()->user()->email;

        Mail::to($invoiceData['client_email'])->send(new SendInvoice($invoiceData));

        return redirect()->route('invoice')->with('success', 'Invoice sent successfully!');
    }

}

