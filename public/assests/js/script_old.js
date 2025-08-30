document.addEventListener("DOMContentLoaded", function () {
    setTimeout(() => {
        document.getElementById("toast-container")?.classList.add("opacity-0");
        setTimeout(() => document.getElementById("toast-container")?.remove(), 500);
    }, 3000);

    document.getElementById("close-toast")?.addEventListener("click", function () {
        document.getElementById("toast-container")?.remove();
    });
});
$(document).ready(function () {
    const days = ['sunday','monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

    $('#add-day').click(function () {
        let day = days.find(d => !$(`#schedule-fields #${d}`).length); // Find the first available day

        if (day) {
            days.splice(days.indexOf(day), 1); // Remove added day from list
            $('#schedule-fields').append(`
                <div class="flex items-center justify-between p-2 rounded-md" id="${day}">
                    <span class="text-gray-700 font-medium w-20">${capitalize(day)}</span>
                    <input type="time" name="available_days[${day}][start_time]" class="p-2 border rounded-md w-32 start-time">
                    <input type="time" name="available_days[${day}][end_time]" class="p-2 border rounded-md w-32 end-time">
                    <button type="button" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 remove-btn" onclick="removeDay('${day}')">X</button>
                </div>
            `);
        } else {
            alert("All available days have already been added!");
        }
    });

    // Detect changes in any time input inside #schedule-fields
    $('#schedule-fields').on('change', 'input[type="time"]', function () {
        let parentDiv = $(this).closest('div'); // Get the parent div (day container)
        let startTime = parentDiv.find('.start-time').val();
        let endTime = parentDiv.find('.end-time').val();

        if (startTime && endTime) {
            validateTime(startTime, endTime, parentDiv);
        }
    });

    // Function to validate time inputs
    function validateTime(startTime, endTime, parentDiv) {
        let start = new Date(`2000-01-01T${startTime}`);
        let end = new Date(`2000-01-01T${endTime}`);
        let diffHours = (end - start) / (1000 * 60 * 60); // Convert milliseconds to hours
        let message = "";
    
        if (startTime === endTime) {
            message = "⏰ Start time and end time cannot be the same. Please select a different time.";
            parentDiv.find('.end-time').val(""); // Reset end time
        } else if (diffHours > 10) {
            message = "⚠️ The time difference should not be more than 10 hours. Please adjust the schedule";
            parentDiv.find('.end-time').val(""); // Reset end time
        } else if (startTime >= endTime) {
            message = "❌  End time must be later than the start time. Please correct it";
            parentDiv.find('.end-time').val(""); // Reset end time
        }
    
        if (message) {
            showToast(message, "error"); // Call toast function
        }
    }
    function showToast(message, type = "error") {
        let toastContainer = $("#toast-container");
        
        if (toastContainer.length === 0) {
            $("body").append('<div id="toast-container" class="fixed top-5 right-5 z-50"></div>');
            toastContainer = $("#toast-container");
        }
    
        let toastColor = type === "error" ? "bg-red-500" : "bg-green-500";
        let icon = type === "error" 
            ? `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M6 18L18 6M6 6l12 12"></path>
               </svg>`
            : `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7"></path>
               </svg>`;
    
        let toast = $(`
            <div class="flex items-center w-80 px-4 py-3 rounded-lg shadow-lg text-white ${toastColor} opacity-100 transition-opacity duration-300 mt-2">
                <div class="mr-3">${icon}</div>
                <span class="flex-1">${message}</span>
                
            </div>
        `);
    
        toastContainer.append(toast);
    
        // Auto-hide toast after 3 seconds
        setTimeout(() => toast.fadeOut(300, () => toast.remove()), 3000);
    
        // Close button click
        toast.find(".close-toast").click(() => toast.remove());
    }
    

    // Function to remove a day
    window.removeDay = function(day) {
        if ($('.remove-btn').length > 1) {
            $('#' + day).remove();
            days.push(day); // Add the removed day back to available list
        } else {
            alert("At least one day must remain!");
        }
    };

    // Capitalize the first letter of each day
    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
});


var options = {
    chart: {
      type: 'line',
      height: 200
    },
    // Two series for two lines
    series: [
      {
        name: 'Red Series',
        data: [10, 20, 15, 30, 25, 40, 35]
      },
      {
        name: 'Blue Series',
        data: [5, 15, 10, 20, 15, 30, 25]
      }
    ],
    // Specify the colors for each series
    colors: ['#FF6F70', '#00B3F0'],
    xaxis: {
      categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
    }
  };

  // Render the chart
  var chart = new ApexCharts(document.querySelector("#chart"), options);
  chart.render();

  $(document).ready(function () {
    const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

    $('#add-day').click(function () {
        let day = days.find(d => !$(`#schedule-fields #${d}`).length); // Find the first day that is not already in schedule-fields
    
        if (day) {
            // days.splice(days.indexOf(day), 1); // Remove the day from the array
            // $('#schedule-fields').append(`
            //     <div class="flex items-center justify-between p-2 rounded-md" id="${day}">
            //         <span class="text-gray-700 font-medium w-20">${capitalize(day)}</span>
            //         <input type="time" name="available_days[${day}][start]" class="p-2 border rounded-md w-24">
            //         <input type="time" name="available_days[${day}][end]" class="p-2 border rounded-md w-24">
            //         <button type="button" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 remove-btn" onclick="removeDay('${day}')">X</button>
            //     </div>
            // `);
        } else {
            alert("All available days are already added!");
        }
    });

    // Remove a specific day row, but at least one must remain
    window.removeDay = function(day) {
        if ($('.remove-btn').length > 1) {
            $('#' + day).remove();
        } else {
            alert("At least one day must remain!");
        }
    };

    // Reset the schedule (keep the Sunday row and reset others)
    function resetSchedule() {
        $('#schedule-fields').html(`
            <div class="flex items-center justify-between  p-2 rounded-md" id="sunday">
                <span class="text-gray-700 font-medium w-20">Sunday</span>
                <input type="time" name="available_days[sunday][start]" class="p-2 border rounded-md w-24">
                <input type="time" name="available_days[sunday][end]" class="p-2 border rounded-md w-24">
                <button type="button" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 remove-btn" onclick="removeDay('sunday')">X</button>
            </div>
        `);
        // Re-add all the days for the next possible addition
        days.splice(0, days.length, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
    }

    window.resetSchedule = resetSchedule;

    // Capitalize the first letter of each day
    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
});
$(document).ready(function () {
    let groupCount = 1;

   
    $("#showCalendarBtn").click(function (event) {
        event.preventDefault(); 
        $("#calendarContainer").toggle(); 
    });

 
    $("#dateInput").change(function (event) {
        event.preventDefault();
        $("#selectedDate").text("Selected Date: " + $(this).val());
    });

    $(".dropdown-button").click(function (event) {
        event.preventDefault(); 
         event.stopPropagation(); 
        var menu = $(this).next(".dropdown-menu");
        $(".dropdown-menu").not(menu).addClass("hidden"); 
        menu.toggleClass("hidden");
    });

    $(document).click(function () {
        $(".dropdown-menu").addClass("hidden"); 
    });

    // Add More Button Click
    $("#add-more-btn").click(function (event) {
        event.preventDefault(); 
        groupCount++;
        if($(".input-group").length < 6){

            const newGroup = `
                 <div class="flex gap-2 my-2 input-group">
                                <input type="time" name="available_time[]" id="" class="p-2 rounded-lg border outline-none w-28">
                               <span class="text-lg py-2"><i class="ri-subtract-fill"></i></span>
                                <input type="time" name="available_time[]" id="" class="p-2 rounded-lg border outline-none w-28">
                                <button class="text-lg text-red-700 bg-red-300 rounded-lg px-3 py-2 remove-btn" ><i class="ri-subtract-fill"></i></button>
                                            
                              </div>`;
            $("#input-container").append(newGroup);
            updateRemoveButtons();
        }
    });

    // Remove Button Click
    $("#input-container").on("click", ".remove-btn", function (event) {
        event.preventDefault(); // Prevent default behavior (form submission, etc.)
        if ($(".input-group").length > 1) {
            $(this).closest(".input-group").remove();
            updateRemoveButtons();
        }
    }); 

    // Disable Remove Button if only one group is present
    function updateRemoveButtons() {
        const groups = $(".input-group");
        $(".remove-btn").prop("disabled", groups.length === 1);
    }

    // Modal Toggle Logic (Using Vanilla JS)
    

  

    // Close modal (on click outside or close button)

});
function viewDetails(id) {
    fetch(`/get-product/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById("modalName").textContent = data.name;
            document.getElementById("modalEmailText").textContent = data.email || "N/A";
            document.getElementById("modalPhoneText").textContent = data.phone || "N/A";
            document.getElementById("modalGender").textContent = data.gender || "N/A";
            document.getElementById("modalMarital").textContent = data.marital_status || "N/A";
            document.getElementById("modalDOB").textContent = data.dob || "N/A";
            document.getElementById("modalBloodGroup").textContent = data.blood_group || "N/A";
            document.getElementById("modalAddress").textContent = data.address || "N/A";
            
            document.getElementById("modalImage").src = data.image ? data.image : "assets/img/avatar.png";
            
            document.getElementById("modal").classList.remove("hidden");
            document.getElementById("modal").classList.add("scale-100", "opacity-100");
        })
        .catch(error => console.error("Error fetching details:", error));
}

document.getElementById("closeModal").addEventListener("click", function () {
    document.getElementById("modal").classList.add("hidden");
    document.getElementById("modal").classList.remove("scale-100", "opacity-100");
});


    document.addEventListener("DOMContentLoaded", () => {
        const modalToggles = document.querySelectorAll(".modalToggle"); 
        const modalOverlay = document.getElementById("modalContent"); 
        const closeModal = document.getElementById("closeModal");
        const overlay =document.getElementById('modalOverlay')
        const showModal = (event) => {
            event.preventDefault(); 
            const button = event.currentTarget; 
            const userId = button.getAttribute("data-id"); 
            console.log("Opening modal for ID:", userId); 
            overlay.classList.remove('hidden');
            modalOverlay.classList.remove("hidden");
            modalOverlay.classList.add("scale-100", "opacity-100");
        };
        const hideModal = () => {
            overlay.classList.add('hidden');
            modalOverlay.classList.add("hidden");
            modalOverlay.classList.remove("scale-100", "opacity-100");
        };
        modalToggles.forEach(button => button.addEventListener("click", showModal));
        closeModal.addEventListener("click", hideModal);
    });
    
    $(document).ready(function () {
        $(".textInput").on("keypress", function (e) {
            let char = String.fromCharCode(e.which);
            if (!char.match(/[a-zA-Z\s]/)) {
                e.preventDefault(); 
            }
        });

        $(".textInput").on("keyup", function () {
            let input = $(this).val().trim();
            input = input.replace(/[^a-zA-Z\s]/g, '');
            let words = input.split(/\s+/).filter(Boolean);
            let message = $(this).next(".message");

            // Check word count
            if (words.length > 3) {
                message.text("⚠️ You can enter only up to 3 words!");
                input = words.slice(0, 3).join(" ");
            } else {
                message.text(`✅ Words entered: ${words.length}/3`);
            }

            $(this).val(input);
        });
    });
   
 
    
    $(document).ready(function() {
        $('#personal_info').show();
        $('#company_info').hide(); 
        $('#contact_info').hide(); 
        $('#form_pdf').hide(); 
        $('#personal_info_btn').click(function(){
            $('#personal_info').show();
            $('#company_info').hide();   
            $('#contact_info').hide();
            $('#form_pdf').hide(); 
        });
        $('#contact_btn').click(function(){
            $('#contact_info').show();  
            $('#personal_info').hide();
            $('#form_pdf').hide(); 
            $('#company_info').hide();  
        });
        $('#company_info_btn').click(function(){
            $('#company_info').show();  
            $('#contact_info').hide();
            $('#personal_info').hide();
            $('#form_pdf').hide(); 
            // $('#personal_info').hide();
        });
        $('#form_pdf_btn').click(function(){
            $('#form_pdf').show(); 
            $('#company_info').hide();   
            $('#contact_info').hide();
            $('#personal_info').hide();
        });
        const buttons = $('.tab-button');
        buttons.first().addClass('bg-red-500 text-white');
        buttons.not(':first').addClass('text-gray-500');

        buttons.on('click', function() {
        buttons.removeClass('bg-red-500 text-white').addClass('text-white');
        $(this).addClass('bg-red-500 text-white').removeClass('text-gray-500');
    });
    });
    
    

    
    
    