<?php

namespace ShopifyConnector\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallShopifyConnector extends Command
{
    protected $hidden = true;

    protected $signature = 'ShopifyConnector:install';

    protected $description = 'Install the ShopifyConnector Package';

    public function handle()
    {
        $this->info('Installing ShopifyConnector...');

        $this->info('Publishing configuration...');

        if (! $this->configExists('shopifyconnector.php')) {
            $this->publishConfiguration();
            $this->info('Published configuration');
        } else {
            if ($this->shouldOverwriteConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration($force = true);
            } else {
                $this->info('Existing configuration was not overwritten');
            }
        }

        $this->info('Installed ShopifyConnector');
    }

    private function configExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    private function shouldOverwriteConfig()
    {
        return $this->confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "ShopifyConnector\App\Providers\ShopifyConnectorProvider",
            '--tag' => "config"
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

       $this->call('vendor:publish', $params);
    }
}
