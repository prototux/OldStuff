-- The main part, the collections
local collection = {}

local data = {}

local function cloneGames(data)
    if type(data) ~= "table" then
		return data
	end
    local target = {}
    for k, v in pairs(data) do
        if type(v) == "table" then
            target[k] = cloneGames(v)
        else
            target[k] = v
        end
    end
    return target
end

function collection.load(games, files)
	data = cloneGames(games)
	for osef, file in pairs(files.getAll()) do
		local game, rom = data.getBySHA1(file.sha1)
		if game then
			if rom.files == nil then
				rom.files = {}
			end
			table.insert(rom.files, file.name)
		end
	end
end

local function getRomsStats(roms)
	local total = #roms
	local found = 0
	for osef, rom in pairs(roms) do
		if rom.files ~= nil then
			found = found + 1
		end
	end
	return total, found
end

function collection.getComplete()
	local complete = {}

	for osef, game in pairs(data.getAll()) do
		local total, found = getRomsStats(game.roms)
		if total == found then
			table.insert(complete, game)
		end
	end

	return complete
end

function collection.getPartial()
	local partial = {}

	for osef, game in pairs(data.getAll()) do
		local total, found = getRomsStats(game.roms)
		if total ~= found and found >= 1 then
			table.insert(partial, game)
		end
	end

	return partial
end

function collection.getMissing()
	local missing = {}

	for osef, game in pairs(data.getAll()) do
		local total, found = getRomsStats(game.roms)
		if found == 0 then
			table.insert(missing, game)
		end
	end

	return missing
end

function collection.dumpAll()
	for osef, game in pairs(data.getAll()) do
		print("\n-------------------------------------------------------------------------------\n")
		print("|-> Names")
		for setname, name in pairs(game.names) do
			print(" |- ("..setname.."): "..name)
		end

		print("|-> Descriptions")
		for setname, description in pairs(game.descriptions) do
			print(" |- ("..setname.."): "..description)
		end

		print("|-> Roms")
		for osef, rom in pairs(game.roms) do
			print(" |------------")
			print("  |-> Names")
			for setname, name in pairs(rom.names) do
				print("   |- ("..setname.."): "..name)
			end
			print("  |-> Files")
			if rom.files then
				for rofl, file in pairs(rom.files) do
					print("   |- "..file)
				end
			end
			print("  |-> Size: "..rom.size.." bytes")
			print("  |-> CRC32: "..rom.crc)
			print("  |-> SHA1: "..rom.sha1)
		end
	end
end



return collection
