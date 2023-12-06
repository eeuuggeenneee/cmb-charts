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


    <script>
        document.addEventListener('livewire:load', function() {
            var ctx = document.getElementById('lineChart').getContext('2d');
            var chart;
            var baselineValues = [50, 40]; // Hardcoded baseline values

            Livewire.on('sensorDataUpdated', function(data) {
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
                                        yMin: 0.595,
                                        yMax: 0.595,
                                        borderWidth: 2,
                                        borderColor: 'grey'
                                    },
                                    line2: {
                                        type: 'line',
                                        yMin: 1.215,
                                        yMax: 1.215,
                                        borderWidth: 2,
                                        borderColor: 'blue'
                                    },
                                    line3: {
                                        type: 'line',
                                        yMin: 1.525,
                                        yMax: 1.525,
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
