#!/usr/bin/env lua
-- Remus: ROMs manager using multiple DAT files

local lfs = require("lfs")
local sets = require("sets")
local files = require("files")
local games = require("games")
local collection = require("collection")

-- Add sets then parse them
print("Loading sets databases")
sets.add("No Intro", "./test/nointro.dat")
sets.add("GoodSets", "./test/goodset.dat")

print("Loading ROMs")
files.load("./test/roms")

print("Merging sets databases")
games.load(sets.getAll())
--games.dumpAll()

print("Building collection")
collection.load(games, files)

-- Print some stats
local missing = collection.getMissing()
local partial = collection.getPartial()
local complete = collection.getComplete()
print("Stats, on a total of "..#games.getAll().." games in sets")
print("Got "..#missing.." missing games, "..#partial.." partial romsets, and "..#complete.." complete romsets")
