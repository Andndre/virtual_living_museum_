<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">{{ __('app.admin_dashboard') }}</h1>
                <p class="mt-2 text-gray-600">{{ __('app.admin_dashboard_desc') }}</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users text-blue-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">{{ __('app.total_users') }}</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-book text-green-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">{{ __('app.total_materi') }}</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_materi'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-red-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">{{ __('app.total_situs') }}</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_situs'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-flag text-yellow-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">{{ __('app.total_reports') }}</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_laporan'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-comments text-purple-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">{{ __('app.total_feedback') }}</dt>
                                    <dd class="text-2xl font-bold text-gray-900">{{ $stats['total_kritik_saran'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg mb-8">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">{{ __('app.quick_actions') }}</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.users') }}" class="flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <i class="fas fa-users mr-2"></i>
                            {{ __('app.manage_users') }}
                        </a>
                        <a href="{{ route('admin.materi') }}" class="flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors">
                            <i class="fas fa-book mr-2"></i>
                            {{ __('app.manage_materi') }}
                        </a>
                        <a href="{{ route('admin.situs') }}" class="flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition-colors">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ __('app.manage_situs') }}
                        </a>
                        <a href="{{ route('admin.reports') }}" class="flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 transition-colors">
                            <i class="fas fa-flag mr-2"></i>
                            {{ __('app.manage_reports') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Users -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">{{ __('app.recent_users') }}</h3>
                        <div class="flow-root">
                            <ul class="-my-5 divide-y divide-gray-200">
                                @forelse($recentUsers as $user)
                                <li class="py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($user->profile_photo)
                                                <img class="h-8 w-8 rounded-full" src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-500 text-sm"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                                        </div>
                                        <div class="flex-shrink-0 text-sm text-gray-500">
                                            {{ $user->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <li class="py-4 text-center text-gray-500">
                                    {{ __('app.no_users_yet') }}
                                </li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('admin.users') }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                                {{ __('app.view_all_users') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Reports -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">{{ __('app.recent_reports') }}</h3>
                        <div class="flow-root">
                            <ul class="-my-5 divide-y divide-gray-200">
                                @forelse($recentReports as $report)
                                <li class="py-4">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-flag text-yellow-500"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900">{{ $report->nama_peninggalan }}</p>
                                            <p class="text-sm text-gray-500">{{ __('app.by') }} {{ $report->user->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $report->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <li class="py-4 text-center text-gray-500">
                                    {{ __('app.no_reports_yet') }}
                                </li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('admin.reports') }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200">
                                {{ __('app.view_all_reports') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Feedback -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">{{ __('app.recent_feedback') }}</h3>
                        <div class="flow-root">
                            <ul class="-my-5 divide-y divide-gray-200">
                                @forelse($recentFeedback as $feedback)
                                <li class="py-4">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-comments text-purple-500"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900">{{ Str::limit($feedback->pesan, 100) }}</p>
                                            <p class="text-sm text-gray-500">{{ __('app.by') }} {{ $feedback->user->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $feedback->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <li class="py-4 text-center text-gray-500">
                                    {{ __('app.no_feedback_yet') }}
                                </li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('admin.feedback') }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200">
                                {{ __('app.view_all_feedback') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">{{ __('app.system_info') }}</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-sm text-gray-600">{{ __('app.total_storage_used') }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ number_format(disk_free_space(storage_path()) / 1024 / 1024 / 1024, 2) }} GB {{ __('app.available') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-sm text-gray-600">{{ __('app.laravel_version') }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ app()->version() }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-600">{{ __('app.php_version') }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ PHP_VERSION }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
