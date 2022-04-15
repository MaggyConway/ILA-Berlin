# webpack4-starter-kit

Webpack 4 with webpack-dev-server configuration.

## Requirements

- Node >= v6.x
- Yarn >= v1.1 | NPM >= v5.0

## Install dependencies

```
$ cd my-app-name
$ yarn
```

## Linters
There are two configs for stylelint. Default is `.stylelintrc` wich is used by IDE's stylelint to highlight quality mistakes. Another config is `.stylintrc-format` extends from default config and also includes rules for code formating based on unified convention. Also there are [prettier](https://prettier.io) to help formating code. ESLing + Prettier are used for JS linting and fixing.

## Available tasks

```sh

# Runs development server (Webpack dev server)
$ yarn dev

# Build command
$ yarn prod

# Lint scss
$ yarn lint:scss

# Lint js
$ yarn lint:js

# Lint all
$ yarn lint

# Format scss
$ yarn format:scss

# Format js
$ yarn format:js

# Format all
$ yarn format

```

## Features

* [Webpack 4](https://github.com/webpack/webpack)
* [HMR](https://webpack.js.org/concepts/hot-module-replacement/)
* [Babel](https://babeljs.io/)
* [EsLint](https://eslint.org/docs/user-guide/getting-started)
* [StyleLint](https://github.com/stylelint/stylelint)
* [Sass](https://github.com/webpack-contrib/sass-loader)
* [Autoprefixer](https://github.com/postcss/autoprefixer)
