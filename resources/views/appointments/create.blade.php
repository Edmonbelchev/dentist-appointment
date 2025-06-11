<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white rounded-2xl shadow-xl mt-8">
        <h2 class="text-2xl font-semibold mb-6">
            {{ __('Add new appointment') }}
        </h2>

        <div id="form-alert" class="mb-4 hidden"></div>

        <form id="appointment-form" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">
                    {{ __('Appointment date and time') }}
                </label>
                <input type="datetime-local" name="appointment_datetime" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    {{ __('Client name') }}
                </label>
                <input type="text" name="client_name" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    {{ __('EGN') }}
                </label>
                <input type="text" name="egn" required maxlength="10"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    {{ __('Description') }} ({{ __('optional') }})
                </label>
                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    {{ __('Notification method') }}
                </label>
                <select name="notification_method" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option value="" default>{{ __('Select') }}...</option>
                    <option value="sms">SMS</option>
                    <option value="email">Email</option>
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

            <div class="pt-4">
                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                    {{ __('Save appointment') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full text-center">
            <h3 class="text-xl font-semibold mb-4 text-green-700">{{ __('Appointment saved successfully!') }}</h3>
            <p class="mb-4 text-gray-700">
                {{ __('The client will be notified via') }} <span id="modal-method"
                    class="font-bold uppercase"></span>.
            </p>
            <button id="continue-btn"
                class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded transition">
                {{ __('Continue') }}
            </button>
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

        if(notificationSelect.value !== '' && notificationSelect.value === 'email') {
            emailField.classList.remove('hidden');
        } else if(notificationSelect.value === 'sms') {
            phoneField.classList.remove('hidden');
        }

        document.getElementById('appointment-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const alertBox = document.getElementById('form-alert');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            alertBox.classList.add('hidden');
            alertBox.innerHTML = '';

            try {
                const res = await fetch('/api/appointments', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(data)
                });

                const result = await res.json();

                if (!res.ok) {
                    if (res.status === 422 && result.errors) {
                        const errorHtml = Object.values(result.errors)
                            .map(errors => `<li>${errors.join('<br>')}</li>`)
                            .join('');
                        alertBox.innerHTML = `<ul class="list-disc pl-5">${errorHtml}</ul>`;
                        alertBox.className = 'mb-4 p-4 bg-red-100 text-red-700 rounded';
                    } else {
                        alertBox.innerHTML = '{{ __('An unexpected error occurred.') }}';
                        alertBox.className = 'mb-4 p-4 bg-red-100 text-red-700 rounded';
                    }
                    alertBox.classList.remove('hidden');
                    return;
                }

                // SUCCESS: show modal
                const modal = document.getElementById('success-modal');
                const methodSpan = document.getElementById('modal-method');
                methodSpan.textContent = data.notification_method;

                modal.classList.remove('hidden');

                // Redirect to appointment details on click
                document.getElementById('continue-btn').addEventListener('click', () => {
                    window.location.href = `/appointments/${result.data.id}`;
                });

                form.reset();

            } catch (err) {
                console.error(err);
                alertBox.innerHTML = '{{ __('Something went wrong. Please try again later.') }}';
                alertBox.className = 'mb-4 p-4 bg-red-100 text-red-700 rounded';
                alertBox.classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>
