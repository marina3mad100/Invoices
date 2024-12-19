@extends('invoicesSystem.layouts.master')

@section('title', 'User - Create')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-semibold mb-6 dark:text-gray-200">Add a user</h1>
	<div class="bg-green-500 text-white px-2 py-1 text-sm font-semibold hidden success"></div>
	<div class="bg-red-500 text-white px-2 py-1 text-sm font-semibold hidden error"></div>
	<form id="user-item-form" class="space-y-6"  method="POST" enctype="multipart/form-data">
		@csrf
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Title (Required) -->
            <div>
                <label for="first_name" class="block text-sm mb-2 font-medium dark:text-gray-200">First Name <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="first_name" name="first_name"
                    class="w-full px-4 py-2 dark:bg-gray-800 dark:text-gray-200"
                    placeholder="Enter first name"
                    required
                />
            </div>

            <div>
                <label for="last_name" class="block text-sm mb-2 font-medium dark:text-gray-200">Last Name <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="last_name" name="last_name"
                    class="w-full px-4 py-2 dark:bg-gray-800 dark:text-gray-200"
                    placeholder="Enter last name "
                    required
                />
            </div>

            <div>
                <label for="email" class="block text-sm mb-2 font-medium dark:text-gray-200">Email <span class="text-red-500">*</span></label>
                <input
                    type="email"
                    id="email" name="email"
                    class="w-full px-4 py-2 dark:bg-gray-800 dark:text-gray-200"
                    placeholder="Enter email"
                    required
                />
            </div>

            <div>
                <label for="password" class="block text-sm mb-2 font-medium dark:text-gray-200">password <span class="text-red-500">*</span></label>
                <input
                    type="password"
                    id="password" name="password"
                    class="w-full px-4 py-2 dark:bg-gray-800 dark:text-gray-200"
                    placeholder="Enter password"
                    required
                />
            </div>
            <div>
                <label for="mobile_phone" class="block text-sm mb-2 font-medium dark:text-gray-200">Mobile Phone <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="mobile_phone" name="mobile_phone"
                    class="w-full px-4 py-2 dark:bg-gray-800 dark:text-gray-200"
                    placeholder="Enter Mobile Phone"
                    required
                />
            </div>

            <div>
                <label for="office_phone" class="block text-sm mb-2 font-medium dark:text-gray-200">Office Phone <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="office_phone" name="office_phone"
                    class="w-full px-4 py-2 dark:bg-gray-800 dark:text-gray-200"
                    placeholder="Enter Office Phone"
                    required
                />
            </div>

            <div>
                <label for="address" class="block text-sm mb-2 font-medium dark:text-gray-200">Address <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="address" name="address"
                    class="w-full px-4 py-2 dark:bg-gray-800 dark:text-gray-200"
                    placeholder="Enter address"
                    required
                />
            </div>

            <div>
                <label for="image" class="block text-sm mb-2 font-medium dark:text-gray-200">Image </label>
                <input type="file" id="image" accept="image/*" name="image" class="w-full px-4 py-2 border  dark:bg-gray-700 dark:text-gray-200" >

            </div>
            <div>
                <label for="user_type" class="block text-sm mb-2 font-medium dark:text-gray-200">Type</label>
                <select id="user_type" name="user_type" onchange="check_type()" class="w-full px-4 py-2 dark:bg-gray-800 dark:text-gray-200">
                    <option value="employee">Employee</option>
                    <option value="admin">Admin</option>
                    <option value="client">Client</option>

					
                </select>
            </div>	
            <!-- Distribution Members (Multiple Selector) -->
            <div id="permissions_div">
                <label for="permissions" class="block text-sm mb-2 font-medium dark:text-gray-200">Permissions <span class="text-red-500">*</span></label>
                <select id="permissions"  name="permissions[]" multiple class="choices w-full border dark:bg-gray-800 dark:text-gray-200">
            
                </select>
            </div>


            <div>
                <label for="status" class="block text-sm mb-2 font-medium dark:text-gray-200">status</label>
                <select id="status" name="status" class="w-full px-4 py-2 dark:bg-gray-800 dark:text-gray-200">
                @php
                $enums_list = \App\Enums\UserStatusEnum::cases();
                @endphp
                @foreach ($enums_list as $enum)
                    <option value="{{$enum->value}}" >{{$enum->text()}}</option>
                @endforeach

					
                </select>
            </div>			
			
	
			
        </div>

	
		@if(Auth()->user()->hasPermissionTo('add user'))
        <!-- Submit Button -->
        <button
            type="submit"
            class="submit_user_form px-4 py-2 bg-blue-500 text-white hover:bg-blue-600"
        >
            Submit User
        </button>
		@endif
    </form>
</div>
<script>
    function check_type(){
        $user_type = $('#user_type').val();
        if($user_type != 'client'){
            $('#permissions_div').show();
        }else{
            $('#permissions_div').hide();  
        }
    }
    let permissions ={!! json_encode($permissions) !!}  ;
    document.addEventListener('DOMContentLoaded', () => {
        populateChoices('permissions',permissions,true);     
     });

     $("#user-item-form").on("submit", function(event) {
        const form = document.getElementById("user-item-form");
        const formData = new FormData(form);     
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
                url: "{{ route('users.store') }}",
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