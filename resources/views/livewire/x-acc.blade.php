<div>
    <h1 class="text-center mb-3">X Acceleration</h1>
    <div class="row">
        <div class="col-4">
            <div class="card mb-5">
                <div class="card-header d-flex align-items-center">
                    <h4 class="mb-0 me-3">Latest Data</h4>
                    <span class="display-4 ms-auto" style="font-size: 1rem;"><strong>{{ $xAccTime }}</strong></span>
                </div>
                
                
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center">
                        <i class="fas fa-shuttle-space fa-3x mr-3 text-black"></i>
                        <span class="display-4"><strong>{{ $latestXacc }}g</strong></span>
                    </li>
                </ul>
            </div>
            
            <div class="card mb-5">
                <div class="card-header">
                    Select
                </div>
                <div class="card-body">
                    <label for="sensorSelector">Select Sensor:</label>
                    <select wire:model="selectedSensor" wire:change="selectedSensor" id="sensorSelector" class="form-select">
            
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
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Legends
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">X Acceleration Alarm: <strong style="color: red;">{{ $xAalarm }}</strong></li>
                    <li class="list-group-item">X Acceleration Warning: <strong style="color: blue;">{{ $xAwarn }}</strong></li>
                    <li class="list-group-item">X Acceleration Base: <strong style="color: grey;">{{ $xAbase }}</strong></li>
                </ul>
                
                
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                   Z Velocity
                </div>
                <div class="card-body">
            
                        <canvas id="lineChart" width="500" height="500"></canvas>
                
                </div>
            </div>
        </div>
    </div>




    <script>
        document.addEventListener('livewire:load', function() {
            var ctx = document.getElementById('lineChart').getContext('2d');
            var chart;


            Livewire.on('sensorDataUpdated', function(data, xAalarm, xAwarn, xAbase) {
                if (chart) {
                    chart.destroy();
                }
             
                updateChart(data, xAalarm, xAwarn, xAbase);
            });

            function updateChart(data, xAalarm, xAwarn, xAbase) {
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
                                    text: 'X Velocity',
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
                                        yMin: xAbase, //here
                                        yMax: xAbase, //here
                                        borderWidth: 2,
                                        borderColor: 'grey' 
                                    },
                                    line2: {
                                        type: 'line',
                                        yMin: xAwarn, //here
                                        yMax: xAwarn, //here
                                        borderWidth: 2,
                                        borderColor: 'blue'
                                    },
                                    line3: {
                                        type: 'line',
                                        yMin: xAalarm, //here
                                        yMax: xAalarm, //here
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
