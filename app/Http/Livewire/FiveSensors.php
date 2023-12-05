<?php

namespace App\Http\Livewire;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\Request;
use Carbon\Carbon;


class FiveSensors extends Component
{  

    public $apiData;
    public $count;
    public $livewireKey;
    protected $refresh = 1;
    public function mount(){


        $response = Http::get('http://172.31.2.124:5000/cbmdata/rawdata?compressor_ids=1');
        $this->apiData = $response->json();
        $this->livewireKey = uniqid();

    }
    public function prepareChartData()
    {
        $chartData = [];
        $timestamps = [];
    
        // collect all
        foreach ($this->apiData as $entry) {
            foreach ($entry['sensors'] as $sensorIndex => $sensor) {
                foreach ($sensor['data'] as $dataPoint) {
                    $timestamps[] = $dataPoint['timestamp'];
                    $chartData[$sensorIndex][$dataPoint['timestamp']] = $dataPoint['temp'];
                }
            }
        }
    
        $timestamps = array_unique($timestamps);
        sort($timestamps);
    
        // set data for each sensor
        foreach ($chartData as $sensorIndex => $sensorData) {
            $chartData[$sensorIndex] = [];
    
            // populate chart with aligned data
            foreach ($timestamps as $timestamp) {
                $temp = isset($sensorData[$timestamp]) ? $sensorData[$timestamp] : null;
    
                $timestamp = Carbon::parse($timestamp);
    
                $chartData[$sensorIndex][] = [
                    'x' => $timestamp->format('M d y H:i'),
                    'y' => $temp,
                ];
            }
        }
    
        return $chartData;
    }
    
    
    
    
    
    
    public function render()
    {
        //dd($this->prepareChartData());
        return view('livewire.five-sensors',[
            'data' => $this->prepareChartData()
        ]);
    }
}
