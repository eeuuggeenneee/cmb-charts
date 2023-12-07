<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class XVel extends Component
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
    public $xValarm;
    public $xVwarn;
    public $xVbase;
    public $latestXvel;
    public $xVelTime;
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
 
   
        $chartData = $this->sensor($this->selectedSensor);
        $this->emit('sensorDataUpdated', $chartData, $this->xValarm, $this->xVwarn, $this->xVbase);
     

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
                        $xvel = $dataPoint['x-vel'];
                      
                        $chartData[] = ['x' => $timestamp->format('M d y H:i'), 'y' => $xvel];
                        $latestTimestamp = $timestamp;
                    }else{
            
                    }
                    //}
                  
                }
                $this->xValarm = $dataPoint['x-vel-alarm'];
                $this->xVwarn = $dataPoint['x-vel-warning'];
                $this->xVbase = $dataPoint['x-vel-baseline'];
                $this->latestXvel = $dataPoint['x-vel'];
                $this->xVelTime = $timestamp->format('M d y H:i');
            }
        }


        return $chartData;
    }
    public function selectedSensor()
    {
        $chartData = $this->sensor($this->selectedSensor);
        $this->emit('sensorDataUpdated', $chartData,$this->xValarm, $this->xVwarn, $this->xVbase,$this->latestXvel,$this->xVelTime);
    }

    public function render()
    {
        $chartData = $this->sensor($this->selectedSensor);
        return view('livewire.x-vel', [
            'data' => $chartData,
            'sensorNames' => $this->sensorNames,
            'machineName' => $this->machineName,

        ]);
    }
}
