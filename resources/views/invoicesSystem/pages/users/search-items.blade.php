<table class="w-full text-left border-collapse border dark:border-gray-800">
    <thead class="bg-gray-100 dark:bg-gray-800 text-sm text-left">
        <tr>
            <th  class="px-4 py-2 font-light">#</th>
            <th class="px-4 py-2 font-light">First Name</th>
            <th class="px-4 py-2 font-light">Last Name</th>
            <th class="px-4 py-2 font-light">Email</th>
            <th class="px-4 py-2 font-light">Address</th>
            <th class="px-4 py-2 font-light">Mobile Phone</th>
            <th class="px-4 py-2 font-light">Office Phone</th>
            <th class="px-4 py-2 font-light">type</th>
            <th class="px-4 py-2 font-light">status</th>
            <th class="px-4 py-2 font-light">Actions</th>
        </tr>
    </thead>
    <tbody id="users-list">
        @if ($users->count() > 0)
        @foreach($users as $key=>$row)
            <tr class="border-b dark:border-gray-800">
                <td class="px-4 py-2">{{ $users->firstItem() + $key }}</td>
                <td class="px-4 py-2">{{ $row->userable->first_name }}</td>
                <td class="px-4 py-2">{{ $row->userable->last_name }}</td>
                <td class="px-4 py-2">{{ $row->userable->email }}</td>
                <td class="px-4 py-2">{{ $row->userable->address }}</td>
                <td class="px-4 py-2">{{ $row->userable->mobile_phone }}</td>
                <td class="px-4 py-2">{{ $row->userable->office_phone }}</td>
                <td class="px-4 py-2">{{ $row->user_type}}</td>
                <td class="px-4 py-2">
                    <span class="text-white px-2 py-1 rounded {{ $row->userable->status->color() }}">{{ $row->userable->status->text() }}</span>
                
                </td>
                <td class="px-4 py-2">
					@if(Auth()->user()->hasPermissionTo('delete user'))
						@if(\Auth::user()->id != $row->id)
						<a href="{{ route('users.destroy',$row->id) }}" class="text-blue-500 dark:text-blue-400 hover:text-blue-300">
							<i data-feather="delete" class="w-5 h-5"></i>
						</a>
						@endif
					@endif
					@if(Auth()->user()->hasPermissionTo('edit user'))
					<a target="_blank" href="{{ route('users.edit',$row->id) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-300">
						<i data-feather="edit" class="w-5 h-5"></i>
					</a>
					@endif
                </td>
            </tr>  
        @endforeach
        @endif
        
    
    </tbody>
</table>
<div class="pagination" id="pagination">{!! $users->appends(Request::all())->links() !!}</div>