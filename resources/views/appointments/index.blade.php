<x-app-layout>
    <div class="max-w-[1200px] mx-auto p-6 bg-white rounded shadow mt-6">
        <h2 class="text-2xl font-semibold mb-4">
            {{ __('Appointments list') }}
        </h2>

        <div id="alert" class="hidden mb-4 p-4 bg-green-100 text-green-700 rounded"></div>

        <!-- Filter Form -->
        <form id="filter-form" class="mb-6 flex space-x-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('From date') }}</label>
                <input type="date" name="date_from" class="border rounded px-2 py-1">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('To date') }}</label>
                <input type="date" name="date_to" class="border rounded px-2 py-1">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('EGN') }}</label>
                <input type="text" name="egn" placeholder="{{ __('Enter EGN') }}"
                       class="border rounded px-2 py-1">
            </div>

            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    {{ __('Filter') }}
                </button>
            </div>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300" id="appointments-table">
                <thead>
                <tr>
                    <th class="border px-3 py-2">{{ __('Date and time') }}</th>
                    <th class="border px-3 py-2">{{ __('Client name') }}</th>
                    <th class="border px-3 py-2">{{ __('EGN') }}</th>
                    <th class="border px-3 py-2">{{ __('Notification method') }}</th>
                    <th class="border px-3 py-2">{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody id="appointments-body">
                    <tr><td colspan="5" class="text-center py-4 text-gray-500">{{ __('Loading...') }}</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 text-center" id="pagination-controls"></div>
    </div>

    <script>
        const tableBody = document.getElementById('appointments-body');
        const pagination = document.getElementById('pagination-controls');
        const alertBox = document.getElementById('alert');
        const form = document.getElementById('filter-form');

        let currentPage = 1;
        let currentFilters = {};

        async function loadAppointments(page = 1, filters = {}) {
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-gray-500">{{ __('Loading...') }}</td></tr>`;
            const params = new URLSearchParams({ ...filters, page });

            const res = await fetch(`/api/appointments?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            const data = await res.json();

            if (!res.ok) {
                tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-red-500">{{ __('Failed to load appointments') }}</td></tr>`;
                return;
            }

            if (data.data.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-gray-500">{{ __('No appointments found') }}</td></tr>`;
            } else {
                tableBody.innerHTML = '';
                data.data.forEach(appointment => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="border px-3 py-2">${appointment.appointment_datetime}</td>
                        <td class="border px-3 py-2">${appointment.client_name}</td>
                        <td class="border px-3 py-2">${appointment.egn}</td>
                        <td class="border px-3 py-2">${appointment.notification_method.toUpperCase()}</td>
                        <td class="border px-3 py-2">
                            <a href="/appointments/${appointment.id}" class="text-blue-600 hover:underline mr-2">{{ __('View') }}</a>
                            <a href="/appointments/${appointment.id}/edit" class="text-yellow-600 hover:underline mr-2">{{ __('Edit') }}</a>
                            <form method="POST" action="/appointments/${appointment.id}" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this appointment?') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    `;
                    tableBody.appendChild(tr);
                });
            }

            // Add pagination
            pagination.innerHTML = '';
            const totalPages = data.meta.last_page;
            const current = data.meta.current_page;

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `mx-1 px-3 py-1 rounded ${i === current ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300'}`;
                btn.addEventListener('click', () => {
                    currentPage = i;
                    loadAppointments(i, currentFilters);
                });
                pagination.appendChild(btn);
            }
        }

        /* Create the form data */
        let formData = new FormData(form);
        currentFilters = Object.fromEntries(formData.entries());

        // Filter submit
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            formData = new FormData(form);
            currentFilters = Object.fromEntries(formData.entries());
            currentPage = 1;
            loadAppointments(currentPage, currentFilters);
        });

        // Initial load
        loadAppointments(currentPage, currentFilters);
    </script>
</x-app-layout>
