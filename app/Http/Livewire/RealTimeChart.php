<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Carbon\Carbon;

class RealTimeChart extends Component
{

    public $chartData;
    public $apiData;
    public $initialData;
    public $selectedSensor = 0;
    public $sensorData;
    public $machineData;
    public $sensorNames;
    public $machineName;

        protected $listeners = ['getData' => 'updateChart'];

    public function mount()
    {
        $response = Http::get('http://172.31.2.124:5000/cbmdata/rawdata');

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

            //dd($this->machineName);

        }
        //dd($this->sensorData);

        $this->apiData = $response->json();
    }
    // public function sensor($selectedSensor)
    // {
    //     $chartData = [];
    //     foreach ($this->apiData as $entry) {
    //         if (isset($entry['sensors'][$selectedSensor]['data'])) {
    //             foreach ($entry['sensors'][$selectedSensor]['data'] as $dataPoint) {
    //                 //dd($dataPoint['timestamp']);
    //                 if (Carbon::parse($dataPoint['timestamp']) >= "2023-12-05 00:00:00") {
    //                     $temp = $dataPoint['temp'];
    //                     $timestamp = $dataPoint['timestamp'];
    //                     $chartData[] = ['x' => $timestamp, 'y' => $temp];
    //                     $latestTimestamp = $timestamp;
    //                 }
    //             }
    //         }
    //     }
    //     return $chartData;
    // }

    // public function sensor()
    // {
    //     $chartData = [];
    //     $dayLabels = [];

    //     foreach ($this->apiData as $entry) {
    //         if (isset($entry['sensors'][0]['data'])) {
    //             foreach ($entry['sensors'][0]['data'] as $dataPoint) {
    //                 $timestamp = $dataPoint['timestamp'];

    //                 // Check if the timestamp is after or equal to "2023-11-05 00:00:00"
    //                 if (Carbon::parse($timestamp) >= "2023-11-05 00:00:00") {
    //                     $temp = $dataPoint['temp'];
    //                     $day = Carbon::parse($timestamp)->format('Y-m-d');

    //                     // Store the unique day for labeling
    //                     if (!in_array($day, $dayLabels)) {
    //                         $dayLabels[] = $day;
    //                     }

    //                     $chartData[] = ['x' => $day, 'y' => $temp];
    //                 }
    //             }
    //         }
    //     }

    //     // Sort the day labels to ensure they are in order
    //     sort($dayLabels);

    //     return $chartData;
    // }

    public function sensor($selectedSensor)
    {
        $chartData = [];
        $latestTimestamp = null;

        foreach ($this->apiData as $entry) {
            if (isset($entry['sensors'][$selectedSensor]['data'])) {
                foreach ($entry['sensors'][$selectedSensor]['data'] as $dataPoint) {
                    $timestamp = Carbon::parse($dataPoint['timestamp']);
                    if (Carbon::parse($timestamp) >= "2023-12-05 12:00:00") {
                        if (!$latestTimestamp || $timestamp->diffInMinutes($latestTimestamp) >= 5) {
                            $temp = $dataPoint['temp'];
                            $chartData[] = ['x' => $timestamp->format('M d y H:i'), 'y' => $temp];

                            $latestTimestamp = $timestamp;
                        }
                    }
                }
            }
        }

        return $chartData;
    }
    public function selectedSensor()
    {
        $chartData = $this->sensor($this->selectedSensor);
        $this->emit('sensorDataUpdated', $chartData);
    }
    public function getCurrentData()
    {
        return $this->sensor($this->selectedSensor);
    }
    public function updateChart()
    {
        $this->emit('updateChart', $this->getCurrentData());
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

    public function fetchDataFromApi()
    {

        try {
            $response = Http::get('');

            if ($response->successful()) {
                return $response->body();
            } else {
                return '{}';
            }
        } catch (\Exception $e) {

            return '{}';
        }
    }
}
