# MovieCraft Plugin

MovieCraft is a WordPress plugin that provides Gutenberg blocks for displaying movie content powered by The Movie Database (TMDb) API. It includes dynamic blocks for movie listings, sliders, and theatre shows with customizable display options.

## Blocks (from src/blocks)

-   **Movie Lists**: General movie listing block with genre filtering and layout customization
-   **Theatres Movies**: Displays currently showing movies in theatres
-   **Top Rated Movie Lists**: Showcases top-rated movies from TMDb
-   **Upcoming Movie Slider**: Interactive slider for upcoming movie releases with Interactivity API support
-   **Upcoming Movies**: Grid display of upcoming movies with genre filtering

## Admin Options (includes/Admin)

### Movie Craft Settings

Access the settings page at **Settings > Movie Craft** in your WordPress admin panel:

-   **The Movie Database API Key**: Configure your TMDb API key for all movie blocks
    -   The key is stored securely and not exposed via REST API
    -   Required for all movie-related blocks to function

### Getting a TMDb API Key

1. Create an account at [The Movie Database](https://www.themoviedb.org/)
2. Go to [API Settings](https://www.themoviedb.org/settings/api)
3. Request an API key
4. Use the API Read Access Token (v4 auth) for bearer token authentication

### Alternative: Environment Variables

You can also configure the API key via environment variables:

1. Copy `.env.example` to `.env` in the plugin root directory
2. Add your API key:
    ```
    MOVIE_BEARER_TOKEN=your_actual_bearer_token_here
    ```

**Priority Order**: WordPress admin settings (highest) > PHP constant `MOVIE_BEARER_TOKEN` > Environment variable (lowest)

## Setup

#### Composer setup

`composer install`

#### Install node dependencies

`npm install` or `yarn install`

#### Start development

`npm run start`

#### Build for production

`npm run build`

#### How to create a block inside src directory

`npx @wordpress/create-block@latest your-block-name --variant=dynamic --no-plugin`

## Testing

#### Jest (Unit Tests)

`npm run jest`

Run with coverage:
`npm run jest -- --coverage`

Watch mode:
`npm run jest:watch`

#### PHP Unit

Setup testing environment:
`bin/install-wp-tests.sh moviecraft root '' localhost 6.4.3`

Run tests:
`./vendor/bin/phpunit`

Run specific test file:
`./vendor/bin/phpunit --bootstrap tests/bootstrap.php tests/test-admin-options.php --verbose`

#### E2E Tests

`npm run test:e2e`

### Important links

[@wordpress/scripts documentation](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/)
[Minimal Block Example](https://github.com/WordPress/block-development-examples/tree/trunk/plugins/minimal-block-ca6eda)
[Block Development Examples](https://github.com/WordPress/block-development-examples/blob/trunk/plugins/data-basics-59c8f8/plugin.php)
