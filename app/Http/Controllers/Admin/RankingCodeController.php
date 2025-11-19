<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RankingCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RankingCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rankingCodes = RankingCode::orderBy('branch_code')
            ->orderBy('group_name')
            ->orderBy('position_code')
            ->orderBy('id_code')
            ->paginate(20);
            
        return view('admin.ranking-codes.index', compact('rankingCodes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ranking-codes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Prepare the data
            $data = $request->only([
                'group_name', 'position_name', 'name', 'guardian_name',
                'guardian_code', 'branch_code', 'group_code', 'position_code', 'id_code'
            ]);
            
            // Convert to proper types
            $data['branch_code'] = (int)$data['branch_code'];
            $data['id_code'] = (int)$data['id_code'];
            $data['guardian_code'] = strtoupper(trim($data['guardian_code']));
            $data['group_code'] = strtoupper(trim($data['group_code']));
            $data['position_code'] = strtoupper(trim($data['position_code']));
            
            // Log the data for debugging
            Log::info('Ranking code creation data: ' . json_encode($data));
            
            // Validate the data
            $validator = Validator::make($data, RankingCode::rules(), RankingCode::validationMessages());
            
            if ($validator->fails()) {
                Log::error('Ranking code validation errors: ' . json_encode($validator->errors()));
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'ဒေတာများအား အတည်ပြုရာတွင် အမှားတစ်ခုဖြစ်နေပါသည်။');
            }
            
            // Create the ranking code
            $rankingCode = new RankingCode($data);
            $rankingCode->save();
            
            return redirect()
                ->route('ranking-codes.index')
                ->with('success', 'Ranking code အား အောင်မြင်စွာထည့်သွင်းပြီးပါပြီ။');
                
        } catch (\Exception $e) {
            Log::error('Error creating ranking code: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Ranking code ထည့်သွင်းရာတွင် အမှားတစ်ခုဖြစ်နေပါသည်။ ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RankingCode $rankingCode)
    {
        return view('admin.ranking-codes.show', compact('rankingCode'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RankingCode $rankingCode)
    {
        return view('admin.ranking-codes.edit', compact('rankingCode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RankingCode $rankingCode)
    {
        try {
            // Prepare the data
            $data = $request->only([
                'group_name', 'position_name', 'name', 'guardian_name',
                'guardian_code', 'branch_code', 'group_code', 'position_code', 'id_code'
            ]);
            
            // Convert to proper types
            $data['branch_code'] = (int)$data['branch_code'];
            $data['id_code'] = (int)$data['id_code'];
            $data['guardian_code'] = strtoupper(trim($data['guardian_code']));
            $data['group_code'] = strtoupper(trim($data['group_code']));
            $data['position_code'] = strtoupper(trim($data['position_code']));
            
            // Validate the data
            $validator = Validator::make($data, RankingCode::rules($rankingCode->id), RankingCode::validationMessages());
            
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'ဒေတာများအား အတည်ပြုရာတွင် အမှားတစ်ခုဖြစ်နေပါသည်။');
            }
            
            // Update the ranking code
            $rankingCode->fill($data);
            $rankingCode->save();
            
            return redirect()
                ->route('ranking-codes.index')
                ->with('success', 'Ranking code အား အောင်မြင်စွာပြင်ဆင်ပြီးပါပြီ။');
                
        } catch (\Exception $e) {
            Log::error('Error updating ranking code: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Ranking code ပြင်ဆင်ရာတွင် အမှားတစ်ခုဖြစ်နေပါသည်။ ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RankingCode $rankingCode)
    {
        try {
            $rankingCode->delete();
            
            return redirect()
                ->route('ranking-codes.index')
                ->with('success', 'Ranking code အား အောင်မြင်စွာဖျက်ပစ်လိုက်ပါပြီ။');
                
        } catch (\Exception $e) {
            Log::error('Error deleting ranking code: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Ranking code ဖျက်ပစ်ရာတွင် အမှားတစ်ခုဖြစ်နေပါသည်။ ' . $e->getMessage());
        }
    }
    
    /**
     * Generate ranking ID based on the pattern
     */
    protected function generateRankingId($data)
    {
        $branch = abs($data['branch_code']);
        $group = $data['group_code'];
        $position = $data['position_code'];
        $idCode = str_pad($data['id_code'], 3, '0', STR_PAD_LEFT);
        
        return "{$data['guardian_code']}-{$branch}{$group}{$position}{$idCode}";
    }

    /**
     * Show the import form
     */
    public function showImportForm()
    {
        return view('admin.ranking-codes.import');
    }

    /**
     * Import ranking codes from CSV/Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ], [
            'file.required' => 'ကျေးဇူးပြု၍ ဖိုင်တစ်ခုရွေးပါ။',
            'file.mimes' => 'CSV သို့မဟုတ် TXT ဖိုင်သာ လက်ခံပါသည်။',
            'file.max' => 'ဖိုင်အရွယ်အစားသည် 10MB ထက်ကျော်လွန်နေပါသည်။',
        ]);
        
        $file = $request->file('file');
        $handle = null;
        
        try {
            if (!$file->isValid()) {
                throw new \Exception('ဖိုင်မှားယွင်းနေပါသည်။ ကျေးဇူးပြု၍ မှန်ကန်သော CSV ဖိုင်ကိုသာ တင်ပါ။');
            }
            
            // Get the file path
            $path = $file->getRealPath();
            
            // Detect file encoding and convert to UTF-8 if needed
            $fileContent = @file_get_contents($path);
            if ($fileContent === false) {
                throw new \Exception('ဖိုင်ဖတ်၍မရပါ။ ကျေးဇူးပြု၍ ဖိုင်ကိုပြန်စစ်ပါ။');
            }
            
            $encoding = mb_detect_encoding($fileContent, ['UTF-8', 'ISO-8859-1', 'WINDOWS-1252'], true);
            
            if ($encoding === false) {
                $encoding = 'UTF-8'; // Default to UTF-8 if detection fails
            }
            
            if ($encoding !== 'UTF-8') {
                $fileContent = mb_convert_encoding($fileContent, 'UTF-8', $encoding);
                if (!@file_put_contents($path, $fileContent)) {
                    throw new \Exception('ဖိုင်အား UTF-8 format သို့ပြောင်းလဲရာတွင် အမှားတစ်ခုဖြစ်နေပါသည်။');
                }
            }
            
            // Open the file with error handling
            $handle = @fopen($path, 'r');
            if ($handle === false) {
                throw new \Exception('ဖိုင်ဖွင့်၍မရပါ။ ကျေးဇူးပြု၍ ဖိုင်ကိုပြန်စစ်ပါ။');
            }
            
            // Read the first row to get headers
            $header = fgetcsv($handle);
            
            // Check if file is empty or has no headers
            if ($header === false || count($header) < 8) {
                throw new \Exception('CSV ဖိုင်တွင် အနည်းဆုံး လိုအပ်သော ကော်လံများ ပါရှိရပါမည်။');
            }
            
            // Trim all header values and remove BOM if present
            $header = array_map(function($value) {
                return trim(preg_replace('/\x{FEFF}/u', '', $value));
            }, $header);
            
            $requiredFields = [
                'group_name', 'position_name', 'guardian_name', 
                'guardian_code', 'branch_code', 'group_code', 
                'position_code', 'id_code'
            ];
            
            // Check for missing required columns
            $missingColumns = array_diff($requiredFields, $header);
            if (!empty($missingColumns)) {
                throw new \Exception('အောက်ပါကော်လံများ ပါရှိရန် လိုအပ်ပါသည်: ' . implode(', ', $missingColumns));
            }
            
            $imported = 0;
            $updated = 0;
            $skipped = 0;
            $errors = [];
            $line = 1; // Start from line 1 (header is line 1)
            
            // Start database transaction
            DB::beginTransaction();
            
            // Read each row of the CSV file
            while (($row = fgetcsv($handle)) !== false) {
                $line++;
                
                // Skip empty rows
                if (count(array_filter($row, function($value) { return $value !== ''; })) === 0) {
                    $skipped++;
                    continue;
                }
                
                // Ensure row has same number of columns as header
                if (count($row) !== count($header)) {
                    $errors[] = "စာကြောင်း {$line}: ကော်လံအရေအတွက် မကိုက်ညီပါ။";
                    $skipped++;
                    continue;
                }
                
                // Combine header with row values and trim
                $data = [];
                foreach ($header as $index => $key) {
                    $data[$key] = isset($row[$index]) ? trim($row[$index]) : '';
                }
                
                // Skip if all required fields are empty
                $allEmpty = true;
                foreach ($requiredFields as $field) {
                    if (!empty($data[$field])) {
                        $allEmpty = false;
                        break;
                    }
                }
                
                if ($allEmpty) {
                    $skipped++;
                    continue;
                }
                
                // Prepare data with proper types and validation
                $importData = [
                    'group_name' => $data['group_name'] ?? '',
                    'position_name' => $data['position_name'] ?? '',
                    'name' => $data['name'] ?? null,
                    'guardian_name' => $data['guardian_name'] ?? '',
                    'guardian_code' => strtoupper(trim($data['guardian_code'] ?? '')),
                    'branch_code' => (int)$data['branch_code'],
                    'group_code' => strtoupper(trim($data['group_code'] ?? '')),
                    'position_code' => strtoupper(trim($data['position_code'] ?? '')),
                    'id_code' => (int)$data['id_code'],
                ];
                
                // Generate ranking_id if not provided or empty
                if (empty($data['ranking_id'])) {
                    $importData['ranking_id'] = $this->generateRankingId($importData);
                } else {
                    $importData['ranking_id'] = trim($data['ranking_id']);
                }
                
                // Validate the data
                $rules = RankingCode::rules();
                // Remove the unique constraint for ranking_id during import since we're using updateOrCreate
                $rules['ranking_id'] = 'required|string|max:50';
                $validator = Validator::make($importData, $rules, RankingCode::validationMessages());
                
                if ($validator->fails()) {
                    $errors[] = "စာကြောင်း {$line}: " . implode(' ', $validator->errors()->all());
                    $skipped++;
                    continue;
                }
                
                // Update or create the record
                try {
                    $result = RankingCode::updateOrCreate(
                        ['ranking_id' => $importData['ranking_id']],
                        $importData
                    );
                    
                    $result->wasRecentlyCreated ? $imported++ : $updated++;
                    
                    // Clear the model cache for the next iteration
                    $result = null;
                } catch (\Exception $e) {
                    // Log the error but continue with next record
                    $errors[] = "စာကြောင်း {$line}: " . $e->getMessage();
                    $skipped++;
                    continue;
                }
            }
            
            // Commit the transaction after all rows are processed
            DB::commit();
            
            // Prepare success message with statistics
            $message = [
                'success' => true,
                'message' => 'ဒေတာများအား အောင်မြင်စွာ တင်သွင်းပြီးပါပြီ။',
                'stats' => [
                    'imported' => $imported,
                    'updated' => $updated,
                    'skipped' => $skipped,
                    'errors' => count($errors)
                ]
            ];
            
            // If there are errors, return with warning
            if (count($errors) > 0) {
                $message['type'] = 'warning';
                $message['message'] .= ' ' . count($errors) . ' ကြောင်းတွင် အမှားများရှိနေပါသည်။';
                
                return redirect()
                    ->route('ranking-codes.import.form')
                    ->with($message)
                    ->with('import_errors', $errors);
            }
            
            return redirect()
                ->route('ranking-codes.index')
                ->with($message);
                
        } catch (\Exception $e) {
            Log::error('Import Process Error: ' . $e->getMessage() . '\n' . $e->getTraceAsString());
            return back()->with('error', 'ဖိုင်တင်သွင်းရာတွင် အမှားတစ်ခုဖြစ်နေပါသည်။ ' . $e->getMessage());
        }
    }
    
    /**
     * Export ranking codes to CSV
     */
    public function export()
    {
        $fileName = 'ranking-codes-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $rankingCodes = RankingCode::all();

        $columns = [
            'group_name', 'position_name', 'name', 'guardian_name', 'guardian_code', 
            'branch_code', 'group_code', 'position_code', 'id_code', 'ranking_id'
        ];

        $callback = function() use ($rankingCodes, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($rankingCodes as $code) {
                $row = [];
                foreach ($columns as $column) {
                    $row[] = $code->$column;
                }
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
