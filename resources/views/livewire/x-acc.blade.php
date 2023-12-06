<div>


    <label for="sensorSelector">Select Sensor:</label>
    <!-- your-component.blade.php -->
    <select wire:model="selectedSensor" wire:change="selectedSensor" id="sensorSelector">
        <option selected disabled>Select Sensor</option>
        @foreach ($sensorNames as $sensorNumber => $sensorName)
            @if ($sensorName == null)
            @else
                <option value="{{ $sensorNumber }}">
                    Sensor {{ $sensorNumber }} - {{ $sensorName }} - {{ $machineName[$sensorNumber] ?? '' }}
                </option>
            @endif
        @endforeach
    </select>

    <div class="card-body">
        <canvas id="lineChart" width="500" height="500"></canvas>
    </div>


 
</div>
