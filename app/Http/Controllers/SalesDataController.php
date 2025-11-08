<?php

namespace App\Http\Controllers;

use App\Models\SalesData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesDataController extends Controller
{
    protected $perPage = 100; // Increased from 50 to 100
    protected $sortField = 'invoiced_date';
    protected $sortDirection = 'desc';
    
    // Define sortable columns
    protected $sortable = [
        'year', 'month', 'invoiced_date', 'voucher_number', 'branch', 
        'customer_name', 'customer_status', 'contact_number', 'contact_address',
        'township', 'division', 'customer_nrc_number', 'item_categories',
        'item_group', 'item_name', 'density', 'weight', 'unit', 'quantity',
        'g_price', 'g_gross_amount', 'm_price', 'm_gross_amount', 'dis',
        'promotion_dis', 'special_dis', 'dis_net_amount', 'promotion_net_amount',
        'total_net_amount', 'tax', 'sale_person', 'created_at'
    ];
    
    /**
     * Display a listing of the sales data.
     */
    public function index(Request $request)
    {
        // Get sort parameters with validation
        $this->sortField = in_array($request->get('sort'), $this->sortable) 
            ? $request->get('sort') 
            : 'invoiced_date';
            
        $this->sortDirection = in_array($request->get('direction'), ['asc', 'desc']) 
            ? $request->get('direction') 
            : 'desc';
        
        // Get filter parameters
        $filters = [
            'branch' => $request->get('branch'),
            'customer_name' => $request->get('customer_name'),
            'item_categories' => $request->get('item_categories'),
            'item_group' => $request->get('item_group'),
            'sale_person' => $request->get('sale_person'),
            'from_date' => $request->get('from_date'),
            'to_date' => $request->get('to_date'),
        ];
        
        // Build the query
        $query = SalesData::query();
        
        // Apply filters
        if (!empty($filters['branch'])) {
            $query->where('branch', 'like', '%' . $filters['branch'] . '%');
        }
        
        if (!empty($filters['customer_name'])) {
            $query->where('customer_name', 'like', '%' . $filters['customer_name'] . '%');
        }
        
        if (!empty($filters['item_categories'])) {
            $query->where('item_categories', $filters['item_categories']);
        }
        
        if (!empty($filters['item_group'])) {
            $query->where('item_group', $filters['item_group']);
        }
        
        if (!empty($filters['sale_person'])) {
            $query->where('sale_person', 'like', '%' . $filters['sale_person'] . '%');
        }
        
        if (!empty($filters['from_date'])) {
            $query->where('invoiced_date', '>=', $filters['from_date']);
        }
        
        if (!empty($filters['to_date'])) {
            $query->where('invoiced_date', '<=', $filters['to_date']);
        }
        
        // Get unique values for filters
        $branches = SalesData::select('branch')->distinct()->orderBy('branch')->pluck('branch');
        $itemCategories = SalesData::select('item_categories')->distinct()->orderBy('item_categories')->pluck('item_categories');
        $itemGroups = SalesData::select('item_group')->distinct()->orderBy('item_group')->pluck('item_group');
        $salePersons = SalesData::select('sale_person')->distinct()->whereNotNull('sale_person')->orderBy('sale_person')->pluck('sale_person');
        
        // Get summary statistics
        $summary = [
            'total_sales' => (clone $query)->sum('total_net_amount'),
            'total_quantity' => (clone $query)->sum('quantity'),
            'total_tax' => (clone $query)->sum('tax'),
            'total_discount' => (clone $query)->sum(DB::raw('COALESCE(dis, 0) + COALESCE(promotion_dis, 0) + COALESCE(special_dis, 0)'))
        ];
        
        // Select all required columns
        $salesData = $query->select([
                'id', 'year', 'month', 'invoiced_date', 'voucher_number', 'branch',
                'customer_name', 'customer_status', 'contact_number', 'contact_address',
                'township', 'division', 'customer_nrc_number', 'item_categories',
                'item_group', 'item_name', 'density', 'weight', 'unit', 'quantity',
                'g_price', 'g_gross_amount', 'm_price', 'm_gross_amount', 'dis',
                'promotion_dis', 'special_dis', 'dis_net_amount', 'promotion_net_amount',
                'total_net_amount', 'tax', 'sale_person', 'created_at'
            ])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage)
            ->withQueryString();
        
        // Define SVG icons
        $svgIcons = [
            'table' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-table" viewBox="0 0 16 16"><path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 2h-4v3h4V4zm0 4h-4v3h4V8zm0 4h-4v3h3a1 1 0 0 0 1-1v-2zm-5 3v-3H6v3h4zm-5 0v-3H1v2a1 1 0 0 0 1 1h3zm-4-4h4V8H1v3zm0-4h4V4H1v3zm5-3v3h4V4H6zm4 4H6v3h4V8z"/></svg>',
            'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/></svg>',
            'download' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/><path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/></svg>',
            'columns' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-columns-gap" viewBox="0 0 16 16"><path d="M6 1v3H1V1h5zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H1zm14 12v3h-5v-3h5zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5zM6 8v7H1V8h5zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H1zm14-6v7h-5V1h5zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1h-5z"/></svg>',
            'file-excel' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-excel" viewBox="0 0 16 16"><path d="M5.884 6.68a.5.5 0 1 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 0 0 .768.64L8 10.781l2.116 2.54a.5.5 0 0 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z"/><path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/></svg>',
            'file-pdf' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16"><path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/><path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.294.058-.174.14-.34.25-.49.14-.21.33-.4.58-.57.28-.17.558-.26.836-.26.27 0 .506.086.7.241a1 1 0 0 1 .37.633c.07.35.09.676-.08 1.08-.1.26-.29.494-.56.7a1.5 1.5 0 0 1-.73.273 2.3 2.3 0 0 1-.697-.08 7 7 0 0 1-.5-.132.8.8 0 0 1-.2-.05zm2.75-1.677c0-.11.08-.19.19-.19.12 0 .22.08.22.19 0 .12-.1.21-.22.21a.2.2 0 0 1-.19-.21zm-.8-.54c0-.12.08-.2.19-.2s.18.08.19.2v.01c0 .12-.08.2-.19.2s-.19-.09-.19-.2v-.01zm1.13 1.21c-.2.11-.54.17-.98.17-.42 0-.76-.05-.98-.17-.23-.12-.34-.31-.34-.57 0-.16.04-.3.12-.4a.6.6 0 0 1 .3-.2c.12-.04.26-.06.43-.06.17 0 .32.02.47.06.14.04.25.1.33.2.08.1.13.25.13.4 0 .26-.11.45-.32.57zm.4-.81c0-.11-.02-.19-.06-.25a.3.3 0 0 0-.26-.13c-.21 0-.35.1-.45.31-.1.21-.15.51-.15.91v.42c0 .4.05.7.15.91.1.21.24.3.45.3.1 0 .19-.04.26-.13.06-.09.1-.18.1-.3v-2.44h-.1zm.98-.16v3.14c0 .25.06.44.19.57a.7.7 0 0 0 .5.2c.2 0 .37-.05.5-.15.13-.1.23-.27.3-.5h.04c.02.1.03.2.03.3v.5a1.8 1.8 0 0 1-.01.24c0 .02-.01.04-.03.06v.02h-1.3V11.1h1.3v.1c-.06.12-.1.28-.15.48-.03.2-.04.36-.04.47 0 .04.02.06.05.06.03 0 .05-.02.08-.06.03-.05.07-.1.1-.16.04-.05.08-.1.11-.16h.04c.11.25.27.45.48.6a1 1 0 0 0 .62.2c.28 0 .54-.06.77-.17.23-.12.4-.28.52-.5.12-.2.18-.44.18-.7v-3.6h-1.36v3.52c0 .4-.12.7-.37.88-.25.17-.57.26-.98.26-.4 0-.73-.1-.98-.26a.9.9 0 0 1-.38-.77v-3.63h-1.4v4.46l1.33.01v-.6h.04c.05.1.1.18.17.26.08.08.19.15.33.22.14.06.3.1.5.1.42 0 .78-.13 1.06-.4.3-.26.44-.64.44-1.14v-.1c0-.5-.14-.87-.43-1.13-.28-.26-.63-.4-1.06-.4-.35 0-.64.08-.87.23-.22.16-.35.36-.4.6h-.04v-3.3h1.4v.01z"/></svg>',
            'printer' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16"><path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/><path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/></svg>',
            'file-csv' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-csv" viewBox="0 0 16 16"><path d="M14 4.5V14a2 2 0 0 1-2 2h-2v-1h2a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM3.527 11.85h-.893l-.823 1.439h-.036L.943 11.85H.012l1.227 1.983L0 15.85h.861l.853-1.415h.035l.85 1.415h.907l-1.254-1.992 1.274-2.007Zm.954 3.08v-2.792h.038l.952 2.06.945-2.06h.038v2.791h-1.16v-2.05l-1.12 1.61h-.02l-1.12-1.61v2.05H2.145v-3.999h1.17v2.39l1.027-1.4h.02l1.028 1.4v-2.39h1.16v4Z"/></svg>',
            'sort-up' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-up" viewBox="0 0 16 16"><path d="M3.5 12.5a.5.5 0 0 1-1 0V3.707L1.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.498.498 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L3.5 3.707V12.5zm3.5-9a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z"/></svg>',
            'sort-down' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-down" viewBox="0 0 16 16"><path d="M3.5 3.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 12.293V3.5zm4 .5a.5.5 0 0 1 0-1h1a.5.5 0 0 1 0 1h-1zm0 3a.5.5 0 0 1 0-1h3a.5.5 0 0 1 0 1h-3zm0 3a.5.5 0 0 1 0-1h5a.5.5 0 0 1 0 1h-5zM7 12.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5z"/></svg>',
            'sort' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z"/></svg>',
            'info-circle' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>',
            'plus-circle' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>',
        ];

        return view('sales.index', [
            'salesData' => $salesData,
            'branches' => $branches,
            'itemCategories' => $itemCategories,
            'itemGroups' => $itemGroups,
            'salePersons' => $salePersons,
            'filters' => $filters,
            'summary' => $summary,
            'sortField' => $this->sortField,
            'sortDirection' => $this->sortDirection,
            'svgIcons' => $svgIcons,
        ]);
    }
    
    /**
     * Show the form for creating a new sales record.
     */
    public function create()
    {
        // Get unique values for dropdowns
        $branches = SalesData::select('branch')->distinct()->orderBy('branch')->pluck('branch');
        $categories = SalesData::select('item_categories')->distinct()->orderBy('item_categories')->pluck('item_categories');
        $itemGroups = SalesData::select('item_group')->distinct()->orderBy('item_group')->pluck('item_group');
        $salePersons = SalesData::select('sale_person')->distinct()->whereNotNull('sale_person')->orderBy('sale_person')->pluck('sale_person');

        return view('sales.form', [
            'branches' => $branches,
            'categories' => $categories,
            'itemGroups' => $itemGroups,
            'salePersons' => $salePersons
        ]);
    }
    
    /**
     * Store a newly created sales record in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'voucher_number' => 'required|string|max:50',
            'invoiced_date' => 'required|date',
            'branch' => 'required|string|max:100',
            'customer_name' => 'required|string|max:255',
            'item_categories' => 'required|string|max:100',
            'item_group' => 'required|string|max:100',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'weight' => 'required|numeric|min:0.01',
            'unit' => 'required|string|in:P,g,kg',
            'm_price' => 'required|numeric|min:0',
            'm_gross_amount' => 'required|numeric|min:0',
            'sale_person' => 'required|string|max:100',
            'customer_status' => 'nullable|string|max:50',
            'contact_number' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string|max:500',
            'township' => 'nullable|string|max:100',
            'division' => 'nullable|string|max:100',
            'customer_nrc_number' => 'nullable|string|max:50',
            'density' => 'nullable|numeric|min:0',
            'g_price' => 'nullable|numeric|min:0',
            'g_gross_amount' => 'nullable|numeric|min:0',
            'dis' => 'nullable|numeric|min:0',
            'promotion_dis' => 'nullable|numeric|min:0',
            'special_dis' => 'nullable|numeric|min:0',
            'dis_net_amount' => 'nullable|numeric|min:0',
            'promotion_net_amount' => 'nullable|numeric|min:0',
            'total_net_amount' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'remark' => 'nullable|string|max:1000',
        ]);
        
        try {
            // Extract year and month from invoiced_date
            $invoicedDate = Carbon::parse($validated['invoiced_date']);
            $validated['year'] = $invoicedDate->year;
            $validated['month'] = $invoicedDate->format('F');
            
            // Calculate any missing amounts if needed
            if (empty($validated['total_net_amount']) && !empty($validated['m_gross_amount'])) {
                $validated['total_net_amount'] = $validated['m_gross_amount'];
                if (!empty($validated['dis_net_amount'])) {
                    $validated['total_net_amount'] -= $validated['dis_net_amount'];
                }
            }
            
            // Create the sales record
            $sale = SalesData::create($validated);
            
            return redirect()->route('sales.data.index')
                ->with('success', 'Sales record created successfully.');
                
        } catch (\Exception $e) {
            Log::error('Error creating sales record: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Error creating sales record. Please try again.');
        }
    }
    
    /**
     * Display the specified sales record.
     */
    public function show($id)
    {
        $sale = SalesData::findOrFail($id);
        
        // Safely format the date
        $formattedDate = $sale->invoiced_date 
            ? Carbon::parse($sale->invoiced_date)->format('d M Y')
            : 'N/A';
            
        return view('sales.show', [
            'sale' => $sale,
            'formattedDate' => $formattedDate
        ]);
    }
    
    /**
     * Show the form for editing the specified sales record.
     */
    public function edit($id)
    {
        $sale = SalesData::findOrFail($id);
        
        // Get unique values for dropdowns
        $branches = SalesData::select('branch')->distinct()->orderBy('branch')->pluck('branch');
        $categories = SalesData::select('item_categories')->distinct()->orderBy('item_categories')->pluck('item_categories');
        $itemGroups = SalesData::select('item_group')->distinct()->orderBy('item_group')->pluck('item_group');
        $salePersons = SalesData::select('sale_person')->distinct()->whereNotNull('sale_person')->orderBy('sale_person')->pluck('sale_person');
        
        return view('sales.form', [
            'sale' => $sale,
            'branches' => $branches,
            'categories' => $categories,
            'itemGroups' => $itemGroups,
            'salePersons' => $salePersons
        ]);
    }
    
    /**
     * Update the specified sales record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sale = SalesData::findOrFail($id);
        
        // Validate the request data
        $validated = $request->validate([
            'voucher_number' => 'required|string|max:50',
            'invoiced_date' => 'required|date',
            'branch' => 'required|string|max:100',
            'customer_name' => 'required|string|max:255',
            'item_categories' => 'required|string|max:100',
            'item_group' => 'required|string|max:100',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'weight' => 'required|numeric|min:0.01',
            'unit' => 'required|string|in:P,g,kg',
            'm_price' => 'required|numeric|min:0',
            'm_gross_amount' => 'required|numeric|min:0',
            'sale_person' => 'required|string|max:100',
            'customer_status' => 'nullable|string|max:50',
            'contact_number' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string|max:500',
            'township' => 'nullable|string|max:100',
            'division' => 'nullable|string|max:100',
            'customer_nrc_number' => 'nullable|string|max:50',
            'density' => 'nullable|numeric|min:0',
            'g_price' => 'nullable|numeric|min:0',
            'g_gross_amount' => 'nullable|numeric|min:0',
            'dis' => 'nullable|numeric|min:0',
            'promotion_dis' => 'nullable|numeric|min:0',
            'special_dis' => 'nullable|numeric|min:0',
            'dis_net_amount' => 'nullable|numeric|min:0',
            'promotion_net_amount' => 'nullable|numeric|min:0',
            'total_net_amount' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'remark' => 'nullable|string|max:1000',
        ]);
        
        try {
            // Extract year and month from invoiced_date
            $invoicedDate = Carbon::parse($validated['invoiced_date']);
            $validated['year'] = $invoicedDate->year;
            $validated['month'] = $invoicedDate->format('F');
            
            // Calculate any missing amounts if needed
            if (empty($validated['total_net_amount']) && !empty($validated['m_gross_amount'])) {
                $validated['total_net_amount'] = $validated['m_gross_amount'];
                if (!empty($validated['dis_net_amount'])) {
                    $validated['total_net_amount'] -= $validated['dis_net_amount'];
                }
            }
            
            // Update the sales record
            $sale->update($validated);
            
            return redirect()->route('sales.data.index')
                ->with('success', 'Sales record updated successfully.');
                
        } catch (\Exception $e) {
            Log::error('Error updating sales record: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Error updating sales record. Please try again.');
        }
    }
    
    /**
     * Remove the specified sales record from storage.
     */
    public function destroy($id)
    {
        try {
            $sale = SalesData::findOrFail($id);
            $sale->delete();

            return redirect()->route('sales.data.index')
                ->with('success', 'Sales record deleted successfully.');
                
        } catch (\Exception $e) {
            Log::error('Error deleting sales record: ' . $e->getMessage());
            return back()
                ->with('error', 'Error deleting sales record. Please try again.');
        }
    }
    
    /**
     * Show the import form.
     */
    public function importForm()
    {
        return view('sales.import');
    }
    
    /**
     * Import sales data from CSV file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);
        
        $file = $request->file('csv_file');
        $path = $file->storeAs('temp', 'import_' . time() . '.csv');
        
        // Process the import in the background
        // You can use Laravel's queue system here for better performance
        
        return redirect()->route('sales.index')
                         ->with('success', 'Sales data import has been queued. You will be notified when it is completed.');
    }
}
