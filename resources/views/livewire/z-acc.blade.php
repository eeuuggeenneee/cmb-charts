<div>
    <h1 class="text-center mb-3">Z Acceleration</h1>
    <div class="row">
        <div class="col-4">
            <div class="card mb-5">
                <div class="card-header d-flex align-items-center">
                    <h4 class="mb-0 mr-3">Latest Data</h4>
                    <span class="display-4 ml-auto" style="font-size: 1rem;"><strong>{{ $zAccTime }}</strong></span>
                </div>
                
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="fas fa-shuttle-space fa-3x mr-3 text-black"></i>
                        <span class="display-4"><strong>{{ $latestZacc }}g</strong></span>
                    </li>
                </ul>
            </div>
            
            
            <div class="card">
                <div class="card-header">
                    Legends
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Z Acceleration Alarm: <strong style="color: red;">{{ $zAalarm }}</strong></li>
                    <li class="list-group-item">Z Acceleration Warning: <strong style="color: blue;">{{ $zAwarn }}</strong></li>
                    <li class="list-group-item">Z Acceleration Base: <strong style="color: grey;">{{ $zAbase }}</strong></li>
                </ul>
                
                
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                   Z Acceleration
                </div>
                <div class="card-body">
                    <label for="sensorSelector">Select Sensor:</label>
                    <select wire:model="selectedSensor" wire:change="selectedSensor" id="sensorSelector">

                        <option selected disabled>Select Sensor</option>
                        @foreach ($sensorNames as $sensorNumber => $sensorName)
                            @if ($sensorName == null)
                            @else
                                <option value="{{ $sensorNumber }}">
                                    Sensor {{ $sensorNumber }} - {{ $sensorName }} -
                                    {{ $machineName[$sensorNumber] ?? '' }}
                                </option>
                            @endif
                        @endforeach

                    </select>

                    <div class="card-body">
                        <canvas id="lineChart" width="500" height="500"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('livewire:load', function() {
            var ctx = document.getElementById('lineChart').getContext('2d');
            var chart;
            var baselineValues = [50, 40]; // Hardcoded baseline values

            Livewire.on('sensorDataUpdated', function(data,zAalarm, zAwarn, zAbase) {
                if (chart) {
                    chart.destroy();
                }
                updateChart(data,zAalarm, zAwarn, zAbase);
            });

            function updateChart(data,zAalarm, zAwarn, zAbase) {
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
                            xAxes: [{
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
                            }],
                            yAxes: [{
                                id: 'y-axis-0', // use 'y-axis-0' for the first y-axis
                                title: {
                                    display: true,
                                    text: 'X Acceleration',
                                },
                                ticks: {
                                    // Adjust the y-axis scale properties if needed
                                },
                            }],
                        },
                        plugins: {
                            annotation: {
                                annotations: {
                                    line1: {
                                        type: 'line',
                                        yMin: zAbase,
                                        yMax: zAbase,
                                        borderWidth: 2,  
                                        borderColor: 'grey'
                                    },
                                    line2: {
                                        type: 'line',
                                        yMin: zAwarn,
                                        yMax: zAwarn,
                                        borderWidth: 2,
                                        borderColor: 'blue'
                                    },
                                    line3: {
                                        type: 'line',
                                        yMin: zAalarm,
                                        yMax: zAalarm,
                                        borderWidth: 2,
                                        borderColor: 'red'
                                    },
                                }
                            }
                        },
                    },

                });
            }
        });
    </script>
</div>
