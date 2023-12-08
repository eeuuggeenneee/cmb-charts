<div>
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
                        <label for="">Select Building:</label>
                        <select class="form-control">
                            <option value="" selected>MF Building</option>
                            <option value="" disabled>LF Building</option>

                        </select>
                    </div>

                    
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
                        <label for="sensorSelect" class="text-center mt-3">Filter Date:</label>
                        <div class="form-group">
                            <input type="date" wire:ignore class="form-control" id="startDate"
                                wire:model="start_date" wire:change="dateRangeChanged">
                        </div>
                        <h6 class="text-center">TO</h6>
                        <div class="form-group">
                            <input type="date" wire:ignore class="form-control" id="endDate" wire:model="end_date"
                                wire:change="dateRangeChanged">
                        </div>

                        <p class="text-center mt-3">Time: <span id="demo"></span></p>
                        <div class="slidecontainer">
                            <input type="range" min="0" max="24" step="0.1" value="12"
                                class="slider form-control" id="myRange">
                        </div>


                    </div>
                    <h1></h1>
                </div>
            </div>

        </div>
        <div class="col-xl-8 col-sm-12">
            <div class="card">
                <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">

                    <span style="text-align: left;">Temperature</span>

                    <div style="display: flex; align-items: center;">
                        <div class="line" style="height: 2px; background-color: blue; margin: 0 10px; width: 50px;">
                        </div>
                        <span style="margin-right: 10px;">Warning</span>
                        <div class="line" style="height: 2px; background-color: red; margin: 0 10px; width: 50px;">
                        </div>
                        <span>Alarm</span>
                    </div>

                </div>





                <div class="card-body">
                    <canvas id="myChart" width="500" height="600"></canvas>
                </div>
            </div>

        </div>
    </div>
    <script>
        var slider = document.getElementById("myRange");
        var output = document.getElementById("demo");

        // Function to convert decimal value to time format (hh:mm)
        function convertToTime(decimalValue) {
            var hours = Math.floor(decimalValue);
            var minutes = Math.round((decimalValue - hours) * 60);
            minutes = (minutes < 10) ? "0" + minutes : minutes;
            return hours + ":" + minutes;
        }

        output.innerHTML = convertToTime(slider.value);

        slider.oninput = function() {
            output.innerHTML = convertToTime(this.value);
        }
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
                    label: "200D E-DE"
                },
                {
                    value: 18,
                    label: "200D E-NDE"
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
  

        var selectedSensorValue = document.getElementById("sensorSelect").value;

        if(selectedSensorValue == ""){
            selectedSensorValue = 0;
        }
        

        function updateSelectedSensorValue() {
            selectedSensorValue = document.getElementById("sensorSelect").value;
            console.log("Selected Sensor: " + selectedSensorValue);
        }
        sensorSelect.addEventListener("change", updateSelectedSensorValue);

      
        var oldData = @json($olddata);


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




        document.addEventListener('livewire:load', function() {
            var data2 = @json($data);
            const canvas = document.getElementById('myChart');
            const chartData = data2.map(item => ({
                x: item.x,
                y: item.y
            }));
            const data = {
                labels: chartData.map(item => item.x),

                datasets: [{
                    label: 'Sensor',
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 2,
                    data: chartData.map(item => item.y),
                    pointRadius: 1,
                }]
            };
            const config = {
                type: 'line',
                data: data,
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
                                display: false,
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
                                // Adjust the y-axis scale properties if needed
                            },
                        }],
                        legend: {
                            display: false, // This line removes the legend
                        },
                    },
                    plugins: {
                        annotation: {
                            annotations: {
                                line1: {
                                    type: 'line',
                                    yMin: <?php echo $tempalarm + 10; ?>,
                                    yMax: <?php echo $tempalarm + 10; ?>,
                                    borderWidth: 0,
                                    borderColor: 'grey'
                                },
                                line2: {
                                    type: 'line',
                                    yMin: <?php echo $tempwarning; ?>,
                                    yMax: <?php echo $tempwarning; ?>,
                                    borderWidth: 2,
                                    borderColor: 'blue'
                                },
                                line3: {
                                    type: 'line',
                                    yMin: <?php echo $tempalarm; ?>,
                                    yMax: <?php echo $tempalarm; ?>,
                                    borderWidth: 2,
                                    borderColor: 'red'
                                },
                            }
                        },
                    },


                },
            };

            var myChart = new Chart(canvas, config);
            var initialDataFromBackend = @json($olddata);
            Livewire.on('sensorDataUpdated', function(data, tempalarm, tempwarning, tempTime, latestTemp, olddata) {
                console.log("Updated Selected Sensor: " + selectedSensorValue);


                const fromlivewire = {
                    x: tempTime,
                    y: latestTemp,
                };

                initialDataFromBackend = fromlivewire;
                console.log(initialDataFromBackend);
                fetchDataAndAddToChart();
                myChart.destroy();

                const updatedChartData = data.map(item => ({
                    x: item.x,
                    y: item.y
                }));



                const updatedData = {
                    labels: updatedChartData.map(item => item.x),
                    datasets: [{
                        label: 'Sensor',
                        backgroundColor: 'rgb(255, 99, 132)',
                        borderColor: 'rgb(255, 99, 132)',
                        data: updatedChartData.map(item => item.y),
                    }]
                };
                myChart = new Chart(canvas, {
                    type: 'line',
                    data: updatedData,
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
                                        yMin: tempalarm + 10, //here
                                        yMax: tempalarm + 10, //here
                                        borderWidth: 0,
                                        borderColor: 'grey'
                                    },
                                    line2: {
                                        type: 'line',
                                        yMin: tempwarning, //here
                                        yMax: tempwarning, //here
                                        borderWidth: 2,
                                        borderColor: 'blue'
                                    },
                                    line3: {
                                        type: 'line',
                                        yMin: tempalarm, //here
                                        yMax: tempalarm, //here
                                        borderWidth: 2,
                                        borderColor: 'red'
                                    },
                                }
                            }
                        },
                    },

                });
            });

            function addData(chart, newData) {
                chart.data.labels.push(newData.x);
                chart.data.datasets[0].data.push(newData.y);
                chart.update();
            }

            let isFirstLoad = true;


            function arraysEqual(arr1, arr2) {
                return JSON.stringify(arr1) === JSON.stringify(arr2);
            }

            function fetchDataAndAddToChart() {
                console.log("Selected Sensor " + selectedSensorValue);
                fetch('http://127.0.0.1:8000/api/sensor-data/' + selectedSensorValue)
                    .then(response => response.json())
                    .then(data => {
                        const reconstructedData = {
                            x: data[0].x,
                            y: parseFloat(data[0].y),
                        };
                        if (arraysEqual(initialDataFromBackend, reconstructedData)) {
                            console.log("No new data");
                        } else {
                            console.log("New data", reconstructedData);
                            initialDataFromBackend = reconstructedData;
                            addData(myChart, reconstructedData);
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }
            fetchDataAndAddToChart();

            setInterval(fetchDataAndAddToChart, 1000);

        });
    </script>




</div>
