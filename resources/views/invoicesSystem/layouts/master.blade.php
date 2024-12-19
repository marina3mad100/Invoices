<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
 

</head>
<body class="flex flex-col h-screen bg-gray-100 dark:bg-gray-800">


 
   @include('invoicesSystem.layouts.header')


   @include('invoicesSystem.layouts.sidebar')


    
    <div class="p-4 pt-28 sm:ml-64">
       <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
         @yield('content')

       </div>
    </div>
    


</body>
</html>