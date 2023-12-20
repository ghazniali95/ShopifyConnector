<?php

namespace Ghazniali95\ShopifyConnector\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Artisan command to install the ShopifyConnector package.
 *
 * This command automates the process of installing the ShopifyConnector
 * by publishing necessary configuration files and updating the application
 * configuration to include the ShopifyConnector service provider and alias.
 */
class InstallShopifyConnector extends Command
{
    /**
     * The command's visibility in the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = true;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ShopifyConnector:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the ShopifyConnector Package';

    /**
     * Execute the console command.
     *
     * Handles the logic for installing the ShopifyConnector package, including
     * publishing the configuration file and adding the service provider and alias.
     */
    public function handle()
    {
        $this->info('Installing ShopifyConnector...');

        // Check if the configuration file already exists
        if (!$this->configExists('shopifyconnector.php')) {
            $this->info('Publishing configuration...');
            $this->publishConfiguration();

            // Add the service provider and alias to the application configuration
            $this->addServiceProviderAndAlias();
            $this->info('Published configuration');
        } else {
            // Handle the situation where the configuration file already exists
            if ($this->shouldOverwriteConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration($force = true);
            } else {
                $this->info('Existing configuration was not overwritten');
            }
        }

        $this->info('Installed ShopifyConnector');
    }

    /**
     * Check if the given configuration file exists.
     *
     * @param string $fileName The name of the configuration file.
     * @return bool True if the file exists, false otherwise.
     */
    private function configExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    /**
     * Prompt the user to confirm if they want to overwrite an existing config file.
     *
     * @return bool True if the user confirms, false otherwise.
     */
    private function shouldOverwriteConfig()
    {
        return $this->confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    /**
     * Publish the configuration file for ShopifyConnector.
     *
     * @param bool $forcePublish Whether to force overwrite the existing configuration file.
     */
    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "Ghazniali95\ShopifyConnector\App\Providers\ShopifyConnectorProvider",
            '--tag' => "shopifyconnector"
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }

    /**
     * Add the ShopifyConnector service provider and alias to the application configuration.
     *
     * This method updates the config/app.php file to include the necessary
     * service provider and facade alias for the ShopifyConnector.
     */
    private function addServiceProviderAndAlias()
    {
        $appConfigPath = config_path('app.php');
        $appConfigContent = file_get_contents($appConfigPath);

        // Define the service provider and alias to be added
        $providerToAdd = "    Ghazniali95\ShopifyConnector\\App\\Providers\\ShopifyConnectorProvider::class,\n";
        $aliasToAdd = "    'ShopifyService' => Ghazniali95\ShopifyConnector\\App\\Facades\\ShopifyService::class,\n";

        // Insert the service provider and alias into the app configuration
        if (!str_contains($appConfigContent, $providerToAdd)) {
            $providersEndPos = strpos($appConfigContent, '],', strpos($appConfigContent, "'providers' =>"));
            $appConfigContent = substr_replace($appConfigContent, $providerToAdd, $providersEndPos, 0);
        }

        if (!str_contains($appConfigContent, $aliasToAdd)) {
            $aliasesEndPos = strpos($appConfigContent, '],', strpos($appConfigContent, "'aliases' =>"));
            $appConfigContent = substr_replace($appConfigContent, $aliasToAdd, $aliasesEndPos, 0);
        }

        // Write the updated configuration back to the file
        file_put_contents($appConfigPath, $appConfigContent);

        $this->info('Service provider and alias added to config/app.php');
    }
}
