# AMP Deprecator

> A WordPress plugin that allows administrators to selectively disable AMP (Accelerated Mobile Pages) functionality for specific categories on their website.

## Features

- Provides a simple settings page to choose which categories should have AMP disabled
- Removes the AMP links from the head of the posts in the selected categories
- Redirects AMP URLs to non-AMP versions for posts in disabled categories

## Usage

After installation and activation, follow these steps to disable AMP for specific categories:

1. Go to **Settings > Disable AMP by Category** in your WordPress admin dashboard.
2. Check the boxes next to any categories where you want to disable AMP functionality.
3. Click **Save Changes**

Once configured, the plugin will:

- Remove the link rel="amphtml" from posts in disabled categories.
- Redirect any AMP version requests to the standard version for posts in disabled categories.
- Show an AMP Status disable notice on post edit screens in non-production environments.

## Installation

### Using Composer

To install the plugin via Composer, follow these steps:

1. **Add the Repository:**
   - Open your project's `composer.json` file.
   - Add the following under the `repositories` section:

     ```json
     "repositories": [
         {
             "type": "vcs",
             "url": "https://github.com/xwp/amp-deprecator"
         }
     ]
     ```

2. **Require the Plugin:**
   - Run the following command in your terminal:

     ```bash
     composer require xwp/amp-deprecator
     ```

3. **Activate the Plugin:**
   - Once installed, activate the plugin through the 'Plugins' menu in WordPress.

### Manual Installation

1. **Download the Plugin:**
   - Download the `amp-deprecator` plugin folder.

2. **Upload the Plugin:**
   - Add the `amp-deprecator` folder to the `/wp-content/plugins/` directory of your WordPress installation.

3. **Activate the Plugin:**
   - Activate the plugin through the 'Plugins' menu in WordPress.

## Configuration

To disable AMP in specific categories, follow these steps:

1. Navigate to **Settings > Disable AMP by Category** in your WordPress admin
2. Check the boxes next to any categories where you want to disable AMP
3. Click **Save Changes**

After saving, posts in the selected categories will no longer have AMP enabled.

## Requirements

- **WordPress:** Version 6.5 or higher.
- **PHP:** Version 8.1 or higher.

## License

This plugin is licensed under the GPLv3 or later.
