<div>
    <div class="card">

        <label for="sensorSelector">Select Sensor:</label>
        <!-- your-component.blade.php -->
        <select wire:model="selectedSensor" wire:change="selectedSensor" id="sensorSelector">
            <option selected disabled>Select Sensor</option>
            @foreach ($sensorNames as $sensorNumber => $sensorName)
                @if($sensorName == null)

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

    <script>
        document.addEventListener('livewire:load', function () {
            var ctx = document.getElementById('lineChart').getContext('2d');
            var chart;
    
            Livewire.on('sensorDataUpdated', function (data) {
                if (chart) {
                    chart.destroy();
                }
                updateChart(data);
            });
    
            function updateChart(data) {
                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        datasets: [{
                            label: 'Sensor',
                            data: data,
                            borderColor: 'rgb(75, 192, 192)',
                            borderWidth: 2,
                            fill: false,
                            pointRadius: 0,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: [{
                                type: 'time',
                                time: {
                                    unit: 'day',
                                    displayFormats: {
                                        day: 'D',
                                    },
                                },
                                title: {
                                    display: true,
                                    text: 'Time',
                                },
                                distribution: 'linear',
                            }],
                            y: [{
                                title: {
                                    display: true,
                                    text: 'Temperature',
                                },
                            }],
                        },
                    },
                });
            }
        });
    </script>
    





</div>
