<div>


    <h1 class="text-center mb-3">Temperature</h1>
    <div class="row">
        <div class="col-xl-4 col-sm-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center">
                    <h4 class="mb-0 me-3">Latest Data</h4>
                    <span class="display-4 ms-auto" style="font-size: 1rem;"><strong>{{ $tempTime }}</strong></span>
                </div>


                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center">

                        <i class="fa-solid fa-3x mr-3 fa-temperature-empty"></i>
                        <span class="display-4"><strong>{{ $latestTemp }}c</strong></span>
                    </li>
                </ul>
            </div>

            <div class="card mb-2">
                <div class="card-header">
                     <strong>Filter</strong>
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

                    <div class="form-group mb-3">
                        <label for="sensorSelect">Select Sensor:</label>
                        <select class="form-control" id="sensorSelect" onchange="displaySensorValue()"
                            wire:model="selectedSensor" wire:change="selectedSensor" wire:ignore>
                        </select>
                    </div>
                    <div style="border-top: 1px solid black; margin: 10px 0;">
                        <label for="sensorSelect" class="text-center mt-3">Filter Date:</label>
                        <div class="form-group">
                            <input type="date" wire:ignore class="form-control" id="startDate" wire:model="start_date" wire:change="dateRangeChanged">
                        </div>
                        <h6 class="text-center">TO</h6>
                        <div class="form-group">
                            <input type="date"  wire:ignore class="form-control" id="endDate" wire:model="end_date" wire:change="dateRangeChanged">
                        </div>
                    </div>
                    <h1></h1>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                     <strong>Legends</strong>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Temperature Alarm: <strong
                            style="color: red;">{{ $tempalarm }}°</strong></li>
                    <li class="list-group-item">Temperature Warning: <strong
                            style="color: blue;">{{ $tempwarning }}°</strong></li>
                </ul>
            </div>
        </div>
        <div class="col-xl-8 col-sm-12">
            <div class="card">
                <div class="card-header">
                    Temperature
                </div>
                <div class="card-body">
                    <canvas id="lineChart" width="500" height="600"></canvas>
                </div>
            </div>

        </div>
    </div>
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



    <script>
        document.addEventListener('livewire:load', function() {
            var ctx = document.getElementById('lineChart').getContext('2d');
            var chart;
            var data5 = @json($data);


            Livewire.on('sensorDataUpdated', function(data, tempalarm, tempwarning, tempTime) {


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
                                        yMin: tempalarm, //tempalarm,tempwarning
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
                                    line4: {
                                        type: 'line',
                                        yMin: tempalarm + 10,
                                        yMax: tempalarm + 10,
                                        borderWidth: 0,
                                        borderColor: 'pink'
                                    },
                                }
                            }
                        },
                    },

                });

            });
            updateChart(data5);




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
                                        yMin: <?php echo $tempalarm; ?>,
                                        yMax: <?php echo $tempalarm; ?>,
                                        borderWidth: 2,
                                        borderColor: 'red'
                                    },
                                    line2: {
                                        type: 'line',
                                        yMin: <?php echo $tempwarning; ?>,
                                        yMax: <?php echo $tempwarning; ?>,
                                        borderWidth: 2,
                                        borderColor: 'blue'
                                    },
                                    line4: {
                                        type: 'line',
                                        yMin: <?php echo $tempalarm + 10; ?>,
                                        yMax: <?php echo $tempalarm + 10; ?>,
                                        borderWidth: 0,
                                        borderColor: 'pink'
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
