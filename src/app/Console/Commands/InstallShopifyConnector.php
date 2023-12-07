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
            '--tag' => "config"
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
    
        // Evaluating the content of app.php as a PHP array
        $appConfig = eval('?>' . $appConfigContent);
    
        // Adding the service provider, if not already added
        $providerClass = 'ShopifyConnector\\App\\Providers\\ShopifyConnectorProvider::class';
        if (!in_array($providerClass, $appConfig['providers'])) {
            $appConfig['providers'][] = $providerClass;
        }
    
        // Adding the facade alias, if not already added
        $aliasClass = 'ShopifyConnector\\App\\Facades\\ShopifyService::class';
        if (!isset($appConfig['aliases']['ShopifyService'])) {
            $appConfig['aliases']['ShopifyService'] = $aliasClass;
        }
    
        // Converting the array back to PHP code
        $appConfigExport = var_export($appConfig, true);
        $appConfigNewContent = "<?php\n\nreturn " . $appConfigExport . ";\n";
    
        // Writing the updated configuration back to app.php
        file_put_contents($appConfigPath, $appConfigNewContent);
    
        $this->info('Service provider and alias added to config/app.php');
    }
}
