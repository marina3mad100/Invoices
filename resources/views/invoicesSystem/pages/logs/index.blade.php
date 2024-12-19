@extends('invoicesSystem.layouts.master')

@section('title', 'Logs')

@section('content')
<style>
    .pagination span[aria-current="page"] span{
        background: rgb(59 130 246 / var(--tw-bg-opacity));
        color:#fff;
    }
</style>
<div class="p-6">
    <div class="bg-red-500 text-white px-2 py-1 text-sm font-semibold hidden error"></div>
    @if(session()->has('success'))
        <div class="bg-green-500 text-white px-2 py-1 text-sm font-semibold">
            {{ session()->get('success') }}
        </div>
    @endif


    <div class="overflow-x-auto" id="searchResult">
        <table class="w-full text-left border-collapse border dark:border-gray-800">
            <thead class="bg-gray-100 dark:bg-gray-800 text-sm text-left">
                <tr>
                    <th  class="px-4 py-2 font-light">#</th>
                    <th class="px-4 py-2 font-light">Log Name</th>
                    <th class="px-4 py-2 font-light">Description</th>
                    <th class="px-4 py-2 font-light">subject</th>
                    <th class="px-4 py-2 font-light">event</th>
                    <th class="px-4 py-2 font-light">user</th>
                    <th class="px-4 py-2 font-light">created at</th>
                    <th class="px-4 py-2 font-light">properties</th>
   
                </tr>
            </thead>
            <tbody id="users-list">
                @if ($logs->count() > 0)
                @foreach($logs as $key=>$row)
                    <tr class="border-b dark:border-gray-800">
                        <td class="px-4 py-2">{{ $logs->firstItem() + $key }}</td>
                        <td class="px-4 py-2">{{ $row->log_name }}</td>
                        <td class="px-4 py-2">{{ $row->description }}</td>
                        <td class="px-4 py-2">{{ $row->subject }}</td>
                        <td class="px-4 py-2">{{ $row->event }}</td>
                        <td class="px-4 py-2">{{ $row->causer }}</td>
                        <td class="px-4 py-2">{{ $row->created_at }}</td>
                        <td class="px-4 py-2">
                            {!! $row->properties_text  !!}
                        
                        </td>
                        
                        <td class="px-4 py-2">
                            <a href="{{ route('logs.destroy',$row->id) }}" class="text-blue-500 dark:text-blue-400 hover:text-blue-300">
                                <i data-feather="delete" class="w-5 h-5"></i>
                            </a>
						
                        </td>
                    </tr>  
                @endforeach
                @endif
                
            
            </tbody>
        </table>
        <div class="pagination">{!! $logs->appends(Request::all())->links() !!}</div>
	</div>

</div>

@endsection  

