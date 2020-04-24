local format = {}

format.name = "ITerm2 plist"
format.extension = ".itermcolors"

function format.color(color, utils)
	local fcolors = utils.rgb2float(color)

	function makeColor(name, value) return "                <key>"..name.." Component</key>\n                <real>"..value.."</real>\n" end
	local blue = makeColor("Blue", fcolors[3])
	local green = makeColor("Green", fcolors[2])
	local red = makeColor("Red", fcolors[1])
	return blue..green..red
end

return format
