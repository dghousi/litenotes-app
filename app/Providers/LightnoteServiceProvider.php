<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LightnoteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->repositoriesArray();
    }

    protected function repositoriesArray()
    {
        $services =
        [
            'App\Interfaces\Note\NoteInterface' => 'App\Repositories\Note\NoteRepository',
        ];

        foreach ($services as $key => $value) {
            $this->app->singleton($key, $value);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
