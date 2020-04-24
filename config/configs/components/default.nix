{ config, pkgs, ... }:
{
	imports = [
		./audio
		./desktop
		./dev
		./graphics
		./hack
		./messaging
		./security
		./shell
		./sysadmin
		./user
		./wifi
	];
}
