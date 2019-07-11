@if (config('logNotify.enabled')) {
<script>
    var socket = new WebSocket("{{config('logNotify.socket_url')}}");

    socket.onopen = function () {
        console.log("logNotify Socket Server Connected");
    };

    socket.onclose = function (event) {
        console.log('logNotify - Key: ' + event.code + ' cause: ' + event.reason);
    };

    socket.onmessage = function (event) {
        var data = JSON.parse(event.data);
        var message = data.message;

        if (data.context) {
            var context = data.context;
            message += '<br>Context: ' + data.context;
        }

        message += '<br>Time: ' + data.time;

        sendNotification(message, data.level);
    };

    socket.onerror = function (error) {
        console.log("logNotify - Error " + error.message);
    };

    function sendNotification(message, heading) {
        var alertContainer = document.querySelector('#lognotify_container');
        var alertBox = document.createElement('div');
        var typeClass = heading;

        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.setAttribute('id', 'lognotify_container');
        }

        document.body.appendChild(alertContainer);

        alertBox.className += 'alert ' + typeClass;
        alertBox.innerHTML = '<h4>' + heading.toUpperCase() + ' - LogNotify</h4>' + message;

        alertContainer.appendChild(alertBox);

        alertBox.addEventListener('click', function (ev) {
            alertContainer.removeChild(alertBox);
        });
    }
</script>

<style>
    #lognotify_container {
        width: 400px;
        height: auto;
        margin: 0 auto;
        background-color: transparent;
        position: fixed;
        bottom: 10px;
        right: 10px;
        z-index: 9999999999999999999999;
        display: flex;
        flex-flow: column nowrap;
        justify-content: center;
        align-items: right;
    }

    .alert {
        padding: 10px;
        background-color: #2196F3;
        color: white;
        opacity: 1;
        transition: opacity 0.6s;
        cursor: pointer;
    }

    .alert.info, .alert.processed {
        background-color: #4CAF50;
    }

    .alert.notice, .alert.warning {
        background-color: #ff9800;
    }

    .alert.error, .alert.critical, .alert.emergency {
        background-color: #f44336;
    }

    h4 {
        background: white;
        color: black;
        padding: 5px;
    }
</style>
@endif
