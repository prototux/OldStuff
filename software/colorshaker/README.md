![#Color Shaker](https://i.imgur.com/WZEDh3J.png)

Serve your own colors concoction to multiple terminal emulators and code editors ([Here's an example](https://github.com/prototux/cocktail))

# Ingredients

* A file containing the colore palette
* A file matching colors in the palette to the 16+3 ANSI colors
* A file matching colors in the palette to syntax hightlighting elements

# What you get

Configuration files for terminal emulators and text editors, see [LIST.md](https://github.com/prototux/makePalette/blob/master/LIST.md) to get a list of supported formats.

# How to use it

Run colorshaker with parameters below, details on file formats, templates and how to kill people in the wiki.

### parameters:
* NAME                 Name of the color scheme (required)
* -p, --palette=FILE   Palette file (default: palette.txt)
* -t, --term=FILE      Terminal color scheme file (default: termcolors.txt)
* -s, --syntax=FILE    Syntax hightlighting file (default: syntaxcolors.txt)
* -o, --output=FOLDER  Output folder (default: output)

# Dependancies

* lua (5.1 or 5.2)
* lua-filesystem
* lua-cliargs
