<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class RealTimeChart extends Component
{

    public $chartData;
    public $apiData;
    public $selectedMachine;
    public $selectedSensor = 0;
    public $olddata = [];
    public $sensorData;
    public $machineData;
    public $sensorNames;
    public $machineName;
    public $latestTimestamp;
    public $latestTemp;
    public $tempwarning;
    public $tempalarm;
    public $tempTime;
    public $start_date;
    public $end_date;
    public $slider_value;


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
        if (empty($this->start_date)) {
            $this->start_date = now()->startOfWeek()->toDateString();
        }
        if (empty($this->end_date)) {
            $this->end_date = now()->endOfWeek()->toDateString();
        }

        $this->slider_value = "00:00:00";
        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);
        $this->emit('sensorDataUpdated', $chartData, $this->tempalarm, $this->tempwarning, $this->tempTime, $this->latestTemp);
    }

    public function dateRangeChanged()
    {
        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);
        $this->emit('sensorDataUpdated', $chartData, $this->tempalarm, $this->tempwarning, $this->tempTime, $this->latestTemp,$this->slider_value);
    }
 
    public function sliderValueChanged($value)
    {
        $this->slider_value = $value;
        //dd($value);
        $this->dateRangeChanged();
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
                        $temp = $dataPoint['temp'];
                       
                }
                $chartData[] = ['x' => $timestamp->format('M d y H:i'), 'y' => $temp];
            }
        }
        return $chartData;
    }
    public function sensor($selectedSensor, $start_date, $end_date)
    {
        $chartData = [];
        $latestTimestamp = null;
        $start_date = $start_date ." ". $this->slider_value;
        // dd($start_date);
        // $end_date = $end_date . "12:00:00";
        $response = Http::get('http://172.31.2.124:5000/cbmdata/rawdata?sensor_ids=' . $selectedSensor . '&start_date='.$start_date .'&end_date='. $end_date .' 00:00:00');
        $this->apiData = $response->json();
        foreach ($this->apiData as $entry) {
            if (isset($entry['sensors'][$selectedSensor]['data'])) {
                foreach ($entry['sensors'][$selectedSensor]['data'] as $dataPoint) {
                    $timestamp = Carbon::parse($dataPoint['timestamp']);
                    if (!$latestTimestamp || $timestamp->diffInMinutes($latestTimestamp) >= 5) {
                        $temp = $dataPoint['temp'];

                        $chartData[] = ['x' => $timestamp->format('M d y H:i'), 'y' => $temp];
                        $latestTimestamp = $timestamp;
                    } else {
                    }
                }
                $this->tempalarm = $dataPoint['temp-alarm'];
                $this->tempwarning = $dataPoint['temp-warning'];
                $this->olddata = ['x' => $timestamp->format('M d y H:i'), 'y' => $temp];
                $this->latestTemp = $dataPoint['temp'];
                $this->tempTime = $timestamp->format('M d y H:i');
            }
        }
      
        return $chartData;
    }
    public function selectedSensor()
    {

        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);

        $this->emit('sensorDataUpdated', $chartData, $this->tempalarm, $this->tempwarning, $this->tempTime, $this->latestTemp);
    }


    public function render()
    {

        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);

        return view('livewire.real-time-chart', [
            'data' => $chartData,
            'olddata' => $this->olddata,
            'sensorNames' => $this->sensorNames,
            'machineName' => $this->machineName,
        ]);
    }
}
