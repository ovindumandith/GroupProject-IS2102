$(document).ready(function () {
    let globalEventHistories = [];

    // Initialize flatpickr
    flatpickr(".datetimepicker", {
        enableTime: true,
        dateFormat: "Y-m-d H:i:S",
        time_24hr: true
    });

    // Initialize FullCalendar
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        selectable: true,
        selectHelper: true,
        editable: true,
        eventLimit: true,
        
        events: function (start, end, timezone, callback) {
            $.ajax({
                url: '../controller/ScheduleEventController.php',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    globalEventHistories = response.eventHistories;
                    callback(response.data); // For FullCalendar v3, use "callback"
                    const eventHistories = response.eventHistories || []; // Extract only tasks from response
                    fetchHistories(eventHistories)
                },
                error: function (xhr, status, error) {
                    console.error("Failed to fetch events:", error);
                }
            });
        },
        

        select: function (start, end) {
            $('#eventId').val('');
            $('#eventTitle').val('');
            $('#startTime').val(moment(start).format("YYYY-MM-DD HH:mm:ss"));
            $('#endTime').val(moment(end).format("YYYY-MM-DD HH:mm:ss"));
            $('#eventModal').modal('show');
        },

        eventClick: function (event) {
            $('#eventId').val(event.id);
            $('#eventTitle').val(event.title);
            $('#startTime').val(moment(event.start).format("YYYY-MM-DD HH:mm:ss"));
            $('#endTime').val(event.end ? moment(event.end).format("YYYY-MM-DD HH:mm:ss") : moment(event.start).format("YYYY-MM-DD HH:mm:ss"));
            $('#eventModal').modal('show');
        },

        eventDrop: function (event) {
            var start = moment(event.start).format("YYYY-MM-DD HH:mm:ss");
            var end = event.end ? moment(event.end).format("YYYY-MM-DD HH:mm:ss") : start;

            $.ajax({
                url: '../controller/ScheduleEventController.php',
                type: "POST",
                data: {
                    id: event.id,
                    title: event.title,
                    start: start,
                    end: end
                },
                success: function (response) {
                    $('#calendar').fullCalendar('refetchEvents');
                    alert("Event updated successfully");
                },
                error: function (xhr, status, error) {
                    console.error("Error updating event:", error);
                }
            });
        },

        eventResize: function (event) {
            var start = moment(event.start).format("YYYY-MM-DD HH:mm:ss");
            var end = event.end ? moment(event.end).format("YYYY-MM-DD HH:mm:ss") : start;

            $.ajax({
                url: '../controller/ScheduleEventController.php',
                type: "POST",
                data: {
                    id: event.id,
                    title: event.title,
                    start: start,
                    end: end
                },
                success: function (response) {
                    $('#calendar').fullCalendar('refetchEvents');
                    alert("Event duration updated successfully");
                },
                error: function (xhr, status, error) {
                    console.error("Error resizing event:", error);
                }
            });
        }
    });

    // Initialize datetimepicker for fallback compatibility
    $('.datetimepicker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });

    // Handle form submission for adding/editing events
    $('#eventForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#eventId').val();
        var title = $('#eventTitle').val();
        var start = $('#startTime').val();
        var end = $('#endTime').val();

        $.ajax({
            url: '../controller/ScheduleEventController.php',
            type: "POST",
            data: {
                id: id,
                title: title,
                start: start,
                end: end
            },
            success: function (response) {
                $('#calendar').fullCalendar('refetchEvents');
                $('#eventModal').modal('hide');
                alert(id ? 'Event updated successfully' : 'Event added successfully');
            },
            error: function (xhr, status, error) {
                console.error("Error saving event:", error);
            }
        });
    });

    // Handle event deletion
    $('#deleteEvent').on('click', function () {
        var id = $('#eventId').val();
        if (id && confirm("Do you really want to delete this event?")) {
            $.ajax({
                url: '../controller/ScheduleEventController.php',
                type: "DELETE",
                contentType: "application/json",
                data: JSON.stringify({ id: id }),
                success: function (response) {
                    $('#calendar').fullCalendar('removeEvents', id);
                    $('#eventModal').modal('hide');
                    alert("Event deleted successfully");
                },
                error: function (xhr, status, error) {
                    console.error("Error deleting event:", error);
                    alert("Failed to delete the event.");
                }
            });
        }
    });
});
function fetchHistories(taskHistories) { 
    const timeline = document.getElementById("timeline");
  
    if (!timeline) {
        console.error("Error: Element with ID 'timeline' not found.");
        return;
    }
  
    timeline.innerHTML = ""; // Clear existing items before adding new ones
  
    taskHistories.forEach(item => {
      let eventDiv = document.createElement("div");
      eventDiv.classList.add("event");
      let formattedStartDate = new Date(item.start_time).toLocaleDateString("en-US", { month: "2-digit", day: "2-digit" });
      let formattedEndDate = new Date(item.end_time).toLocaleDateString("en-US", { month: "2-digit", day: "2-digit" });
      eventDiv.innerHTML = `
          <span class="date">${formattedStartDate}</span>
          <span class="date2">${formattedEndDate}</span>
          <div class="circle"></div>
          <div class="details">
              <strong>${item.title}</strong>
              <small>${item.description || "No additional details"}</small>
          </div>
      `;
  
      timeline.appendChild(eventDiv);
  });
}