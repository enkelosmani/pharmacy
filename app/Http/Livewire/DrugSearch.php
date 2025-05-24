<?php

namespace App\Http\Livewire;

use App\Services\DrugSearchService;
use App\Models\Drug;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class DrugSearch extends Component
{
    use WithPagination;

    public $ndcCodes = '';
    public $results = [];
    public $loading = false;
    public $exporting = false;

    protected $rules = [
        'ndcCodes' => 'required',
    ];

    public function search()
    {
        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to search.');
            return;
        }

        $this->validate();
        $this->loading = true;

        $service = new DrugSearchService();
        $this->results = $service->searchDrugs($this->ndcCodes);

        $this->loading = false;
    }

    public function export()
    {
        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to export.');
            return;
        }

        $this->exporting = true;
        $service = new DrugSearchService();
        $filename = $service->exportToCsv($this->results);
        $this->exporting = false;

        return response()->download(storage_path('app/public/' . $filename))->deleteFileAfterSend(true);
    }

    public function deleteDrug($ndcCode)
    {
        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to delete drugs.');
            return;
        }

        Drug::where('ndc_code', $ndcCode)->delete();
        $this->results = array_filter($this->results, fn($result) => $result['ndc_code'] !== $ndcCode);
    }

    public function render()
    {
        $savedDrugs = Drug::paginate(10);
        return view('livewire.drug-search', ['savedDrugs' => $savedDrugs])
            ->layout('layouts.app');
    }
}
