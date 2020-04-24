{ config, lib, pkgs, ... }:
with lib;
{
	# Enable the components
	components.desktop.enable = true;
	components.audio.enable = true;
	components.graphics.enable = true;

	# Enable the OpenSSH daemon.
	services.openssh.enable = true;
	# !!! DEBUG ONLY !!!
	services.openssh.permitRootLogin = "yes";

	# Bootloader
	boot.loader.efi.canTouchEfiVariables = true;
	boot.loader.gummiboot.enable = true;
}
