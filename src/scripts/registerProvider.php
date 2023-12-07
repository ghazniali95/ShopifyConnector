<?php
 
// registerProvider.php

// The service provider you want to add
$provider = "ShopifyConnector\\Providers\\ShopifyConnectorProvider::class";
 

// Get the absolute path of the script's directory (assumed to be the root of your project config/app.php file is in the root of your) 
$configPath = config_path('app.php'); 
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
