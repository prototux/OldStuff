#include <SFML/Window.hpp>
#include <SFML/Graphics.hpp>

namespace ne
{
	class Objects
	{
	  public:
	    Objects();
	    void Open(std::string filename, std::string name);
	    bool Display(std::string name);
	  private:
	  	struct s_object
	  	{
	  		s_object *next;
	  		std::string name;
	  		float *xvertices;
	  		float *yvertices;
	  		float *zvertices;
	  		float *ucoord;
	  		float *vcoord;
	  		int vertices;
	  		GLuint *textureHandles;
	  		// Add vertex shaders here?
	  	} typedef Object;
	  	Object *ObjectsList;
	  	int ObjectsCount;
	  	void (*Callback)();
	};
}