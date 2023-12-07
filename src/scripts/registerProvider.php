<?php

use Illuminate\Support\Facades\URL;
// registerProvider.php

// The service provider you want to add
$provider = "ShopifyConnector\\Providers\\ShopifyConnectorProvider::class";

// Define the relative path to the Laravel application's config file
$relativeConfigPath = '/config/app.php';

// Get the absolute path of the script's directory (assumed to be the root of your Laravel project)
$projectRootPath = URL::to('/');

// Construct the absolute path to the config file
$configPath = $projectRootPath . $relativeConfigPath;
echo $configPath;

if (file_exists($configPath)) {
    $config = file_get_contents($configPath);

    // Check if the provider is already registered
    if (strpos($config, $provider) === false) {
        // Locate the providers array in the config file
        $updatedConfig = str_replace("'providers' => [", "'providers' => [\n        $provider,", $config);

        // Write the updated content back to the file
        file_put_contents($configPath, $updatedConfig);
        echo "Provider added to config/app.php\n";
    } else {
        echo "Provider already registered in config/app.php\n";
    }
} else {
    echo "The config/app.php file does not exist.\n";
    exit(1); // Exit with an error code
}
