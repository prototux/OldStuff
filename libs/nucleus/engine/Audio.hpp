#include <SFML/Window.hpp>
#include <SFML/Audio.hpp>

namespace ne
{
	class Audio
	{
	  public:
	    Audio();
	    void PlaySound(std::string name);
	    void PlayMusic(std::string name);
	    void LoadSound(std::string filename, std::string name);
	  private:
	  	struct s_sound
	  	{
	  		s_sound *next;
	  		std::string name;
	  		sf::SoundBuffer Buffer;
			sf::Sound Sound;
	  	} typedef Sound;
	  	Sound *SoundsList;
	  	int SoundsCount;
    	sf::Music Music;
	};
}