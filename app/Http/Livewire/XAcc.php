<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class XAcc extends Component
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
    public $xAalarm;
    public $xAwarn;
    public $xAbase;
    public $latestXacc;
    public $xAccTime;
    public $start_date;
    public $end_date;
    public $slider_value;
    public $olddata;
    protected $listeners = ['dateRangeChanged', 'sliderValueChanged'];

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
            $this->slider_value = "00:00:00";
            $this->machineName = collect($this->sensorData)->pluck('machineName')->toArray();
        }
        //dd($this->sensorData);
        if (empty($this->start_date)) {
            $this->start_date = now()->startOfWeek()->toDateString();
        }
        if (empty($this->end_date)) {
            $this->end_date = now()->endOfWeek()->toDateString();
        }
        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);
        $this->emit('sensorDataUpdated', $chartData,$this->xAbase ,$this->xAalarm, $this->xAwarn, $this->xAccTime, $this->latestXacc);
    }

    public function getSensorData($sensor)
    {
        $chartData = [];
      
        $response = Http::get('http://172.31.2.124:5000/cbmdata/rawdata?sensor_ids=' . $sensor);
        $this->apiData = $response->json();
        foreach ($this->apiData as $entry) {
            if (isset($entry['sensors'][$sensor]['data'])) {
                foreach ($entry['sensors'][$sensor]['data'] as $dataPoint) {
                    $timestamp = Carbon::parse($dataPoint['timestamp']);
                        $xacc = $dataPoint['x-acc'];
                       
                }
                $chartData[] = ['x' => $timestamp->format('M d y H:i'), 'y' => $xacc];
            }
        }
        return $chartData;
    }

    public function sliderValueChanged($value)
    {
        $this->slider_value = $value;
        $this->dateRangeChanged();
    }
    
    public function dateRangeChanged()
    {
        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);
        $this->emit('sensorDataUpdated', $chartData,$this->xAbase ,$this->xAalarm, $this->xAwarn, $this->xAccTime, $this->latestXacc);
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
      
        $start_date = $start_date ." ". $this->slider_value;
  
        $response = Http::get('http://172.31.2.124:5000/cbmdata/rawdata?sensor_ids=' . $selectedSensor . '&start_date='.$start_date .'&end_date='. $end_date .' 00:00:00');
        $this->apiData = $response->json();
        foreach ($this->apiData as $entry) {
            if (isset($entry['sensors'][$selectedSensor]['data'])) {
                foreach ($entry['sensors'][$selectedSensor]['data'] as $dataPoint) {
                    $timestamp = Carbon::parse($dataPoint['timestamp']);
                    //if (Carbon::parse($timestamp) >= "2023-12-05 12:00:00") {
                    if (!$latestTimestamp || $timestamp->diffInMinutes($latestTimestamp) >= 5) {
                        $xacc = $dataPoint['x-acc'];
                        $chartData[] = ['x' => $timestamp->format('M d y H:i'), 'y' => $xacc];
                        $latestTimestamp = $timestamp;
                        $zacclatest = $xacc;
                    } else {
                    }
                    //}

                }
                $this->xAalarm = $dataPoint['x-acc-alarm'];
                $this->xAwarn = $dataPoint['x-acc-warning'];
                $this->xAbase = $dataPoint['x-acc-baseline'];
                $this->olddata = ['x' => $timestamp->format('M d y H:i'), 'y' => $xacc];
                $this->latestXacc = $dataPoint['x-acc'];
                $this->xAccTime = $timestamp->format('M d y H:i');
            }
        }
       //dd($chartData);
        return $chartData;
    }
    public function selectedSensor()
    {
        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);
        $this->emit('sensorDataUpdated', $chartData,$this->xAbase ,$this->xAalarm, $this->xAwarn, $this->xAccTime, $this->latestXacc);
    }
    public function render()
    {

        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);

        return view('livewire.x-acc', [
            'data' => $chartData,
            'olddata' => $this->olddata,
            'sensorNames' => $this->sensorNames,
            'machineName' => $this->machineName,
        ]);
    }
}
