<h2>New Appointment Scheduled</h2>
<p><strong>Date & Time:</strong> {{ $appointment->appointment_datetime }}</p>
<p><strong>Client:</strong> {{ $appointment->client_name }}</p>
<p><strong>EGN:</strong> {{ $appointment->egn }}</p>
<p><strong>Description:</strong> {{ $appointment->description ?? '-' }}</p>
