    <table class="w-full text-left border-collapse border dark:border-gray-800">
        <thead class="bg-gray-100 dark:bg-gray-800 text-sm text-left">
            <tr>
                <th class="px-4 py-2 font-light">number</th>
                <th class="px-4 py-2 font-light">title</th>
                <th class="px-4 py-2 font-light">User</th>
                <th class="px-4 py-2 font-light">Email</th>
                <th class="px-4 py-2 font-light">Mobile Phone</th>
                <th class="px-4 py-2 font-light">Amount</th>
                <th class="px-4 py-2 font-light">Date</th>
                <th class="px-4 py-2 font-light">Description</th>
                <th class="px-4 py-2 font-light">Payment Status</th>
                @if(\Auth::user()->userable_type != \App\Models\Client::class)
                <th class="px-4 py-2 font-light">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody id="invoices-list">
            @if ($invoices->count() > 0)
            @foreach($invoices as $key=>$row)
                <tr class="border-b dark:border-gray-800">
                    <td class="px-4 py-2">{{ $row->number }}</td>
                    <td class="px-4 py-2">{{ $row->title }}</td>
                    <td class="px-4 py-2">{{ $row->user->userable->first_name }} {{ $row->user->userable->last_name }}</td>
                    <td class="px-4 py-2">{{ $row->user->userable->email }}</td>
                    <td class="px-4 py-2">{{ $row->user->userable->mobile_phone }}</td>

                    <td class="px-4 py-2">{{ $row->amount }}</td>
                    <td class="px-4 py-2">{{ $row->date }}</td>
                    <td class="px-4 py-2">{!! $row->description !!}</td>
                    <td class="px-4 py-2">{{ $row->payment_status }}</td>
                    @if(\Auth::user()->userable_type != \App\Models\Client::class)
                    <td class="px-4 py-2">
                        @if(Auth()->user()->hasPermissionTo('delete invoice'))
                            <a href="{{ route('invoices.destroy',$row->id) }}" class="text-blue-500 dark:text-blue-400 hover:text-blue-300">
                                <i data-feather="delete" class="w-5 h-5"></i>
                            </a>
                        @endif	
                        @if(Auth()->user()->hasPermissionTo('edit invoice'))
                        <a target="_blank" href="{{ route('invoices.edit',$row->id) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-300">
                            <i data-feather="edit" class="w-5 h-5"></i>
                        </a>
                        @endif
                    </td>
                    @endif
                </tr>  
            @endforeach
            @endif
            
        
        </tbody>
    </table>

<div class="pagination" id="pagination">{!! $invoices->appends(Request::all())->links() !!}</div>