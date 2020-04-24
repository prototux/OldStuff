local utils = {}

function utils.hex2rgb(hex)
	hex = hex:gsub("#","")
	return tonumber("0x"..hex:sub(1,2)), tonumber("0x"..hex:sub(3,4)), tonumber("0x"..hex:sub(5,6))
end

function utils.rgb2lhex(rgb)
	local color = "#"
	for k, v in pairs(rgb) do
		color = color..string.format((v < 16) and "0%x" or "%x", v)
	end
	return color
end

function utils.rgb2uhex(rgb)
	local color = "#"
	for k, v in pairs(rgb) do
		color = color..string.format((v < 16) and "0%X" or "%X", v)
	end
	return color
end

function utils.rgb2lhex16(rgb)
	local color = "#"
	for k, v in pairs(rgb) do
		color = color..string.format((v < 16) and "0%x0%x" or "%x%x", v, v)
	end
	return color
end

function utils.rgb2uhex16(rgb)
	local color = "#"
	for k, v in pairs(rgb) do
		color = color..string.format((v < 16) and "0%X0%X" or "%X%X", v, v)
	end
	return color
end

function utils.rgb2float(rgb)
	return { rgb[1]/255, rgb[2]/255, rgb[3]/255 }
end

function utils.rgb2percent(rgb)
	return { math.floor((rgb[1]/255)*100), math.floor((rgb[2]/255)*100), math.floor((rgb[3]/255)*100) }
end

return utils
