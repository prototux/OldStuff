{config, pkgs, lib, ...}:
let
   cfg = config.components.user;
in with lib;
{
	options = {
		components.user = {
			enable = mkOption {
				default = false;
				type = with types; bool;
				description = ''
					My unix user.
				'';
			};

			name = mkOption {
				default = "username";
				type = with types; uniq string;
				description = ''
					Username.
				'';
       };
		};
	};

	config = mkIf cfg.enable {
		# Add my user
		users.extraUsers.${cfg.name} = {
			isNormalUser = true;
			extraGroups = [ "wheel" "networkmanager" ];
			uid = 1000;
		};

		system.activationScripts.userConfigFolder =
			if config.components.user.enable then
				stringAfter ["users"] ''
					export USERNAME=${cfg.name}
					/bin/sh ${./install.sh}
				''
		else stringAfter ["users"] '' '';
	};
}
