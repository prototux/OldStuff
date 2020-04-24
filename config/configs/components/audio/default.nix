{config, pkgs, lib, ...}:
let
   cfg = config.components.audio;
in with lib;
{
	options = {
		components.audio = {
			enable = mkOption {
				default = false;
				type = with types; bool;
				description = ''
					Pulseaudio, music playback, stuff that does beep beep.
				'';
			};
		};
	};

	config = mkIf cfg.enable {
		hardware.pulseaudio.enable = true;
		environment.systemPackages = with pkgs; [
			pavucontrol
			moc
		];
	};
}
