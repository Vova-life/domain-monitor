<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $domain->url }}
            </h2>

            <div class="flex items-center space-x-2">
                <!-- Кнопка ручної перевірки -->
                <form action="{{ route('domains.check', $domain) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm transition flex items-center shadow-sm">
                        ⚡ Run Check Now
                    </button>
                </form>

                <!-- Кнопка видалення -->
                <form action="{{ route('domains.destroy', $domain) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm shadow-sm">
                        Delete Domain
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Повідомлення про успішну дію -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm rounded">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Блок налаштувань -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-100">
                <div class="p-6 text-gray-900 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <span class="text-gray-500 block text-xs uppercase tracking-wider">Method</span>
                        <span class="font-bold text-lg text-indigo-700 uppercase">{{ $domain->method }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs uppercase tracking-wider">Interval</span>
                        <span class="font-bold text-lg">{{ $domain->check_interval }} seconds</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs uppercase tracking-wider">Timeout</span>
                        <span class="font-bold text-lg">{{ $domain->timeout }} seconds</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs uppercase tracking-wider">Last Checked</span>
                        <span class="font-bold text-lg text-gray-800">
                            {{ $domain->last_checked_at ? $domain->last_checked_at->diffForHumans() : 'Never' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Таблиця логів -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2 text-gray-700">Check History</h3>

                    @if($logs->isEmpty())
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <p class="mt-2 text-gray-500 italic">No checks yet. Press "Run Check Now" to start.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm whitespace-nowrap">
                                <thead class="uppercase tracking-wider border-b-2 border-gray-100 bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-bold text-gray-600">Status</th>
                                    <th scope="col" class="px-6 py-3 font-bold text-gray-600">Response Time</th>
                                    <th scope="col" class="px-6 py-3 font-bold text-gray-600">Date</th>
                                    <th scope="col" class="px-6 py-3 font-bold text-gray-600">Details</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                @foreach($logs as $log)
                                    <tr class="border-b hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4">
                                            @if($log->status_code >= 200 && $log->status_code < 300)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                    {{ $log->status_code }} OK
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                    {{ $log->status_code ?? 'ERR' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-medium">
                                            {{ $log->response_time }}s
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">
                                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 max-w-xs truncate">
                                            {{ $log->error_message ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
