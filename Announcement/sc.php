<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="sched.css">
    <title>Scheduling Calendar</title>
</head>
<body>
<?php include 'scdata.php'; ?>

<div class="sub-container">
    <h2>Subject Schedule</h2>
    <button id="showFormButton">Add New Entry</button>
    <form action="#" method="post">
        <label for="scheduleSelect">View Schedule of:</label>
        <select id="scheduleSelect" name="scheduleSelect">
            <?php foreach ($entries as $entry) { ?>
                <option value="<?php echo $entry['title']; ?>"><?php echo $entry['title']; ?></option>
            <?php } ?>
        </select>
        <button type="submit" name="viewScheduleBtn">View Schedule</button>
    </form>
</div>

<!-- Entry form modal -->
<div id="entryModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form id="entryForm" method="post" action="sc.php">
            <div class="left-column">
                <!-- Left column -->
                <label for="section">Section:</label>
                <input type="text" id="section" name="section" required>

                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>

                <label for="scheduleType">Schedule Type:</label>
                <input type="text" id="scheduleType" name="scheduleType" required>

                <label for="description">Description:</label>
                <input type="text" id="description" name="description" required>

                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>
            </div>
            <div class="right-column">
                <!-- Right column -->
                <label for="selectedDays">Days of the Week:</label>
                <select id="selectedDays" name="selectedDays[]" multiple required>
                    <option value="0">Sunday</option>
                    <option value="1">Monday</option>
                    <option value="2">Tuesday</option>
                    <option value="3">Wednesday</option>
                    <option value="4">Thursday</option>
                    <option value="5">Friday</option>
                    <option value="6">Saturday</option>
                </select>

                <label for="startTime">Start Time:</label>
                <input type="time" id="startTime" name="startTime" required>

                <label for="endTime">End Time:</label>
                <input type="time" id="endTime" name="endTime" required>

                <!-- Month Selection -->
                <label for="startMonth">Start Month:</label>
                <input type="month" id="startMonth" name="startMonth" required>

                <label for="endMonth">End Month:</label>
                <input type="month" id="endMonth" name="endMonth" required>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
</div>

<div id="calendarContainer">
    <h2 id="monthYear"></h2> <!-- Display month and year here -->
    <div class="calendar-nav">
        <button id="prevMonthBtn">&lt; Prev Month</button>
        <button id="nextMonthBtn">Next Month &gt;</button>
    </div>
    <div id="calendar"></div>
</div>

<!-- Form to display selected date -->
<div id="selectedDateForm" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Selected Date</h2>
        <form id="dateForm">
            <label for="selectedDate">Selected Date:</label>
            <input type="text" id="selectedDate" name="selectedDate" readonly>
        </form>
    </div>
</div>

<script>
    // Function to get month name from month number
    function getMonthName(month) {
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        return monthNames[month];
    }

    // Function to generate calendar
    function generateCalendar(year, month, events) {
        const calendarContainer = document.getElementById('calendar');
        const daysInMonth = new Date(year, month + 1, 0).getDate(); // Get number of days in the month
        const firstDayOfWeek = new Date(year, month, 1).getDay(); // Get the day of the week for the first day of the month

        let calendarHTML = '<table><thead><tr>';
        const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        daysOfWeek.forEach(day => {
            calendarHTML += `<th>${day}</th>`;
        });
        calendarHTML += '</tr></thead><tbody><tr>';

        let dayCount = 1;
        for (let i = 0; i < 6; i++) { // Display up to 6 weeks
            for (let j = 0; j < 7; j++) { // Display 7 days per week
                if ((i === 0 && j < firstDayOfWeek) || dayCount > daysInMonth) {
                    calendarHTML += '<td></td>';
                } else {
                    const date = new Date(year, month, dayCount);
                    const formattedDate = date.toISOString().split('T')[0];
                    const event = events.find(evt => {
                        const startMonth = new Date(evt.startMonth);
                        const endMonth = new Date(evt.endMonth);
                        const startDate = new Date(year, month, dayCount);
                        return startMonth <= startDate && endMonth >= startDate && evt.selectedDays.includes(date.getDay().toString());
                    });
                    calendarHTML += `<td class="highlight" data-day="${dayCount}">${dayCount}`;
                    if (event) {
                        calendarHTML += `<br>${event.title}<br>Start: ${event.startTime} - End: ${event.endTime}`;
                    }
                    calendarHTML += '</td>';
                    dayCount++;
                }
            }
            if (dayCount > daysInMonth) break; // Stop if all days are displayed
            calendarHTML += '</tr><tr>';
        }

        calendarHTML += '</tr></tbody></table>';
        calendarContainer.innerHTML = calendarHTML;

        // Update month and year displayed above the calendar
        document.getElementById('monthYear').textContent = getMonthName(month) + ' ' + year;
    }

    let currentYear = new Date().getFullYear(); // Initial year
    let currentMonth = new Date().getMonth(); // Initial month (January is 0)
    let events = []; // Array to store events

    // Generate initial calendar
    generateCalendar(currentYear, currentMonth, events);

    // Event listener for previous month button
    document.getElementById('prevMonthBtn').addEventListener('click', function() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11; // December
            currentYear--;
        }
        generateCalendar(currentYear, currentMonth, events);
    });

    // Event listener for next month button
    document.getElementById('nextMonthBtn').addEventListener('click', function() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0; // January
            currentYear++;
        }
        generateCalendar(currentYear, currentMonth, events);
    });

    // Event listener for showing the form modal
    document.getElementById('showFormButton').addEventListener('click', function() {
        document.getElementById('entryModal').style.display = 'block';
    });

    // Event listener for hiding the form modal when close button is clicked
    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('entryModal').style.display = 'none';
    });

    // Event listener for clicking on a date cell
    const calendarContainer = document.getElementById('calendar');
    calendarContainer.addEventListener('click', function(event) {
        const target = event.target;
        if (target.classList.contains('highlight')) {
            const day = target.dataset.day;
            const date = new Date(currentYear, currentMonth, day);
            document.getElementById('selectedDate').value = date.toDateString();
            document.getElementById('selectedDateForm').style.display = 'block';
        }
    });

    // Event listener for hiding the selected date form modal when close button is clicked
    document.querySelector('#selectedDateForm .close').addEventListener('click', function() {
        document.getElementById('selectedDateForm').style.display = 'none';
    });

    // Submit event listener for entry form
    document.getElementById('entryForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form submission
        const formData = new FormData(this); // Get form data
        const selectedDays = formData.getAll('selectedDays[]'); // Get selected days as an array
        const eventDetails = {
            section: formData.get('section'),
            title: formData.get('title'),
            scheduleType: formData.get('scheduleType'),
            description: formData.get('description'),
            location: formData.get('location'),
            startMonth: formData.get('startMonth'),
            endMonth: formData.get('endMonth'),
            startTime: formData.get('startTime'),
            endTime: formData.get('endTime'),
            selectedDays: selectedDays
        };
        events.push(eventDetails); // Push event details to the events array
        generateCalendar(currentYear, currentMonth, events); // Regenerate the calendar with updated events
        document.getElementById('entryModal').style.display = 'none'; // Hide the entry modal
    });

</script>
</body>
</html>
