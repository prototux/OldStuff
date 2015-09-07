#include <SFML/Window.hpp>

namespace ne
{
	class Engine
	{
	  public:
	    Engine(int width, int height, std::string title, bool isFullscreen);
	    void SetCallback(void (*callback)());
	    void SetFPSLimit(unsigned int limit);
	    void SetVSync(bool enabled);
	    void SetCursor(bool enabled);
	    void SetFullscreen(bool enabled);
	    void Start();
	    int isKeyboardEvent();
	  private:
	  	int KeyboardKey;
	  	sf::Window *App;
	  	std::string title;
	  	void (*Callback)();
	};
}