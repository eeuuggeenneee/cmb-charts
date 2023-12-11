<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ZVel extends Component
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
    public $baselineValues = [80, 70];
    public $zValarm;
    public $zVwarn;
    public $zVbase;
    public $latestZvel;
    public $zVelTime;
    public $receivedData;
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

            $this->machineName = collect($this->sensorData)->pluck('machineName')->toArray();
        }
        //dd($this->sensorData);
        if (empty($this->start_date)) {
            $this->start_date = now()->firstOfMonth()->toDateString();
        }
        if (empty($this->end_date)) {
            $this->end_date = now()->addDay()->toDateString();
        }
        $this->slider_value = "00:00:00";

        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);
        $this->emit('sensorDataUpdated', $chartData,$this->zValarm ,$this->zVwarn, $this->zVbase, $this->latestZvel, $this->zVelTime);
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
                        $zvel = $dataPoint['z-vel'];
                       
                }
                $chartData[] = ['x' => $timestamp->format('M d y H:i'), 'y' => $zvel];
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
        $this->emit('sensorDataUpdated', $chartData,$this->zValarm ,$this->zVwarn, $this->zVbase, $this->latestZvel, $this->zVelTime);
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
                 // if (Carbon::parse($timestamp) >= "2023-12-06 00:00:00") {
                    if (!$latestTimestamp || $timestamp->diffInMinutes($latestTimestamp) >= 5) {
                        $zvel = $dataPoint['z-vel'];

                        $chartData[] = ['x' => $timestamp->format('M d y H:i'), 'y' => $zvel];
                        $latestTimestamp = $timestamp;
                    } else {
                    
                    }

                   // }

                    $this->zValarm = $dataPoint['z-vel-alarm'];
                    $this->zVwarn = $dataPoint['z-vel-warning'];
                    $this->zVbase = $dataPoint['z-vel-baseline'];
                    $this->latestZvel = $dataPoint['z-vel'];
                    $this->olddata = ['x' => $timestamp->format('M d y H:i'), 'y' => $zvel];

                    $this->zVelTime = $dataPoint['timestamp'];

                }
            }
        }


        return $chartData;
    }
    public function selectedSensor()
    {
        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);
        $this->emit('sensorDataUpdated', $chartData,$this->zValarm ,$this->zVwarn, $this->zVbase, $this->latestZvel, $this->zVelTime);
    }
    public function render()
    {
        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);
        return view('livewire.z-vel', [
            'data' => $chartData,
            'sensorNames' => $this->sensorNames,
            'machineName' => $this->machineName,

        ]);
    }
}
