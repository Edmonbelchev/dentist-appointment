<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white rounded-2xl shadow-xl mt-8">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6 border-b pb-2">
            {{ __('Edit Appointment') }}
        </h1>

        <div id="error-messages" class="hidden bg-red-100 text-red-700 p-4 mb-6 rounded-lg"></div>
        <div id="success-message" class="hidden bg-green-100 text-green-700 p-4 mb-6 rounded-lg"></div>

        @can('update', $appointment)
            <form id="edit-appointment-form" class="space-y-6" novalidate>
                @csrf
                @method('PUT')
                <input hidden id="appointment-id" value="{{ $appointment->id }}">
                <!-- Appointment Date/Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Appointment date and time') }}
                    </label>
                    <input type="datetime-local" name="appointment_datetime"
                        value="{{ old('appointment_datetime', $appointment->appointment_datetime->format('Y-m-d\TH:i')) }}"
                        required
                        class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Client Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Client name') }}
                    </label>
                    <input type="text" name="client_name" value="{{ old('client_name', $appointment->client_name) }}"
                        required
                        class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- EGN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('EGN') }}
                    </label>
                    <input type="text" name="egn" maxlength="10" value="{{ old('egn', $appointment->egn) }}" required
                        class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Description') }}
                    </label>
                    <textarea name="description" rows="4"
                        class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $appointment->description) }}</textarea>
                </div>

                <!-- Notification Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Notification method') }}
                    </label>
                    <select name="notification_method" required
                        class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="" default>{{ __('Select') }}...</option>
                        <option value="sms"
                            {{ old('notification_method', $appointment->notification_method) == 'sms' ? 'selected' : '' }}>
                            SMS</option>
                        <option value="email"
                            {{ old('notification_method', $appointment->notification_method) == 'email' ? 'selected' : '' }}>
                            Email</option>
                        <option value="push" disabled>Push Notification (Soon)</option>
                    </select>
                </div>

                <div id="email-field" class="hidden">
                    <label class="block text-sm font-medium mb-1">
                        {{ __('Email address') }}
                    </label>
                    <input type="email" name="email" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                </div>

                <div id="phone-field" class="hidden">
                    <label class="block text-sm font-medium mb-1">
                        {{ __('Phone number') }}
                    </label>
                    <input type="tel" name="phone" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-xl transition">
                        {{ __('Save changes') }}
                    </button>
                </div>
            </form>
        @endcan

        @can('delete', $appointment)
            <form action="{{ route('appointments.destroy', $appointment) }}" method="POST"
                onsubmit="return confirm('{{ __('Are you sure you want to delete this appointment?') }}');"
                class="mt-4 text-right">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-red-600 hover:underline hover:text-red-800 transition">
                    🗑 {{ __('Delete appointment') }}
                </button>
            </form>
        @endcan


        <div class="mt-6 text-sm">
            <a href="{{ route('appointments.show', $appointment) }}" class="text-blue-600 hover:underline">
                &larr; {{ __('Back to appointment details') }}
            </a>
        </div>
    </div>

    <script>
        const notificationSelect = document.querySelector('select[name="notification_method"]');
        const emailField = document.getElementById('email-field');
        const phoneField = document.getElementById('phone-field');

        notificationSelect.addEventListener('change', function() {
            const method = this.value;
            emailField.classList.add('hidden');
            phoneField.classList.add('hidden');

            if (method === 'email') {
                emailField.classList.remove('hidden');
            } else if (method === 'sms') {
                phoneField.classList.remove('hidden');
            }
        });

        if (notificationSelect.value !== '' && notificationSelect.value === 'email') {
            emailField.classList.remove('hidden');
        } else if (notificationSelect.value === 'sms') {
            phoneField.classList.remove('hidden');
        }

        document.getElementById('edit-appointment-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const errorBox = document.getElementById('error-messages');
            const successBox = document.getElementById('success-message');
            errorBox.classList.add('hidden');
            errorBox.innerHTML = '';
            successBox.classList.add('hidden');
            successBox.innerHTML = '';

            const form = e.target;
            const formData = new FormData(form);

            // Format data for JSON
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            try {
                const appointmentId = document.getElementById('appointment-id').value;
                const response = await fetch(`/api/appointments/${appointmentId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data),
                    credentials: 'same-origin',
                });

                const result = await response.json();

                if (!response.ok) {
                    if (result.errors) {
                        // Show validation errors
                        let errorsHtml = '<ul class="list-disc list-inside">';
                        for (const key in result.errors) {
                            result.errors[key].forEach(msg => {
                                errorsHtml += `<li>${msg}</li>`;
                            });
                        }
                        errorsHtml += '</ul>';
                        errorBox.innerHTML = errorsHtml;
                        errorBox.classList.remove('hidden');
                    } else if (result.message) {
                        errorBox.textContent = result.message;
                        errorBox.classList.remove('hidden');
                    } else {
                        errorBox.textContent = '{{ __('An error occurred') }}';
                        errorBox.classList.remove('hidden');
                    }
                } else {
                    successBox.textContent = '{{ __('Appointment updated successfully.') }}';
                    successBox.classList.remove('hidden');

                    // Wait for 2 seconds and redirect to show page
                    setTimeout(() => {
                        window.location.href = '{{ route('appointments.show', $appointment) }}';
                    }, 2000);
                }
            } catch (error) {
                errorBox.textContent = '{{ __('Failed to update appointment. Please try again.') }}';
                errorBox.classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>
