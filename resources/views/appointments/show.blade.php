<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white rounded-2xl shadow-xl mt-8" id="appointment-container">
        <div id="appointment-skeleton">
            <div class="space-y-4 animate-pulse">
                <div class="h-6 bg-gray-200 rounded w-1/3"></div>
                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                <div class="h-4 bg-gray-200 rounded w-full"></div>

                <div class="mt-6 h-5 bg-gray-200 rounded w-1/3"></div>
                <div class="space-y-2">
                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                    <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const container = document.getElementById('appointment-container');
            const urlSegments = window.location.pathname.split('/');
            const appointmentId = urlSegments[urlSegments.length - 1];

            try {
                const [appointmentRes, relatedRes] = await Promise.all([
                    fetch(`/api/appointments/${appointmentId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        credentials: 'same-origin'
                    }),
                    fetch(`/api/appointments/${appointmentId}/related`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        credentials: 'same-origin'
                    }),
                ]);

                if (!appointmentRes.ok || !relatedRes.ok) throw new Error('API error');

                const appointment = await appointmentRes.json();
                const relatedAppointments = await relatedRes.json();

                container.innerHTML = renderAppointment(appointment.data, relatedAppointments.data);
            } catch (err) {
                container.innerHTML =
                    `<div class="text-red-600 text-center mt-4">{{ __('Failed to load appointment details.') }}</div>`;
                console.error(err);
            }
        });

        function renderAppointment(app, related) {
            return `
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold">{{ __('Appointment details') }}</h1>
                </div>

                <div class="border rounded p-4 bg-gray-50">
                    <p class="mb-2"><strong>{{ __('Appointment date and time') }}:</strong> ${app.appointment_datetime}</p>
                    <p class="mb-2"><strong>{{ __('Client name') }}:</strong> ${app.client_name}</p>
                    <p class="mb-2"><strong>{{ __('EGN') }}:</strong> ${app.egn}</p>
                    <p class="mb-2"><strong>{{ __('Description') }}:</strong> ${app.description || '-'}</p>
                    <p class="mb-2"><strong>{{ __('Notification method') }}:</strong> ${app.notification_method.toUpperCase()}</p>
                </div>

                <h2 class="text-xl font-semibold my-4">{{ __('Other upcoming appointments for this client') }}</h2>
                ${related.length === 0
                    ? `<div class="flex items-center text-gray-500 bg-gray-100 p-4 rounded-lg">
                            <span class="text-2xl mr-2">😴</span>
                            <span>{{ __('No other upcoming appointments found') }}</span>
                        </div>`
                    : `<ul class="space-y-3">` +
                      related.map(r => `
                              <li>
                                  <a href="/appointments/${r.id}" class="flex items-center bg-blue-50 hover:bg-blue-100 transition rounded-lg px-4 py-3 shadow-sm">
                                      <span class="text-blue-500 text-lg mr-3">🗓️</span>
                                      <div class="text-sm">
                                          <div class="font-medium text-blue-800">${r.appointment_datetime}</div>
                                          <div class="text-gray-700">${r.client_name}</div>
                                      </div>
                                  </a>
                              </li>`).join('') +
                      `</ul>`}

                <div class="mt-6">
                    <a href="{{ route('appointments.index') }}" class="text-gray-600 hover:underline">
                        &larr; {{ __('Back to appointments list') }}
                    </a>
                </div>
            `;
        }
    </script>
</x-app-layout>
