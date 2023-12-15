<nav class="navbar navbar-expand-lg navbar-light shadow " style="background-color: white">
    <img src="{{ asset('storage/pbi.jpg') }}" class="ms-3" width="130px" height="40px">
    <h3 class="ms-3 mt-2">Compressor Health Monitoring</h3>
    <a class="navbar-brand" href="#">
    </a>

    <div class="navbar-text ms-auto">
        <span id="current-time" class="mx-5"></span>
    </div>


    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
 
   
</nav>
<script>
    // Function to update the current time
    function updateTime() {
        var currentTime = new Date();
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();
        var seconds = currentTime.getSeconds();

        minutes = (minutes < 10 ? "0" : "") + minutes;
        seconds = (seconds < 10 ? "0" : "") + seconds;

        document.getElementById('current-time').innerHTML ="Last Update at " + hours + ":" + minutes + ":" + seconds;

        setTimeout(updateTime, 5000);
    }

    
    updateTime();
</script>