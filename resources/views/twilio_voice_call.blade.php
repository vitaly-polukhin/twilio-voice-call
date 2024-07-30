<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf_token" content="{{ csrf_token() }}">

        <title>Twilio voice call example</title>
        <style>
            .p-6{padding:1.5rem}
            .d-none{display: none;}
            .d-block{display: block;}
            .red-text{color:red;}
        </style>
    </head>
    <body class="p-6">
        <div id="status-block">Start Device Initialization...</div>
        <div id="init-block" class="d-none">
            Type phone number with country code here: <input type="text" id="phone-number"/> <button id="start-call-btn">Start Call</button>
        </div>
        <div id="call-started-block" class="d-none">
            Call started... <button id="finish-call-btn">Finish Call</button>
        </div>
        <div id="error-block" class="d-none red-text"></div>
        <script src="https://media.twiliocdn.com/sdk/js/client/releases/1.8.1/twilio.min.js"></script>
        <script>
            const twilioToken='{{$token}}';
            let connection=null;

            function showBlock(querySelector){
                const element=document.querySelector(querySelector);
                if(element){
                    element.classList.remove('d-none');
                    element.classList.add('d-block');
                }
            }

            function hideBlock(querySelector){
                const element=document.querySelector(querySelector);
                if(element){
                    element.classList.remove('d-block');
                    element.classList.add('d-none');
                }
            }

            const device = new Twilio.Device(twilioToken, {
                // Set Opus as our preferred codec. Opus generally performs better, requiring less bandwidth and
                // providing better audio quality in restrained network conditions. Opus will be default in 2.0.
                codecPreferences: ["opus", "pcmu"],
                // Use fake DTMF tones client-side. Real tones are still sent to the other end of the call,
                // but the client-side DTMF tones are fake. This prevents the local mic capturing the DTMF tone
                // a second time and sending the tone twice. This will be default in 2.0.
                fakeLocalDTMF: true,
                // Use `enableRingingState` to enable the device to emit the `ringing`
                // state. The TwiML backend also needs to have the attribute
                // `answerOnBridge` also set to true in the `Dial` verb. This option
                // changes the behavior of the SDK to consider a call `ringing` starting
                // from the connection to the TwiML backend to when the recipient of
                // the `Dial` verb answers.
                enableRingingState: true,
                debug: true,
            });

            function updateStatus(statusText){
                document.getElementById('status-block').innerText=statusText;
            }

            device.on("ready", function (device) {
                updateStatus("Twilio.Device Ready!");
                showBlock('#init-block');
            });

            document.getElementById('start-call-btn').addEventListener('click', () => {
                hideBlock('#init-block');
                hideBlock('#error-block');

                const phoneNumber=document.getElementById('phone-number').value;
                updateStatus("Calling to "+phoneNumber);
                var params = {
                    to: phoneNumber,
                    from:'JohnDoe',
                };
                device.connect(params);
                // console.log(device);
                // console.log('Button clicked!');
            });

            device.on("connect", function (conn) {
                connection=conn;
                showBlock('#call-started-block');
            });

            device.on("error", function (error) {
                const querySelector='#error-block';
                document.querySelector(querySelector).innerText=error.message;
                showBlock(querySelector);
                showBlock('#init-block');
            });

            document.getElementById('finish-call-btn').addEventListener('click', () => {
                device.disconnectAll();
                updateStatus('Call finished');
                showBlock('#init-block');
                hideBlock('#error-block');
            });
        </script>
    </body>
</html>
