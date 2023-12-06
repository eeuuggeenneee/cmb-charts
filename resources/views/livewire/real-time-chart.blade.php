<div>


    <h1 class="text-center mb-3">Temperature</h1>
    <div class="row">
        <div class="col-4">
            <div class="card mb-5">
                <div class="card-header d-flex align-items-center">
                    <h4 class="mb-0 mr-3">Latest Data</h4>
                    <span class="display-4 ml-auto" style="font-size: 1rem;"><strong>{{ $tempTime }}</strong></span>
                </div>
                
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center">

                        <i class="fa-solid fa-3x mr-3 fa-temperature-empty"></i>
                        <span class="display-4"><strong>{{ $latestTemp }}c</strong></span>
                    </li>
                </ul>
            </div>
            
            
            <div class="card">
                <div class="card-header">
                    Legends
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Temperature Alarm: <strong style="color: red;">{{ $tempalarm }}°</strong></li>
                    <li class="list-group-item">Temperature Warning: <strong style="color: blue;">{{ $tempwarning }}°</strong></li>
                  
                </ul>
                
                
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    Temperature
                </div>
                <div class="card-body">
                    <label for="sensorSelector" class="form-label">Select Sensor:</label>
                    <select wire:model="selectedSensor" wire:change="selectedSensor" class="form-select mb-3" id="sensorSelector">
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
            </div>
            
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function() {
            var ctx = document.getElementById('lineChart').getContext('2d');
            var chart;


            Livewire.on('sensorDataUpdated', function(data,tempwarning,tempalarm) {
                if (chart) {
                    chart.destroy();
                }
                updateChart(data,tempwarning,tempalarm);
            });

            function updateChart(data,tempwarning,tempalarm) {
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
                                    text: 'Temperature',
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
                                        yMin: tempalarm,
                                        yMax: tempalarm,
                                        borderWidth: 2,
                                        borderColor: 'red'
                                    },
                                    line2: {
                                        type: 'line',
                                        yMin: tempwarning,
                                        yMax: tempwarning,
                                        borderWidth: 2,
                                        borderColor: 'blue'
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
