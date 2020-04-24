#include "Engine.hpp"
#include <SFML/Window.hpp>

#include <iostream>
ne::Engine::Engine(int width, int height, std::string title, bool isFullscreen)
{
    this->App = new sf::Window(sf::VideoMode(width, height, 32), title, (isFullscreen)?sf::Style::Fullscreen:sf::Style::Close);
    this->title = title;

    // Set color and depth clear value
    glClearDepth(1.f);
    glClearColor(0.f, 0.f, 0.f, 0.f);

    // Enable Z-buffer read and write
    glEnable(GL_DEPTH_TEST);
    glDepthMask(GL_TRUE);

    // Setup a perspective projection
    glMatrixMode(GL_PROJECTION);
    glLoadIdentity();
    gluPerspective(90.f, 1.f, 1.f, 500.f);
    glEnable(GL_TEXTURE_2D);
}

void ne::Engine::SetCallback(void (*callback)())
{
    this->Callback = callback;
}

void ne::Engine::SetFPSLimit(unsigned int limit)
{
    this->App->SetFramerateLimit(limit);
}

void ne::Engine::SetVSync(bool enabled)
{
    this->App->UseVerticalSync(enabled);
}

void ne::Engine::SetCursor(bool enabled)
{
    this->App->ShowMouseCursor(enabled);
}

void ne::Engine::SetFullscreen(bool enabled)
{
    this->App->Create(sf::VideoMode(this->App->GetWidth(), this->App->GetHeight(), 32), this->title, (enabled)?sf::Style::Fullscreen:sf::Style::Close);
}

int ne::Engine::isKeyboardEvent()
{
    return this->KeyboardKey;
}

void ne::Engine::Start()
{
    while (this->App->IsOpened())
    {
        this->KeyboardKey = false;
        // Process events
        sf::Event Event;
        while (App->GetEvent(Event))
        {
            // Close window : exit
            if (Event.Type == sf::Event::Closed)
                App->Close();

            // Escape key : exit
            if ((Event.Type == sf::Event::KeyPressed) && (Event.Key.Code == sf::Key::Escape))
                App->Close();

            if ((Event.Type == sf::Event::KeyPressed))
                this->KeyboardKey = Event.Key.Code;

            // Resize event : adjust viewport
            if (Event.Type == sf::Event::Resized)
                glViewport(0, 0, Event.Size.Width, Event.Size.Height);
        }
        App->SetActive();
        this->Callback();
        App->Display();
    }
}