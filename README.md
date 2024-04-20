# WordPress theme boilerplate

![GitHub repo size](https://img.shields.io/github/repo-size/GentillePlume/wordpress-starter-theme?style=for-the-badge) ![GitHub last commit](https://img.shields.io/github/last-commit/gentilleplume/wordpress-starter-theme?style=for-the-badge) ![GitHub release (latest by date including pre-releases)](https://img.shields.io/github/v/release/gentilleplume/wordpress-starter-theme?include_prereleases&style=for-the-badge)
![header cover](https://i.imgur.com/RcsXbEa.png)
Don't longer waste time by creating usual files and type usual code & functions in your wordpress theme project.

## Theme content

### Files

| File name          | Usage                                                                                                                                    |
| ------------------ | ---------------------------------------------------------------------------------------------------------------------------------------- |
| header.php         | The header template file usually contains your site’s document type, meta information, links to stylesheets and scripts, and other data. |
| footer.php         | For generating the footer                                                                                                                |
| functions.php      | Imports, configs, and Custom Post Type                                                                                                   |
| index.php          | The main template file. It is required in all themes.                                                                                    |
| front-page.php     | The front page template is always used as the site front page if it exists, regardless of what settings on Admin > Settings > Reading.   |
| 404.php            | The 404 template is used when WordPress cannot find a post, page, or other content that matches the visitor’s request.                   |
| customtemplate.php | Custom template.                                                                                                                         |
| screenshot.webp    | Theme thumbnail.                                                                                                                         |
| style.css          | The main stylesheet. It is required in all themes and contains the information header for your theme.                                    |

### Folders

| Folder name | Usage                                                                                    |
| ----------- | ---------------------------------------------------------------------------------------- |
| assets      | Assets location (fonts, css, js, images)                                                 |
| includes    | Parts that are used multiple times in multiples pages (example: article card or section) |
| sass        | SASS files                                                                               |

## Style

### SASS

Launch SASS engine to compile after code modification

```bash
npm run watch
```

Complete CSS render (SASS compilation, autoprefixing and minification)

```bash
npm run deploy
```

Autoprefix CSS

```bash
npm run build-autoprefix
```

Minify CSS

```bash
npm run build-cleancss
```

Compile SASS to CSS

```bash
npm run build-sass
```
