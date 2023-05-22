# WordPress starter theme
![GitHub all releases](https://img.shields.io/github/downloads/GentillePlume/wordpress-starter-theme/total?style=for-the-badge) ![GitHub repo size](https://img.shields.io/github/repo-size/GentillePlume/wordpress-starter-theme?style=for-the-badge) ![GitHub last commit](https://img.shields.io/github/last-commit/gentilleplume/wordpress-starter-theme?style=for-the-badge) ![GitHub release (latest by date including pre-releases)](https://img.shields.io/github/v/release/gentilleplume/wordpress-starter-theme?include_prereleases&style=for-the-badge)
![header cover](https://i.imgur.com/RcsXbEa.png)
Don't longer waste time by creating usual files and type usual code & functions in your wordpress theme project.

## Theme content
### Files
File name | Usage
------------ | -------------
header.php | The header template file usually contains your site’s document type, meta information, links to stylesheets and scripts, and other data.
footer.php | For generating the footer
functions.php | Imports, configs, and Custom Post Type
index.php | The main template file. It is required in all themes.
front-page.php | The front page template is always used as the site front page if it exists, regardless of what settings on Admin > Settings > Reading.
style.css | The main stylesheet. It is required in all themes and contains the information header for your theme.
404.php | The 404 template is used when WordPress cannot find a post, page, or other content that matches the visitor’s request.

### Folders
Folder name | Usage
------------ | -------------
/assets | Assets location
/sass | SASS files
/js | JavaScript scripts
/includes | Parts that are used multiple times in multiples pages (example: article card or section)

## SASS

Launch SASS engine to compile after code modification
```bash
npm run start
```
Build the CSS with autoprefixer & cleaner
```bash
npm run watch
```
