<?php
// registerProvider.php

// The service provider you want to add
$provider = "Ghazniali95\\ShopifyConnector\\Providers\\ShopifyConnectorProvider::class";

// Path to the Laravel application's config file
$configPath = 'config/app.php';

if (file_exists($configPath)) {
    $config = file_get_contents($configPath);

    // Check if the provider is already registered
    if (strpos($config, $provider) === false) {
        // Locate the providers array in the config file
        $updatedConfig = str_replace("'providers' => [", "'providers' => [\n        $provider,", $config);

        // Write the updated content back to the file
        file_put_contents($configPath, $updatedConfig);
    }
} else {
    echo "The config/app.php file does not exist.";
}