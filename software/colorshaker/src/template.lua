local template = {}

local utils = require("utils")
local config = {}

local function color404(color)
	if color then
		print("[WARN] Color "..color.." not found ("..config.name..")")
	else
		print("[WARN] Color not found ("..config.name..")")
	end
	return config.format({ 255, 255, 255 }, utils)
end

local function renderColor(name)
	if name and config.colors.palette[name] then
		return config.format(config.colors.palette[name], utils)
	else
		return color404(name)
	end
end

local function renderTerm(name)
	return renderColor(config.colors.term[name])
end

local function renderSyntax(name)
	return renderColor(config.colors.syntax[name])
end

local function writeFile(name, content)
	local f = io.open(name, "w")
	f:write(content)
	f:close()
end

local function openTemplate(path)
	local f = io.open(path, "r")
	if f then
		local content = f:read("*all")
		f:close()
		return content
	else
		print("[ERROR]no template "..path.." for "..config.name)
		return nil
	end
end


function template.config(name, colors, format, scheme)
	config.colors = colors
	config.format = format
	config.name = name
	config.scheme = scheme
end

function template.render(path)
	local tpl = openTemplate(arg[0]:gsub("colorshaker$", "templates/")..config.name..".tpl")
	if tpl then
		print("=> Writing file "..path)
		writeFile(path, tpl:gsub("{{=(.-)}}", renderColor):gsub("{{>(.-)}}", renderTerm):gsub("{{%$(.-)}}", renderSyntax):gsub("{{@}}", config.scheme))
	end
end

return template
