<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Carbon\Carbon;

class Forecast extends Component
{
    public $chartData;
    public $apiData;
    public $initialData;
    public $selectedSensor;
    public $sensorData;
    public $machineData;
    public $sensorNames;
    public $machineName;
    public $latestTimestamp;

    public $xValarm;
    public $xVwarn;
    public $xVbase;
    public $latestXvel;
    public $xVelTime;
    public $start_date;
    public $end_date;
    public $slider_value;
    public $olddata;

    protected $listeners = ['dateRangeChanged', 'sliderValueChanged'];

    public function mount()
    {
        if (empty($this->start_date)) {
            $this->start_date = now()->firstOfMonth()->toDateString();
        }

        if (empty($this->end_date)) {
            $this->end_date = now()->lastOfMonth()->toDateString();
        }
        $this->slider_value = "00:00:00";
        $this->selectedSensor = 0;
        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);
        $this->emit('sensorDataUpdated', $chartData, $this->xValarm, $this->xVwarn, $this->xVbase);
    }
    public function getForecastData($sensor)
    {
        $forecastData = [];
        $min = [];
        $max = [];
        $today = Carbon::today()->addDay(-2);
       
        $response = Http::get('http://172.31.3.40:5000/get_forecast/' . $sensor . '/temp/1D');
        $this->apiData = $response->json();
        foreach ($this->apiData as $entry) {
            $timestamp = Carbon::parse($entry['date']);
            if ($timestamp->greaterThanOrEqualTo($today)) {
                $xvel = $entry['val'];
                $xvel_max = $entry['val_max'];
                $xvel_min = $entry['val_min'];
                $forecastData[] = ['x' => $timestamp->format('M d y'), 'y' => $xvel];
                $min[] = ['x' => $timestamp->format('M d y'), 'y' => $xvel_min];
                $max[] = ['x' => $timestamp->format('M d y'), 'y' => $xvel_max];
            }
        }
        return [
            'forecastData' => $forecastData,
            'min' => $min,
            'max' => $max,
        ];
    }
    public function dateRangeChanged()
    {
        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);
        $this->emit('sensorDataUpdated', $chartData, $this->xValarm, $this->xVwarn, $this->xVbase, $this->latestXvel, $this->xVelTime);
    }
    public function sliderValueChanged($value)
    {
        $this->slider_value = $value;
        $this->dateRangeChanged();
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

    public function sensor($selectedSensor, $start_date)
    {
        $chartData = [];
        $latestTimestamp = null;
        $response = Http::get('http://172.31.2.124:5000/cbmdata/dailydata?sensor_ids=' . $selectedSensor . '&start_date=2023-12-01');
        $this->apiData = $response->json();
        foreach ($this->apiData as $entry) {
            if (isset($entry['sensors'][$selectedSensor]['data'])) {
                foreach ($entry['sensors'][$selectedSensor]['data'] as $dataPoint) {
                    $timestamp = Carbon::parse($dataPoint['date']);
                    $xvel = $dataPoint['median temp'];
                    $chartData[] = ['x' => $timestamp->format('M d y'), 'y' => $xvel];
                }
            }
        }

        usort($chartData, function ($a, $b) {
            return strtotime($a['x']) - strtotime($b['x']);
        });

        return $chartData;
    }
    public function selectedSensor()
    {
        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);
        $this->emit('sensorDataUpdated', $chartData, $this->xValarm, $this->xVwarn, $this->xVbase, $this->latestXvel, $this->xVelTime);
    }
    public function render()

    {
        $chartData = $this->sensor($this->selectedSensor, $this->start_date, $this->end_date);
        $forecastData = $this->getForecastData($this->selectedSensor);
        return view('livewire.forecast', [
            'data' => $chartData,
            'forecast' => $forecastData['forecastData'],
            'min' => $forecastData['min'],
            'max' => $forecastData['max'],
            'sensorNames' => $this->sensorNames,
            'machineName' => $this->machineName,

        ]);
    }
}
