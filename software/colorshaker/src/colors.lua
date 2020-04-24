#!/usr/bin/lua
local colors = {}

local utils = require("utils")

local palette = {}
local syntax = {}
local term = {}

-- Get the color name and hexcode after having stripped newlines
function colors.parsePalette(line)
	local name, hexcode = line:gsub("\n", ""):match("(.-)=(#.*)")
	palette[name] = {utils.hex2rgb(hexcode)}
end

function colors.parseTerm(line)
	local name, color = line:gsub("\n", ""):match("(.-)=(.*)")
	term[name] = color
end

function colors.parseSyntax(line)
	local name, color = line:gsub("\n", ""):match("(.-)=(.*)")
	syntax[name] = color
end

-- Read the file, remove comments, remove blank lines, then iterate over each line in parser()
function colors.parse(content, parser)
	content:gsub("#+%s.-\n", ""):gsub("\n+", "\n"):gsub("\n?.-\n", parser)
end

function colors.getAll()
	return { palette = palette, syntax = syntax, term = term }
end

return colors
