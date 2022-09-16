<div class="flex justify-between">
    @role('Super Admin')
    <a href="{{url('log-viewer')}}" class="text-indigo-500 hover:text-gray-500">Log Viewer</a>
    @endcan    
    <p>version {{ config('app.version') }}</p>
</div>
