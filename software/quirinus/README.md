# Quirinus
A dat files tool/converter for ROM sets

# Purpose
This is a .dat file manipulation tool, to detect/convert/etc rom set files

# Usage
Use quirinus --help

# Examples
* quirinus -d games.dat << Will print games.dat format
* quirinus -c -f logiqx games.dat << Will convert games.dat into Logiqx XML
* quirinus -n ./roms << Will output a clrmamepro dat file from files that are into ./roms

# Formats supported
* clrmamepro
* romcenter
* logiqx

# Other formats it may support in the future
* mamexml
* sabredat
* superdat
* romvault (apparently the format used by trurip?)

# Ressources
* Quite obviously, getting different datfiles
* https://github.com/mnadareski/wizzardRedux/wiki/DAT-File-Formats
* http://www.logiqx.com/DatFAQs/RomCenter.php
* http://www.logiqx.com/DatFAQs/CMPro.php

# Notes
Apparently, the clrmamepro format is quite documented (ironic for a proprietary format), the romcenter and logiqx ones also have some documentations, there's xml schema for logiqx and sabredat, and the others doesn't seems to have much documentation...

# Corrections
As it seems the datfiles formats are informal, it's quite possible that my modules contains bugs, don't hesitate to contribute! :)

# Internals
This is based on my early work on Remus, basically, it loads modules that contains functions to detect, parse and write each file format, Quirinus is centered around a Lua table that contains games/rom data, independantly on the source format.
Because it was based on a tool that was here to print informations and statistics on my ROM collection, the whole thing is built with the "let's load the data, now what can i do with it?" mentality, it probably have an effect on the source code.
