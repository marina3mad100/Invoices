<?php

namespace App\Providers;


use App\Repository\AdminRepositoryInterface;
use App\Repository\ClientRepositoryInterface;
use App\Repository\Eloquent\AdminRepository;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\ClientRepository;
use App\Repository\Eloquent\EmployeeRepository;
use App\Repository\Eloquent\InvoiceRepository;
use App\Repository\Eloquent\UserRepository;
use App\Repository\EloquentRepositoryInterface;
use App\Repository\EmployeeRepositoryInterface;
use App\Repository\InvoiceRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Services\AdminService;
use App\Services\ClientService;
use App\Services\EmployeeService;
use App\Services\InvoiceService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        

        
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(ClientService::class, function ($app) {
            return new ClientService($app->make(ClientRepositoryInterface::class));
        });
        
        
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(EmployeeService::class, function ($app) {
            return new EmployeeService($app->make(EmployeeRepositoryInterface::class));
        }); 

        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(AdminService::class, function ($app) {
            return new AdminService($app->make(AdminRepositoryInterface::class));
        }); 

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserService::class, function ($app) {
            return new UserService($app->make(UserRepositoryInterface::class));
        }); 

        $this->app->bind(InvoiceRepositoryInterface::class, InvoiceRepository::class);
        $this->app->bind(InvoiceService::class, function ($app) {
            return new InvoiceService($app->make(InvoiceRepositoryInterface::class));
        }); 

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
