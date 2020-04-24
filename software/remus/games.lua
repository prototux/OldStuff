-- Complete (merged) games list)
-- The games list is basically the same as sets
-- but with names, descriptions and rom names
-- being tables, each containing data from each
-- set, games is all sets merged in one table
local games = {}

local data = {}

local function findByRomset(roms)
	for osef, rom in pairs(roms) do
		local match = games.getBySHA1(rom.sha1)
		if match then
			return match
		end
	end
	return nil
end

local function addRoms(set, game, roms)
	for osef, rom in pairs(roms) do
		local match = nil
		for rofl, gamerom in pairs(game.roms) do
			if gamerom.sha1 == rom.sha1 then
				match = gamerom
			end
		end

		-- If the rom already exist for the game, just add the name, else, add the whole rom
		if match then
			match.names[set] = rom.name
		else
			local newrom = {}
			newrom.names = {}
			newrom.names[set] = rom.name
			newrom.size = rom.size
			newrom.crc = rom.crc
			newrom.md5 = rom.md5
			newrom.sha1 = rom.sha1
			table.insert(game.roms, newrom)
		end
	end
end

local function addGame(set, game)
	local newgame = {}
	newgame.names = {}
	newgame.descriptions = {}
	newgame.names[set] = game.name
	newgame.descriptions[set] = game.description
	newgame.roms = {}
	addRoms(set, newgame, game.roms)
	table.insert(data, newgame)
end

function games.load(sets)
	for osef, set in pairs(sets) do
		local setname = set.name
		for rofl, game in pairs(set.games) do
			local match = findByRomset(game.roms)
			if match then
				match.names[setname] = game.name
				match.descriptions[setname] = game.description
				addRoms(setname, match, game.roms)
			else
				addGame(setname, game)
			end
		end
	end
end

function games.getAll()
	return data
end

function games.getByAttr(attrname, attr)
	for osef, game in pairs(data) do
		for rofl, rom in pairs(game.roms) do
			if rom[attrname] == attr then
				return game, rom
			end
		end
	end
	return nil
end

function games.getByCRC(crc)
	return games.getByAttr("crc", crc)
end

function games.getByMD5(md5)
	return games.getByAttr("md5", md5)
end

function games.getBySHA1(sha1)
	return games.getByAttr("sha1", sha1)
end

function games.getByName(name)
	for osef, game in pairs(data) do
		for rofl,gamename in pairs(game.names) do
			if gamename == name then
				return game
			end
		end
	end
	return nil
end

function games.dumpAll()
	for osef, game in pairs(data) do
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
			print("  |-> Size: "..rom.size.." bytes")
			print("  |-> CRC32: "..rom.crc)
			print("  |-> SHA1: "..rom.sha1)
		end
	end
end

return games
