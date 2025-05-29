# Product Review Manager

A WordPress plugin to manage product reviews with a custom post type, meta fields, shortcode, and REST API.

## Table of Contents

- [Product Review Manager](#product-reivew-manager)
  - [Getting Started](#getting-started)
    - [1. Installation](#1-installation)
    - [2. Development Setup](#2-development-setup)
    - [3. Customization and Coding](#3-customization-and-coding)
    - [4. Activate the Plugin](#4-activate-the-plugin)
  - [Permalink Structure](#permalink-structure)
  - [Recommendations](#recommendations)
  - [Shortcode Usage](#shortcode-usage)
  - [REST API Usage](#rest-api-usage)
  - [Changelog](#changelog)
  - [Contributing](#contributing)
  - [Authors](#authors)
  - [License](#license)

## GETTING STARTED

### 1. Installation

- Clone the repository to /wp-content/plugins/:

  ```sh
  git clone git@github.com:rabindratharu/product-review-manager.git
  ```

  Or download and upload the plugin files to /wp-content/plugins/product-review-manager.

### 2. Development Setup

<details>
 <summary> Don't have <code>Node.js</code> + <code>npm</code> installed? You have to install them first. (CLICK TO EXPAND)</summary>

Go to the Node's site [download + install](https://nodejs.org/en/download/) Node on your system. This will install both `Node.js` and `npm`, i.e., node package manager — the command line interface of Node.js.

You can verify the installation by opening your terminal app and typing...

```sh
node -v
# Results into 7.19.1 — or installed version.

npm -v
# Results into v14.15.1 — or installed version.
```

</details>

Follow the following steps to add your functionalities to the plugin:

1. Navigate to plugin files `/wp-content/plugins/product-review-manager`, and open the terminal app.
2. Run the `npm install` command to install npm dependencies, and wait sometimes to complete it.

### 3. Customization and Coding

1. Navigate to plugin files `/wp-content/plugins/product-review-manager`, and open the terminal app.
2. Run the `npm run start` command to initialize development environment with a live development server.
3. Run the `npm run build` command to generate optimized production files for the plugin.
4. Run the `grunt i18n` command to generate and update .pot file for the languages.
5. Run the `grunt release` command to zip files of plugin.


### 4. Activate the Plugin

It's safe to activate the plugin at this point. Activate the plugin through the `Plugins` screen in WordPress

## Permalink Structure

- Go to your WordPress dashboard and click on Settings > Permalinks.
- You can select from pre-defined structures Post name.
- Click on the Save Changes button to apply your new permalink structure.

## Recommendations

- Need to install and activate [WooCommerce](https://wordpress.org/plugins/woocommerce/) plugin as well used prouct to select in customer review meta box filed.
- Create products or import sample_products.csv items.

## Shortcode Usage

We need to create product reveiws item first through dashboard->product reviews->add product review.

- Add shortcode `[product_reviews]` on your post or page content area.
- Use the `<?php echo do_shortcode('[product_reviews]'); ?>` function in PHP to execute a shortcode within a theme file.

## REST API Usage

REST API endpoint to fetch product reviews allow filtering by rating via query params.

- `https://example.com/wp-json/prm/v1/reviews` display all product reivews posts.
- `https://example.com/wp-json/prm/v1/reviews?rating=4` display all product reivews posts which have only 4 start rating value.
- `https://example.com/wp-json/prm/v1/reviews?rating=3-5` display all product reivews posts which have rating values between 3 and 5.

Replace `https://example.com/` to your local site url or live site url.

## Changelog

### 1.0.0

- Initial Release

## Contributing

Thank you for your interest in contributing to Project Product Review Manager. To submit your changes, please follow the steps outlined below.

1. **Fork the Repository:** Click on the "Fork" button on the top right corner of the repository page to create your fork.

2. **Clone your Fork:** Clone your forked repository to your local machine using the following command:

   ```sh
   git clone git@github.com:rabindratharu/product-review-manager.git
   ```

3. **Create a Feature Branch:** Create a new branch for your feature or bug fix:

   ```sh
   git checkout -b my-new-feature
   ```

4. **Make Changes:** Add your changes to the project. You can use the following command to stage all changes:

   ```sh
   git add .
   ```

5. **Commit Changes:** Commit your changes with a descriptive commit message:

   ```sh
   git commit -am 'Add some feature'
   ```

6. **Push to your Branch:** Push your changes to the branch you created on your fork:

   ```sh
   git push origin my-new-feature
   ```

7. **Submit a Pull Request:** Go to the Pull Requests tab of the original repository and click on "New Pull Request." Provide a clear title and description for your changes, and submit the pull request.

## Authors

- **Rabindra Tharu** - [rabindratharu](https://www.linkedin.com/in/rabindratharu/)

See also the list of [contributors](https://github.com/rabindratharu/product-review-manager) who participated in this project.


## License

- GPL-2.0-or-later