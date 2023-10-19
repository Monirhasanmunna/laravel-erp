<?php

namespace App\Providers;

use App\Helper\Helper;
use App\Http\View\Composers\ModuleComposer;
use App\Http\View\Composers\ViewComposer;
use App\Models\Color;
use App\Models\Institution;
use App\Models\Module;
use App\Services\BackendTemplateService;
use App\Services\InstituteBranchService;
use App\Services\TemplateService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('template',function ($app){
            return new  TemplateService(Institution::with('template')->find(Helper::getInstituteId()));
        });

        $this->app->bind('backend-template',function ($app){
            return new  BackendTemplateService(Institution::with('backendTemplate')->find(Helper::getInstituteId()));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        View::composer(['admin.partial.*'], ModuleComposer::class);


        if (env('APP_ENV') === 'production') {
            \URL::forceScheme('https');
        }

        try {
            $template = collect(app()->make('backend-template')->getTemplate())->toArray();

            if (!empty($template) && isset($template['template']['path_name'])) {
                View::share('adminTemplate', $template['template']['path_name']);
            }
            View::composer(['frontent.theme1.layouts*'], ViewComposer::class);

        } catch (\Exception $e) {
            return null;
        }

    }
}
