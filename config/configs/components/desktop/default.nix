{config, pkgs, lib, ...}:
let
   cfg = config.components.desktop;
in with lib;
{
	options = {
		components.desktop = {
			enable = mkOption {
				default = false;
				type = with types; bool;
				description = ''
					That ugly frankenstein creature that is my desktop.
				'';
			};
		};
	};

	config = mkIf cfg.enable {
		services.xserver.enable = true;
		services.xserver.layout = "us";
		#services.xserver.displayManager.nodm.enable = true;
		services.xserver.windowManager.awesome.enable = true;
		environment.systemPackages = with pkgs; [
			rxvt_unicode
			uzbl
		];
	};
}
