<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RankingCodesExport;
use App\Imports\RankingCodesImport;

class RankingCodeController extends Controller
{
    /**
     * Excel ဖိုင်မှ Ranking Codes import လုပ်ခြင်း
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new RankingCodesImport, $request->file('file'));

            return redirect()->route('ranking-codes.index')
                ->with('success', 'Excel ဖိုင်မှ Ranking Codes import လုပ်ပြီးပါပြီ။');
        } catch (\Exception $e) {
            return redirect()->route('ranking-codes.index')
                ->with('error', 'Import လုပ်ရာတွင် အမှားအယွင်းရှိပါသည်: ' . $e->getMessage());
        }
    }

    /**
     * Export ranking codes to Excel
     */
    public function export()
    {
        return Excel::download(new RankingCodesExport, 'ranking_codes_' . date('Y-m-d') . '.xlsx');
    }
}