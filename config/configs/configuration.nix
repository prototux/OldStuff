{ config, pkgs, ... }:
let
	hostName = "${builtins.readFile /etc/hostname}";
in
{
	imports = [
		./hardware-configuration.nix
		./components
		(./machines + "/${hostName}.nix")
	];

	# Allow proprietary software.
	nixpkgs.config.allowUnfree = true;

	# Base components for every machine
	components.user.enable = true;
	components.user.name = "jason";
	components.shell.enable = true;
	components.security.enable = true;

	# Setting the timezone
	time.timeZone = "Europe/Berlin";

	# Console configuration
	i18n = {
		consoleFont = "Lat2-Terminus16";
		consoleKeyMap = "us";
		defaultLocale = "en_US.UTF-8";
	};

	# Define the hostname
	networking.hostName = "${hostName}";
}
