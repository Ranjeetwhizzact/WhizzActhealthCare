<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>


    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        #zmmtg-root {
            display: block !important;
            position: fixed !important;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 9999;
            /* Ensure it's on top */
            background-color: white;
            /* Prevent invisible background */
        }

        /* #zmmtg-root {
            display: block !important;
            position: relative !important;
            width: 100vw;
            height: 100vh;
        } */

        #meetingSDKElement {
            width: 100vw;
            height: 100vh;
        }
    </style>
</head>

<body>

    <div id="meetingSDKElement"></div>

    <!-- ✅ Zoom Web Meeting SDK CSS -->
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/3.11.2/css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/3.11.2/css/react-select.css" />

    <!-- ✅ Zoom Web Meeting SDK JS -->
    <script src="https://source.zoom.us/3.11.2/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/3.11.2/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/3.11.2/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/3.11.2/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/3.11.2/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/3.11.2/zoom-meeting-3.11.2.min.js"></script>
    <style>
        .zoom-workplace-logo {
            display: none;
        }
        .meeting-header {display: none!important;}
    </style>
    @if (request()->has('doctor_access'))
        <style>
            .footer__leave-btn-container {
                display: none;
            }

            #meetingSDKElement,
            #zmmtg-root {
                max-width: 65% !important;
                max-height: 85vh !important;
                margin-top: 100px;
            }

            .single-main-container__main-view {
                height: 400px !important;
            }

            video-player {
                height: 600px !important;
            }
        </style>
    @endif
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("✅ Checking Zoom SDK...");

            if (typeof ZoomMtg === "undefined") {
                console.error("❌ Error: ZoomMtg is not defined. Check if the Zoom SDK script is loading properly.");
                return;
            }

            ZoomMtg.setZoomJSLib('https://source.zoom.us/3.11.2/lib', '/av');
            // ZoomMtg.preLoadWasm();
            // ZoomMtg.prepareJssdk();
            ZoomMtg.preLoadWasm();
            ZoomMtg.prepareWebSDK();


            ZoomMtg.init({
                leaveUrl: "{{ url('/') }}",
                isSupportAV: true,
                success: function() {
                    console.log("✅ Zoom Initialized Successfully");

                    // console.log("{{ $meetingId }}");
                    console.log("{{ $userName }}");
                    console.log("{{ $signature }}");
                    // console.log("{{ env('ZOOM_CLIENT_ID') }}");
                    // console.log("{{ $passCode }}");


                    ZoomMtg.join({
                        meetingNumber: "{{ $meetingId }}",
                        userName: "{{ $userName }}",
                        signature: "{{ $signature }}",
                        sdkKey: "{{ env('ZOOM_MEETING_CLIENT_ID') }}",
                        passWord: "{{ $passCode }}",
                        // zak: zakToken,

                        success: function() {
                            console.log("✅ Successfully joined the Zoom meeting!");
                        },
                        error: function(err) {
                            console.error("❌ Zoom Join Error:", err);
                        }
                    });

                },
                error: function(err) {
                    console.error("❌ Zoom Init Error:", err);
                }
            });

        });
    </script>
    @if (request()->has('doctor_access'))
        <form action="{{ route('storereport') }}" id="reportdata" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
        <form id="reportdata" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
            <div class=" w-[97.3%] mt-[7px] h-[90px] fixed top-0 left-0 z-50 rounded-md flex items-center">
                <img src="{{ url('assests/img/honestlogo.png') }}" alt="Dashboard Logo"
                    class="h-12 hidden sm:block dashboardlogo  mx-3">
                <button id="endMeetingBtn" class="  px-3 py-1 rounded-full text-red-700 border-2" type="submit">End
                    Meeting</button>
            </div>
            <div class=" w-[30%] mt-[100px] h-full fixed top-0 right-10 z-50 rounded-md">
                <div class="bg-white p-6 rounded-lg  w-full h-full">

                    @csrf
                    <h5>Report Form</h5>
                    <input type="hidden" name="meeting_id" value="{{ $meetingId }}">
                    <input type="hidden" name="isDoctor" value="{{ request()->has('doctor_access')}}">
                    <input type="hidden" id="name" name="client_id" value="{{ $patientId }}"
                        class="w-full p-2 mt-1 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"
                        placeholder="Enter your name" required>

                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" name="name"
                        class="w-full p-2 mt-1 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"
                        placeholder="Enter your name" required>

                    <label for="message" class="block mt-4 text-sm font-medium text-gray-700">Message</label>
                    <textarea id="message" name="message" rows="10"
                        class="w-full p-2 mt-1 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"
                        placeholder="Enter your message" required></textarea>

                    {{-- <button type="submit" class="w-full mt-4 p-2 bg-black text-white rounded-md hover:bg-blue-600">Submit</button> --}}
                </div>
        </form>
        </div>
    @endif
</body>
<script></script>

</html>
