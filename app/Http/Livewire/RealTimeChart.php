<?php

namespace App\Http\Livewire;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Component;


class RealTimeChart extends Component
{

    public $chartData;
    public $apiData;
    public $selectedMachine;
    public $selectedSensor = 0;
    public $sensorData;
    public $machineData;
    public $sensorNames;
    public $machineName;
    public $latestTimestamp;
    public $latestTemp;
    public $tempwarning;
    public $tempalarm;
    public $tempTime;

   

    public function mount()
    {

        $machineResponse = Http::get('http://172.31.2.124:5000/cbmdata/compressorlist');
        $this->machineData = $machineResponse->json();

        $sensordata = Http::get('http://172.31.2.124:5000/cbmdata/sensorlist');
        $this->sensorData = $sensordata->json();


        if (!empty($this->sensorData)) {
            $this->sensorNames = collect($this->sensorData)->pluck('sensorName')->toArray();

            $firstKey = key($this->sensorData);
            $this->selectedSensor = $firstKey;

            $this->sensorData = collect($this->sensorData)->map(function ($sensor) {
                $machineID = $sensor['machineID'] ?? null;
                $sensor['machineName'] = $this->machineData[$machineID]['compressorname'] ?? '';
                unset($sensor['machineID']);
                return $sensor;
            })->toArray();

            $this->machineName = collect($this->sensorData)->pluck('machineName')->toArray();
        }
        //dd($this->sensorData);
    
   
        $chartData = $this->sensor($this->selectedSensor);
        $this->emit('firstload', $chartData,$this->tempalarm, $this->tempwarning,$this->tempTime,$this->latestTemp);
     

    }
    public function updatedSelectedMachine()
    {
        $this->selectedSensor = null;
        $this->emit('machineChanged', $this->selectedMachine); 
    }

    
    public function sensor($selectedSensor)
    {
        $chartData = [];
        $latestTimestamp = null;
        $response = Http::get('http://172.31.2.124:5000/cbmdata/rawdata?sensor_ids='.$selectedSensor);
        $this->apiData = $response->json();
        foreach ($this->apiData as $entry) {
            if (isset($entry['sensors'][$selectedSensor]['data'])) {
                foreach ($entry['sensors'][$selectedSensor]['data'] as $dataPoint) {
                    $timestamp = Carbon::parse($dataPoint['timestamp']);
                    //if (Carbon::parse($timestamp) >= "2023-12-05 12:00:00") {
                    if (!$latestTimestamp || $timestamp->diffInMinutes($latestTimestamp) >= 5) {
                        $temp = $dataPoint['temp'];
                      
                        $chartData[] = ['x' => $timestamp->format('M d y H:i'), 'y' => $temp];
                        $latestTimestamp = $timestamp;
                    }else{
                      
                    }
                    //}
                  
                }
                $this->tempalarm = $dataPoint['temp-alarm'];
                $this->tempwarning = $dataPoint['temp-warning'];
         
                $this->latestTemp = $dataPoint['temp'];
                $this->tempTime = $timestamp->format('M d y H:i');
            }
        }


        return $chartData;
    }
    public function selectedSensor()
    {
        $chartData = $this->sensor($this->selectedSensor);
        $this->emit('sensorDataUpdated', $chartData,$this->tempalarm, $this->tempwarning,$this->tempTime,$this->latestTemp);
    }

    public function render()
    {


        $chartData = $this->sensor($this->selectedSensor);
    
        return view('livewire.real-time-chart', [
            'data' => $chartData,
            'sensorNames' => $this->sensorNames,
            'machineName' => $this->machineName,
        ]);
    }

   
}
