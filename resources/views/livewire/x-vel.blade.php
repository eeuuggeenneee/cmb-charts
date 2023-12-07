<div>
    <h1 class="text-center mb-3">X Velocity</h1>
    <div class="row">
        <div class="col-xl-4 col-sm-12">
            <div class="col-sm-12">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="mb-0 me-3">Latest Data</h4>
                        <span class="display-4 ms-auto"
                            style="font-size: 1rem;"><strong>{{ $xVelTime }}</strong></span>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item text-center">
                            <i class="fas fa-shuttle-space fa-3x mr-3 text-black"></i>
                            <span class="display-5"><strong>{{ $latestXvel }}mm/s</strong></span>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="card mb-3">
                <div class="card-header">
                    Select
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label for="machineSelect">Select Machine:</label>
                        <select class="form-control" id="machineSelect" onchange="updateSensorOptions()">
                            <option value="machine1">200A</option>
                            <option value="machine2">200B</option>
                            <option value="machine3">200C</option>
                            <option value="machine4">200D</option>
                            <option value="machine5">90+</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sensorSelect">Select Sensor:</label>
                        <select class="form-control" id="sensorSelect" onchange="displaySensorValue()"
                            wire:model="selectedSensor" wire:change="selectedSensor" wire:ignore>
                        </select>
                    </div>

                    <div style="border-top: 1px solid black; margin: 10px 0;">
                        <label for="sensorSelect" class="text-center mt-3">Filter Date</label>
                        <div class="form-group">
                            <input type="date" wire:ignore class="form-control" id="startDate"
                                wire:model="start_date" wire:change="dateRangeChanged">
                        </div>
                        <h6 class="text-center">TO</h6>
                        <div class="form-group">
                            <input type="date" wire:ignore class="form-control" id="endDate" wire:model="end_date"
                                wire:change="dateRangeChanged">
                        </div>
                    </div>
                </div>

            </div>
            <div class="card mb-3">
                <div class="card-header">
                    Legends
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">X Velocity Alarm: <strong
                            style="color: red;">{{ $xValarm }}</strong></li>
                    <li class="list-group-item">X Velocity Warning: <strong
                            style="color: blue;">{{ $xVwarn }}</strong></li>
                    <li class="list-group-item">X Velocity Base: <strong
                            style="color: grey;">{{ $xVbase }}</strong></li>
                </ul>


            </div>
        </div>
        <div class="col-xl-8 col-sm-12">
            <div class="card">
                <div class="card-header">
                    X Velocity
                </div>
                <div class="card-body">


                    <canvas id="lineChart" width="500" height="600"></canvas>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function() {
            var ctx = document.getElementById('lineChart').getContext('2d');
            var chart;
            var data5 = @json($data);
            updateChart(data5, xValarm, xVwarn, xVbase)

            Livewire.on('sensorDataUpdated', function(data, xValarm, xVwarn, xVbase) {
                if (chart) {
                    chart.destroy();
                }
                updateChart(data, <?php echo $xVbase; ?>, <?php echo $xVwarn; ?>, <?php echo $xValarm; ?>)
            });

            function updateChart(data, xValarm, xVwarn, xVbase) {
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
                                id: 'y-axis-0', 
                                title: {
                                    display: true,
                                    text: 'X Velocity',
                                },
                                ticks: {
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
            }
        });
    </script>
    <script>
        const sensorOptions = {
            machine1: [{
                    value: 100,
                    label: "Select a Sensor",
                    disabled: true,
                    selected: true
                },
                {
                    value: 0,
                    label: "200A NDE"
                },
                {
                    value: 1,
                    label: "200A DE"
                },
                {
                    value: 2,
                    label: "200A E1"
                },
                {
                    value: 3,
                    label: "200A E2"
                },
            ],
            machine2: [{
                    value: 100,
                    label: "Select a Sensor",
                    disabled: true,
                    selected: true
                },
                {
                    value: 5,
                    label: "200B NDE"
                },
                {
                    value: 6,
                    label: "200B DE"
                },
                {
                    value: 7,
                    label: "200B E1"
                },
                {
                    value: 8,
                    label: "200B E2"
                },
            ],
            machine3: [{
                    value: 100,
                    label: "Select a Sensor",
                    disabled: true,
                    selected: true
                },
                {
                    value: 10,
                    label: "200C NDE"
                },
                {
                    value: 11,
                    label: "200C DE"
                },
                {
                    value: 12,
                    label: "200C E-DE"
                },
                {
                    value: 13,
                    label: "200C E-NDE"
                },
            ],
            machine4: [{
                    value: 100,
                    label: "Select a Sensor",
                    disabled: true,
                    selected: true
                },
                {
                    value: 15,
                    label: "200D NDE"
                },
                {
                    value: 16,
                    label: "200D DE"
                },
                {
                    value: 17,
                    label: "200C E-DE"
                },
                {
                    value: 18,
                    label: "200C E-NDE"
                },
            ],
            machine5: [{
                    value: 100,
                    label: "Select a Sensor",
                    disabled: true,
                    selected: true
                },
                {
                    value: 20,
                    label: "90+ NDE"
                },
                {
                    value: 21,
                    label: "90+ DE"
                },
                {
                    value: 22,
                    label: "90+ E-DE"
                },
                {
                    value: 23,
                    label: "90+ E-NDE"
                },
            ],
        };

        function updateSensorOptions() {
            const selectedMachine = document.getElementById("machineSelect").value;
            const sensorSelect = document.getElementById("sensorSelect");

            // Clear existing options
            sensorSelect.innerHTML = "";

            // If a machine is selected, show the sensor options
            if (selectedMachine !== "") {
                const sensorOptionsList = sensorOptions[selectedMachine];
                sensorOptionsList.forEach(sensor => {
                    const option = document.createElement("option");
                    option.value = sensor.value;
                    option.text = sensor.label;
                    if (sensor.disabled) {
                        option.disabled = true;
                    }
                    if (sensor.selected) {
                        option.selected = true;
                    }
                    sensorSelect.add(option);
                });

                sensorSelect.removeAttribute("disabled");
            } else {
                sensorSelect.setAttribute("disabled", "disabled");
                sensorSelect.value = "";
            }
        }

        function displaySensorValue() {
            const selectedSensorValue = document.getElementById("sensorSelect").value;

            if (selectedSensorValue !== "") {
                console.log("Selected Sensor Value: " + selectedSensorValue);
            }
        }

        updateSensorOptions();
    </script>
</div>
