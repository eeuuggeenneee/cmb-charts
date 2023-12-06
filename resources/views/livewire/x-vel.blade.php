<div>
    <div class="row">
        <div class="col-4">
            <div class="card mb-5">
                <div class="card-header d-flex align-items-center">
                    <h4 class="mb-0 me-3">Latest Data</h4>
                    <span class="display-4 ms-auto" style="font-size: 1rem;"><strong>{{ $xVelTime }}</strong></span>
                </div>
                
                
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center">
                        <i class="fas fa-shuttle-space fa-3x mr-3 text-black"></i>
                        <span class="display-4"><strong>{{ $latestXvel }}g</strong></span>
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
                    <li class="list-group-item">X Velocity Alarm: <strong style="color: red;">{{ $xValarm }}</strong></li>
                    <li class="list-group-item">X Velocity Warning: <strong style="color: blue;">{{ $xVwarn }}</strong></li>
                    <li class="list-group-item">X Velocity Base: <strong style="color: grey;">{{ $xVbase }}</strong></li>
                </ul>
                
                
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    X Velocity
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
            var data5 = @json($data);
            updateChart(data5);

            Livewire.on('sensorDataUpdated', function(data, xValarm, xVwarn, xVbase) {
                if (chart) {
                    chart.destroy();
                }
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
                                        yMin: xVbase, //here
                                        yMax: xVbase, //here
                                        borderWidth: 2,
                                        borderColor: 'grey'
                                    },
                                    line2: {
                                        type: 'line',
                                        yMin: xVwarn, //here
                                        yMax: xVwarn, //here
                                        borderWidth: 2,
                                        borderColor: 'blue'
                                    },
                                    line3: {
                                        type: 'line',
                                        yMin: xValarm, //here
                                        yMax: xValarm, //here
                                        borderWidth: 2,
                                        borderColor: 'red'
                                    },
                                }
                            }
                        },
                    },

                });
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
                                        yMin: <?php echo $xVbase; ?>, //here
                                        yMax: <?php echo $xVbase; ?>, //here
                                        borderWidth: 2,
                                        borderColor: 'grey'
                                    },
                                    line2: {
                                        type: 'line',
                                        yMin: <?php echo $xVwarn; ?>, //here
                                        yMax: <?php echo $xVwarn; ?>, //here
                                        borderWidth: 2,
                                        borderColor: 'blue'
                                    },
                                    line3: {
                                        type: 'line',
                                        yMin: <?php echo $xValarm; ?>, //here
                                        yMax: <?php echo $xValarm; ?>, //here
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
