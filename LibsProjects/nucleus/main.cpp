#include "engine/nucleus.hpp"
sf::Clock Clock;
ne::Objects Env = ne::Objects();
GLfloat sun[]={1.0,1.0,1.0,1.0};
GLfloat position[]= {50, 150, 200};
ne::Engine *App;
ne::Audio *Audio;
#include <iostream>


void display()
{
    // Clear color and depth buffer
    glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT);

    glEnable(GL_LIGHTING);
    glEnable(GL_LIGHT0);
    glLightfv(GL_LIGHT0,GL_DIFFUSE, sun);
    glLightfv(GL_LIGHT0,GL_SPECULAR, sun);
    glLightf(GL_LIGHT0,GL_QUADRATIC_ATTENUATION,1.0/1000000000);
    glLightfv(GL_LIGHT0,GL_POSITION,position);

    glDisable(GL_FOG);


    // Apply some transformations
    glMatrixMode(GL_MODELVIEW);
    glLoadIdentity();
    glTranslatef(0.f, 0.f, -200.f);
    glRotatef(Clock.GetElapsedTime() * 50, 1.f, 0.f, 0.f);
    glRotatef(Clock.GetElapsedTime() * 30, 0.f, 1.f, 0.f);
    glRotatef(Clock.GetElapsedTime() * 90, 0.f, 0.f, 1.f);
    Env.Display("car");
    Env.Display("ground");
    //if (App->isKeyboardEvent())
    //    std::cout << "Key: " << App->isKeyboardEvent() << std::endl;
    if (App->isKeyboardEvent() == 'a')
        Audio->PlaySound("fire");
}

int main(int argc, char **argv)
{
    App = new ne::Engine(800, 600, "test", false);
    App->SetCallback(display);
    App->SetFPSLimit(100);
    App->SetVSync(true);
    //App.SetCursor(false);

    Audio = new ne::Audio();
    Audio->LoadSound("fire.wav", "fire");
    Audio->PlayMusic("taank.wav");

    //Init here
    Env.Open("clio.tri", "car");
    Env.Open("cocorobix.tri", "ground");

    //Launch the App
    App->Start();
    return 0;
}

