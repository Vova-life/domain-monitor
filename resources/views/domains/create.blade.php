<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Domain') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('domains.store') }}">
                        @csrf

                        <!-- URL Address -->
                        <div class="mb-4">
                            <label for="url" class="block font-medium text-sm text-gray-700">URL Address</label>
                            <input id="url" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                                   type="url" name="url" value="{{ old('url') }}" required placeholder="https://google.com" />
                            @error('url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <!-- Check Interval -->
                            <div>
                                <label for="check_interval" class="block font-medium text-sm text-gray-700">Interval (seconds)</label>
                                <input id="check_interval" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                                       type="number" name="check_interval" value="60" required min="10" />
                            </div>

                            <!-- Timeout -->
                            <div>
                                <label for="timeout" class="block font-medium text-sm text-gray-700">Timeout (seconds)</label>
                                <input id="timeout" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                                       type="number" name="timeout" value="5" required min="1" />
                            </div>

                            <!-- Method -->
                            <div>
                                <label for="method" class="block font-medium text-sm text-gray-700">Method</label>
                                <select id="method" name="method" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="GET">GET</option>
                                    <option value="HEAD">HEAD</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="ml-4 bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                                Add Domain
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
