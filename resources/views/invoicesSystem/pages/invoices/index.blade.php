@extends('invoicesSystem.layouts.master')

@section('title', 'Invoices')

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
    <div class="items-center mb-4">

        <div class="flex items-center mr-auto">
            @if(\Auth::user()->userable_type != \App\Models\Client::class)
                @if(Auth()->user()->hasPermissionTo('add invoice'))
                    <a href="{{ route('invoices.create') }}" class="inline-flex px-4 py-2 bg-blue-500 text-white hover:bg-blue-600"><i data-feather="plus" class="mr-2"></i> Create</a>
                @endif
            @endif
        </div>

        <!-- Search and Actions -->
		@if(Auth()->user()->hasPermissionTo('view all invoices') || \Auth::user()->userable_type == \App\Models\Client::class)
        <div class="mt-5 flex justify-between	 items-center mr-auto">
            <div class="relative mr-4">
                <i data-feather="search" stroke-width=2 class="absolute left-2 top-2 text-gray-700 dark:text-gray-300"></i>
                <input
                    id="search"
                    type="text" name="search"
                    placeholder="Search"
                    class="pl-10 pr-4 py-2 border-0 bg-gray-200 dark:bg-gray-700 dark:text-gray-200"
                />
            </div>

            <div class="relative mr-4">
                <select id="length" name="length">
                    <option value="1">1</option>
                    <option value="2">2</option>

                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>

                </select>
            </div>
            
        
        </div>
		@endif

    </div>

	@if(Auth()->user()->hasPermissionTo('view all invoices') || \Auth::user()->userable_type == \App\Models\Client::class)
    <div class="overflow-x-auto" id="searchResult">
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
        <div class="pagination">{!! $invoices->appends(Request::all())->links() !!}</div>
	</div>
	@endif

</div>
<script>
  $(document).ready(function() {
        $(document).on('click', '#pagination a', function (e) {
            let length = $('#length').val();
            let page = $(this).attr('href').split('page=')[1];
            let length_chk = $(this).attr('href').split('length=');
            if(length_chk.length == 1){
                getInvoices($(this).attr('href') +'&length='+length , page , length);
            }else{
                getInvoices($(this).attr('href') , page , length);
            }         
            e.preventDefault();
        });

        $(document).on('change', '#length', function (e) {
            let length = $(this).val();
            let search = $('input[name=search]').val();
            let page = window.location.href.split('page=');
            let length_chk = window.location.href.split('length=');
            let url = `{{ route('invoices.search') }}`;
            if(page.length == 1){
                getInvoices(url+'?page=1&length='+length+'&search='+search , 1 , length);
                
            }else{
               page = page[1];
               getInvoices(url+'?page='+page+'&length='+length+'&search='+search , 1 , length);
 
            }
            //alert(page.length);
           // getInvoices(window.location.href ,length);
            e.preventDefault();
        });  

    });
    function getInvoices(href , page , length) {
        $.ajax({
            url : href,
            dataType: 'json',
        }).done(function (data) {
            $('#searchResult').html(data.html);
			feather.replace();
        }).fail(function () {
            alert('Posts could not be loaded.');
        });
    }
    $(document).ready(function(){
 

        $('input[name=search]').on('input', function(e){
        let length = $('#length').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('invoices.search') }}",
                type: "GET",
                data: {'search': e.target.value , 'length' : length},
                dataType: 'json',
                success: function(data) {
                    if (data.success) {

                        $('#searchResult').html(data.html);
                        feather.replace();

                        
                    }
                },
                error: function (err) {
                

                        $(".error").html("<div class='alert alert-danger error'>Some Error Occurred!</div>")
                    }
                });
            
            
        });
    });
   </script>
@endsection  

