#include "Objects.hpp"
#include <algorithm>
#include <fstream>
#include <iostream>
#include <sstream>

#include <string>
std::vector<std::string> &split(const std::string &s, char delim, std::vector<std::string> &elems) {
    std::stringstream ss(s);
    std::string item;
    while (std::getline(ss, item, delim)) {
        elems.push_back(item);
    }
    return elems;
}


std::vector<std::string> split(const std::string &s, char delim) {
    std::vector<std::string> elems;
    return split(s, delim, elems);
}

using namespace ne;
Objects::Objects()
{
	Object *first = new Object;
	this->ObjectsList = first;
	this->ObjectsCount = 0;
}

void Objects::Open(std::string filename, std::string name)
{
	Object *NewObject;
	if (this->ObjectsCount)
		NewObject = new Object;
	else
		NewObject = this->ObjectsList;

	std::ifstream inFile(filename.c_str());
	int lines = std::count(std::istreambuf_iterator<char>(inFile), std::istreambuf_iterator<char>(), '\n');

	NewObject->name = name;
	NewObject->vertices = 0;
	NewObject->xvertices = (float*)malloc(sizeof(float)*lines);
	NewObject->yvertices = (float*)malloc(sizeof(float)*lines);
	NewObject->zvertices = (float*)malloc(sizeof(float)*lines);
	NewObject->ucoord = (float*)malloc(sizeof(float)*lines);
	NewObject->vcoord = (float*)malloc(sizeof(float)*lines);
	NewObject->textureHandles = (GLuint*)malloc(sizeof(GLuint)*lines);
    std::string line;
    std::ifstream myfile(filename.c_str());

	float nx, ny, nz;
	std::string texture = "null";
	std::string oldTexture;


	std::getline(myfile, line);
	std::cout << "TRI File " << filename << " version " << line << std::endl;


    while (std::getline(myfile, line))
    {

    	std::stringstream stream(line);
    	oldTexture = texture;

 		//Parse vertices
		stream >> NewObject->xvertices[NewObject->vertices] >> NewObject->yvertices[NewObject->vertices] >> NewObject->zvertices[NewObject->vertices];

		//Parse normales
		stream>> nx >> ny >> nz;

		//Parse UV coordinates and texture files, split textures if multi-texturing
		stream >> NewObject->ucoord[NewObject->vertices] >> NewObject->vcoord[NewObject->vertices] >> texture;
		if (texture.find(';'))
			texture = split(texture, ';')[0];
		if (oldTexture != texture)
		{
			//Load texture image
			sf::Image img_data;
			if (!img_data.LoadFromFile(texture))
				std::cout << "ERROR on " << texture << std::endl;

			//Add GL texture
			glGenTextures(1, &NewObject->textureHandles[NewObject->vertices]);
			glBindTexture(GL_TEXTURE_2D, NewObject->textureHandles[NewObject->vertices]);
			glTexImage2D(GL_TEXTURE_2D, 0, GL_RGBA, img_data.GetWidth(), img_data.GetHeight(), 0, GL_RGBA, GL_UNSIGNED_BYTE, img_data.GetPixelsPtr());
			glTexParameteri(GL_TEXTURE_2D, GL_TEXTURE_MIN_FILTER, GL_LINEAR);
			glTexParameteri(GL_TEXTURE_2D, GL_TEXTURE_MAG_FILTER, GL_LINEAR);
			glTexParameteri(GL_TEXTURE_2D, GL_TEXTURE_WRAP_S, GL_REPEAT);
			glTexParameteri(GL_TEXTURE_2D, GL_TEXTURE_WRAP_T, GL_REPEAT);
		}
		else
			NewObject->textureHandles[NewObject->vertices] = NewObject->textureHandles[NewObject->vertices-1];
		NewObject->vertices++;
    }

	if (this->ObjectsCount)
	{
		Object *TmpObject = this->ObjectsList;
		while (TmpObject->next)
			TmpObject = TmpObject->next;
		TmpObject->next = NewObject;
		NewObject->next = 0;
	}
	else
	{
		NewObject->next = 0;
		this->ObjectsCount++;
	}
}

bool Objects::Display(std::string name)
{

	Object *CurrentObject = this->ObjectsList;
	while (CurrentObject->next && CurrentObject->name != name)
		CurrentObject = CurrentObject->next;
	if (!CurrentObject->next && CurrentObject->name != name)
	{
		std::cout << "Error, object " << name << " not found!" << std::endl;
		return false;
	}

    glBegin(GL_TRIANGLES);
    glTexEnvf(GL_TEXTURE_ENV, GL_TEXTURE_ENV_MODE, GL_MODULATE);
    int current_handle = 0;
	glBindTexture(GL_TEXTURE_2D, 0);
    int i = 0;
    while (i != CurrentObject->vertices)
    {
    	if (current_handle != CurrentObject->textureHandles[i])
    	{
    		//In case we change the texture
      		glEnd();
    		glBindTexture(GL_TEXTURE_2D, CurrentObject->textureHandles[i]);
    		current_handle = CurrentObject->textureHandles[i];
      		glBegin(GL_TRIANGLES);

    	}
    	glTexCoord2f (CurrentObject->ucoord[i], 1.0-CurrentObject->vcoord[i]);
    	glVertex3f(CurrentObject->xvertices[i]*50, CurrentObject->yvertices[i]*50, CurrentObject->zvertices[i]*50);
    	i++;
    }
    glEnd();
    return true;
}