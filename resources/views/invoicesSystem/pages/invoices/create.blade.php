@extends('invoicesSystem.layouts.master')

@section('title', 'Invoice - Create')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-semibold mb-6 dark:text-gray-200">Add a Invoice</h1>
	<div class="bg-green-500 text-white px-2 py-1 text-sm font-semibold hidden success"></div>
	<div class="bg-red-500 text-white px-2 py-1 text-sm font-semibold hidden error"></div>
	<form id="user-item-form" class="space-y-6"  method="POST" enctype="multipart/form-data">
		@csrf
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Title (Required) -->
            <div>
                <label for="number" class="block text-sm mb-2 font-medium dark:text-gray-200">Number <span class="text-red-500">*</span></label>
                <input
                    type="text" 
                    id="number" name="number"
                    class="w-full px-4 py-2 dark:bg-gray-800 dark:text-gray-200"
                    placeholder="Enter Number"
                    required
                />
            </div>

            <div>
                <label for="title" class="block text-sm mb-2 font-medium dark:text-gray-200">Title <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="title" name="title"
                    class="w-full px-4 py-2 dark:bg-gray-800 dark:text-gray-200"
                    placeholder="Enter title "
                    required
                />
            </div>
            <div>
                <label for="payment_status" class="block text-sm mb-2 font-medium dark:text-gray-200">Payment status <span class="text-red-500">*</span></label>
                <select id="payment_status"  name="payment_status" class="choices w-full border dark:bg-gray-800 dark:text-gray-200">
                    <option value="paid">Paid</option>
                    <option value="unpaid">Unpaid</option>
                </select>
            </div>


            <div>
                <label for="amount" class="block text-sm mb-2 font-medium dark:text-gray-200 ">Amount <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="amount" name="amount"
                    class="w-full px-4 py-2 dark:bg-gray-800 dark:text-gray-200 float"
                    placeholder="Enter Amount"
                    required
                />
            </div>

            <div>
                <label for="date" class="block text-sm mb-2 font-medium dark:text-gray-200">date <span class="text-red-500">*</span></label>
                <input
                    type="date"
                    id="date" name="date"
                    class="w-full px-4 py-2 dark:bg-gray-800 dark:text-gray-200"
                    placeholder="Enter date"
                    required
                />
            </div>


            
            <div>
                <label for="user" class="block text-sm mb-2 font-medium dark:text-gray-200">Client <span class="text-red-500">*</span></label>
                <select id="user"  name="user_id" class="choices w-full border dark:bg-gray-800 dark:text-gray-200">
            
                </select>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-4">   
            
            <div>
                <label for="description" class="block text-sm mb-2 dark:text-gray-200">Description</label>
                <textarea
                    id="description" name="description"
                    class="w-full px-4 py-2 dark:bg-gray-800 dark:text-gray-200"
                    rows="4"
                    placeholder="Enter description"
                ></textarea>
            </div>
			
        </div>



        <!-- Submit Button -->
        @if(\Auth::user()->userable_type != \App\Models\Client::class)
			@if(Auth()->user()->hasPermissionTo('add invoice'))
				<button
					type="submit"
					class="submit_user_form px-4 py-2 bg-blue-500 text-white hover:bg-blue-600"
				>
					Submit Invoice
				</button>
			@endif
        @endif
    </form>
</div>
<script>
    $('input.float').on('input', function() {
    this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
    });

    document.addEventListener('DOMContentLoaded', () => {
        let users ={!! json_encode($users) !!}  ;
        users=users.map(function(item) {
                return {'value':item.user.id , 'label':item.first_name+' '+item.last_name};
        })	;
        populateChoices('user',users,false);     
     });

     $("#user-item-form").on("submit", function(event) {
        const form = document.getElementById("user-item-form");
        const formData = new FormData(form); 
        formData.append('description',tinyMCE.get('description').getContent());
    
            $('.error').hide();
            $('.success').hide();
            $('.err-msg').hide();
            $(".error").html("");
            $(".success").html("");
            event.preventDefault();  
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('invoices.store') }}",
                type: "POST",
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {
                    $(".submit_user_form").prop('disabled', true);
                },
                success: function(data) {
                    if (data.success) {
                         
                        $(".submit_user_form").prop('disabled', false);
                        
                        $("#user-item-form")[0].reset();
						
						window.scrollTo(0,0);

                        $('.success').show();
                        $('.success').html('<div class= "text-white-500  px-2 py-1 text-sm font-semibold">'+data.success+'</div>');  
						
                        $('#file-list').html('');
						setInterval(function() {
							location.reload();
						}, 3000);						


                    }
                    else if(data.error){
                        $(".submit_user_form").prop('disabled', false);

                        $('.error').show();
                        $('.error').html('<div class= "text-white-500  px-2 py-1 text-sm font-semibold">'+data.error+'</div>');
                    }
                },
                error: function (err) {
                    $.each(err.responseJSON.errors, function(key, value) {
                            var el = $(document).find('[name="'+key + '"]');
							el.after($('<div class= "err-msg text-red-500  px-2 py-1 text-sm font-semibold">' + value[0] + '</div>'));
                            if(el.length == 0){
                                el = $(document).find('#file-upload');
								el.after($('<div class= "err-msg text-red-500  px-2 py-1 text-sm font-semibold">the documents must be pdf </div>'));
								
                            }
                            
                        });

                        $(".submit_user_form").prop('disabled', false);


                }
            });
    
      });
	     
 
</script>
@endsection