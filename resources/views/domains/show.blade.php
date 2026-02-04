<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $domain->url }}
            </h2>

            <!-- Кнопка видалення (щоб було зручно) -->
            <form action="{{ route('domains.destroy', $domain) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                    Delete Domain
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Блок налаштувань -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <span class="text-gray-500 block text-xs uppercase">Method</span>
                        <span class="font-bold">{{ $domain->method }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs uppercase">Interval</span>
                        <span class="font-bold">{{ $domain->check_interval }} seconds</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs uppercase">Timeout</span>
                        <span class="font-bold">{{ $domain->timeout }} seconds</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs uppercase">Last Checked</span>
                        <span class="font-bold">
                            {{ $domain->last_checked_at ? $domain->last_checked_at->diffForHumans() : 'Never' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Таблиця логів -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Check History</h3>

                    @if($logs->isEmpty())
                        <p class="text-gray-500">No checks yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm whitespace-nowrap">
                                <thead class="uppercase tracking-wider border-b-2 border-gray-200 bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Response Time</th>
                                    <th scope="col" class="px-6 py-3">Date</th>
                                    <th scope="col" class="px-6 py-3">Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($logs as $log)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            @if($log->status_code >= 200 && $log->status_code < 300)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ $log->status_code }} OK
                                                    </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        {{ $log->status_code ?? 'ERR' }}
                                                    </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
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
