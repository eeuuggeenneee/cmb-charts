<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ZAcc extends Component
{
    public $chartData;
    public $apiData;
    public $initialData;
    public $selectedSensor = 0;
    public $sensorData;
    public $machineData;
    public $sensorNames;
    public $machineName;
    public $latestTimestamp;
    public $latestTemp;
    public $zAalarm;
    public $zAwarn;
    public $zAbase;
    public $latestZacc;
    public $zAccTime;
    public $start_date;
    public $end_date;
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

            // Replace machineID with machineName in the sensor data
            $this->sensorData = collect($this->sensorData)->map(function ($sensor) {
                $machineID = $sensor['machineID'] ?? null;
                $sensor['machineName'] = $this->machineData[$machineID]['compressorname'] ?? '';
                unset($sensor['machineID']);
                return $sensor;
            })->toArray();

            $this->machineName = collect($this->sensorData)->pluck('machineName')->toArray();

           
        }
        //dd($this->sensorData);
        if (empty($this->start_date)) {
            $this->start_date = now()->firstOfMonth()->toDateString();
        }
        if (empty($this->end_date)) {
            $this->end_date = now()->addDay()->toDateString();
        }
        $this->selectedSensor = 0;
        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);

        $this->selectedSensor();
     
    
    }
    public function dateRangeChanged()
    {
        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);
        $this->emit('sensorDataUpdated', $chartData,$this->zAalarm ,$this->zAwarn, $this->zAbase, $this->latestZacc, $this->zAccTime);
    }
    public function updated($propertyName)
    {
        if ($propertyName === 'selectedMachine') {
            $this->selectedSensor = null;
            $this->emit('machineChanged', $this->selectedMachine);
        } elseif ($propertyName === 'selectedSensor' || $propertyName === 'start_date' || $propertyName === 'end_date') {
            $this->dateRangeChanged();
        }
    }
    public function sensor($selectedSensor, $start_date, $end_date)
    {
        $chartData = [];
        $latestTimestamp = null;
        $response = Http::get('http://172.31.2.124:5000/cbmdata/rawdata?sensor_ids=' . $selectedSensor . '&start_date=' . $start_date . '&end_date=' . $end_date);
        $this->apiData = $response->json();
        foreach ($this->apiData as $entry) {
            if (isset($entry['sensors'][$selectedSensor]['data'])) {
                foreach ($entry['sensors'][$selectedSensor]['data'] as $dataPoint) {
                    $timestamp = Carbon::parse($dataPoint['timestamp']);
                    //if (Carbon::parse($timestamp) >= "2023-12-05 12:00:00") {
                    if (!$latestTimestamp || $timestamp->diffInMinutes($latestTimestamp) >= 5) {
                        $zacc = $dataPoint['z-acc'];
                        $chartData[] = ['x' => $timestamp->format('M d y H:i'), 'y' => $zacc];
                        $latestTimestamp = $timestamp;
                        $zacclatest = $zacc;
                    }else{
                       
                    }
                    //}

                }
                $this->zAalarm = $dataPoint['z-acc-alarm'];
                $this->zAwarn = $dataPoint['z-acc-warning'];
                $this->zAbase = $dataPoint['z-acc-baseline'];
                $this->latestZacc = $dataPoint['z-acc'];
                $this->zAccTime = $timestamp->format('M d y H:i');
            }
        }

  
        return $chartData;
    }
    public function selectedSensor()
    {
        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);
        $this->emit('sensorDataUpdated', $chartData,$this->zAalarm ,$this->zAwarn, $this->zAbase, $this->latestZacc, $this->zAccTime);
    }
    public function render()
    {
        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);

        return view('livewire.z-acc',[
            'data' => $chartData,
            'sensorNames' => $this->sensorNames,
            'machineName' => $this->machineName,

        ]);
    }
}
