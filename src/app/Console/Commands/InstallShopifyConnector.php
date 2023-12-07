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

        if (!$this->configExists('shopifyconnector.php')) {

            $this->info('Publishing configuration...');
            $this->publishConfiguration();
            // Modify config/app.php
            $this->addServiceProviderAndAlias();
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
            '--tag' => "shopifyconnector"
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }

    private function addServiceProviderAndAlias()
    {
        $appConfigPath = config_path('app.php');
        $appConfigContent = file_get_contents($appConfigPath);

        // Define the service provider and alias
        $providerToAdd = "    ShopifyConnector\\App\\Providers\\ShopifyConnectorProvider::class,\n";
        $aliasToAdd = "    'ShopifyService' => ShopifyConnector\\App\\Facades\\ShopifyService::class,\n";

        // Insert the service provider
        if (!str_contains($appConfigContent, $providerToAdd)) {
            $providersEndPos = strpos($appConfigContent, '],', strpos($appConfigContent, "'providers' =>"));
            $appConfigContent = substr_replace($appConfigContent, $providerToAdd, $providersEndPos, 0);
        }

        // Insert the alias
        if (!str_contains($appConfigContent, $aliasToAdd)) {
            $aliasesEndPos = strpos($appConfigContent, '],', strpos($appConfigContent, "'aliases' =>"));
            $appConfigContent = substr_replace($appConfigContent, $aliasToAdd, $aliasesEndPos, 0);
        }

        // Write the updated content back to the file
        file_put_contents($appConfigPath, $appConfigContent);

        $this->info('Service provider and alias added to config/app.php');
    }
}
