<?php

namespace App\Providers;

use App\Repositories\AdminInterface;
use App\Repositories\AdminRepository;
use App\Repositories\BroadcastInterface;
use App\Repositories\BroadcastRepository;
use App\Repositories\ChannelInterface;
use App\Repositories\ChannelRepository;
use App\Repositories\ChannelStatisticalInterface;
use App\Repositories\ChannelStatisticalRepository;
use App\Repositories\ContentBroadcastInterface;
use App\Repositories\ContentBroadcastRepository;
use App\Repositories\ContentInterface;
use App\Repositories\ContentKeywordInterface;
use App\Repositories\ContentKeywordRepository;
use App\Repositories\ContentRepository;
use App\Repositories\InteractionLogInterface;
use App\Repositories\InteractionLogRepository;
use App\Repositories\KeywordInterface;
use App\Repositories\KeywordRepository;
use App\Repositories\PasswordResetInterface;
use App\Repositories\PasswordResetRepository;
use App\Repositories\UserInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(KeywordInterface::class, KeywordRepository::class);
        $this->app->bind(InteractionLogInterface::class, InteractionLogRepository::class);
        $this->app->bind(ContentKeywordInterface::class, ContentKeywordRepository::class);
        $this->app->bind(ContentBroadcastInterface::class, ContentBroadcastRepository::class);
        $this->app->bind(ContentInterface::class, ContentRepository::class);
        $this->app->bind(ChannelStatisticalInterface::class, ChannelStatisticalRepository::class);
        $this->app->bind(ChannelInterface::class, ChannelRepository::class);
        $this->app->bind(BroadcastInterface::class, BroadcastRepository::class);
        $this->app->bind(AdminInterface::class, AdminRepository::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(PasswordResetInterface::class, PasswordResetRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
