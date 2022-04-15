module.exports = {
  extends: ["airbnb-base", "plugin:prettier/recommended"],
  plugins: ["prettier"],
  parser: 'babel-eslint',
  env: {
    browser: true,
    es6: true,
    node: true
  },
  globals: {
    PRODUCTION: true,
    Drupal: true,
    drupalSettings: true,
    jQuery: true
  },
  rules: {
    "prettier/prettier": "error",
    "comma-dangle": ["error", "always-multiline"],
    "arrow-parens": ["error", "as-needed"],
    "no-console": 0
  },
  root: true
}
